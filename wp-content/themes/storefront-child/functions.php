<?php
/**
 * Storefront Child Theme Functions
 * Handles Custom Post Type, Taxonomy, Widget, and AJAX functionality
 */

/**
 * Enqueue parent and child theme styles
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    if (is_page_template('template-cities.php')) {
        wp_enqueue_script('sc-ajax', get_stylesheet_directory_uri() . '/assets/js/ajax.js', ['jquery'], '1.0.1', true);
        wp_localize_script('sc-ajax', 'scAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('sc_search_cities_nonce')
        ]);
    }
});

/**
 * Register Custom Post Type: Cities
 */
add_action('init', function () {
    register_post_type('cities', [
        'labels' => [
            'name'          => __('Cities', 'storefront-child'),
            'singular_name' => __('City', 'storefront-child'),
            'menu_name'     => __('Cities', 'storefront-child'),
            'add_new'       => __('Add New City', 'storefront-child'),
            'add_new_item'  => __('Add New City', 'storefront-child'),
            'edit_item'     => __('Edit City', 'storefront-child'),
            'new_item'      => __('New City', 'storefront-child'),
            'view_item'     => __('View City', 'storefront-child'),
            'search_items'  => __('Search Cities', 'storefront-child'),
            'not_found'     => __('No cities found', 'storefront-child'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-location',
    ]);

    /**
     * Register Custom Taxonomy: Countries
     */
    register_taxonomy('countries', ['cities'], [
        'labels' => [
            'name'          => __('Countries', 'storefront-child'),
            'singular_name' => __('Country', 'storefront-child'),
            'search_items'  => __('Search Countries', 'storefront-child'),
            'all_items'     => __('All Countries', 'storefront-child'),
            'edit_item'     => __('Edit Country', 'storefront-child'),
            'update_item'   => __('Update Country', 'storefront-child'),
            'add_new_item'  => __('Add New Country', 'storefront-child'),
            'new_item_name' => __('New Country Name', 'storefront-child'),
        ],
        'hierarchical'  => true,
        'show_in_rest'  => true,
        'public'        => true,
    ]);
});

/**
 * Add Metabox for City Coordinates
 */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'sc_city_coordinates',
        __('City Coordinates', 'storefront-child'),
        function ($post) {
            wp_nonce_field('sc_save_city_coordinates', 'sc_city_coordinates_nonce');
            $latitude = get_post_meta($post->ID, '_sc_latitude', true);
            $longitude = get_post_meta($post->ID, '_sc_longitude', true);
            ?>
            <p>
                <label for="sc_latitude"><?php esc_html_e('Latitude', 'storefront-child'); ?></label>
                <input type="number" step="any" name="sc_latitude" id="sc_latitude" value="<?php echo esc_attr($latitude); ?>" class="widefat">
            </p>
            <p>
                <label for="sc_longitude"><?php esc_html_e('Longitude', 'storefront-child'); ?></label>
                <input type="number" step="any" name="sc_longitude" id="sc_longitude" value="<?php echo esc_attr($longitude); ?>" class="widefat">
            </p>
            <?php
        },
        'cities',
        'normal',
        'high'
    );
});

/**
 * Save Metabox Data
 */
add_action('save_post_cities', function ($post_id) {
    if (!isset($_POST['sc_city_coordinates_nonce']) || !wp_verify_nonce($_POST['sc_city_coordinates_nonce'], 'sc_save_city_coordinates')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = ['sc_latitude', 'sc_longitude'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, "_{$field}", floatval($_POST[$field]));
        } else {
            delete_post_meta($post_id, "_{$field}");
        }
    }
});

/**
 * City Temperature Widget
 */
class SC_City_Temperature_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'sc_city_temperature_widget',
            __('City Temperature', 'storefront-child'),
            ['description' => __('Displays city name and current temperature', 'storefront-child')]
        );
    }

    public function widget($args, $instance) {
        if (empty($instance['city_id'])) {
            return;
        }

        $city = get_post($instance['city_id']);
        if (!$city) {
            return;
        }

        $latitude = get_post_meta($city->ID, '_sc_latitude', true);
        $longitude = get_post_meta($city->ID, '_sc_longitude', true);
        $temperature = $this->get_temperature($latitude, $longitude);

        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($city->post_title) . $args['after_title'];
        echo '<p>' . esc_html__('Temperature', 'storefront-child') . ': ' . 
             ($temperature !== false ? esc_html(round($temperature, 1)) . '°C' : 'N/A') . '</p>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        $cities = get_posts([
            'post_type'      => 'cities',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC'
        ]);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('city_id')); ?>">
                <?php esc_html_e('Select City:', 'storefront-child'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('city_id')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('city_id')); ?>">
                <option value=""><?php esc_html_e('Select a city', 'storefront-child'); ?></option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->ID); ?>" <?php selected($city_id, $city->ID); ?>>
                        <?php echo esc_html($city->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        return ['city_id' => !empty($new_instance['city_id']) ? absint($new_instance['city_id']) : 0];
    }

    public function get_temperature($lat, $lon) {
        if (empty($lat) || empty($lon)) {
            return false;
        }

        $api_key = defined('OPENWEATHERMAP_API_KEY') ? OPENWEATHERMAP_API_KEY : '';
        if (empty($api_key)) {
            return false;
        }

        $url = sprintf(
            'https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s&units=metric&appid=%s',
            urlencode($lat),
            urlencode($lon),
            urlencode($api_key)
        );

        $response = wp_remote_get($url, ['timeout' => 5]);
        if (is_wp_error($response)) {
            return false;
        }

        $data = json_decode(wp_remote_retrieve_body($response));
        return isset($data->main->temp) ? floatval($data->main->temp) : false;
    }
}
add_action('widgets_init', function() {
    register_widget('SC_City_Temperature_Widget');
});

/**
 * AJAX Search Handler with Temperature
 */
add_action('wp_ajax_sc_search_cities', 'sc_search_cities');
add_action('wp_ajax_nopriv_sc_search_cities', 'sc_search_cities');
/**
 * AJAX Search Handler for Cities
 */
function sc_search_cities() {
    check_ajax_referer('sc_search_cities_nonce', 'nonce');

    $search = isset($_POST['search']) ? sanitize_text_field(trim($_POST['search'])) : '';

    global $wpdb;
    $query = "
        SELECT p.ID, p.post_title, t.name as country, 
               pm1.meta_value as latitude, pm2.meta_value as longitude
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_sc_latitude'
        LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_sc_longitude'
        WHERE p.post_type = 'cities'
        AND p.post_status = 'publish'
    ";

    // Add search condition only if search term is not empty
    $params = [];
    if (!empty($search)) {
        $query .= " AND p.post_title LIKE %s";
        $params[] = '%' . $wpdb->esc_like($search) . '%';
    }

    $query .= " LIMIT 20";

    // Prepare and execute query
    $results = $wpdb->get_results($wpdb->prepare($query, $params));

    // Add temperature data
    $widget = new SC_City_Temperature_Widget();
    foreach ($results as &$result) {
        $result->temperature = $widget->get_temperature($result->latitude, $result->longitude);
        $result->temperature = $result->temperature !== false ? 
            round($result->temperature, 1) . '°C' : 'N/A';
    }

    wp_send_json_success($results);
}
?>