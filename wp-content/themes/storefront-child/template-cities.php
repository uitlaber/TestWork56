<?php
/**
 * Template Name: Cities List
 * Template Post Type: page
 *
 * Displays a searchable table of cities with their countries and temperatures
 */
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="cities-search">
            <input type="text" id="city-search" placeholder="<?php _e('Search cities...', 'storefront-child'); ?>">
        </div>

        <?php do_action('sc_before_cities_table'); ?>

        <table id="cities-table" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('City', 'storefront-child'); ?></th>
                    <th><?php _e('Country', 'storefront-child'); ?></th>
                    <th><?php _e('Temperature (°C)', 'storefront-child'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                $widget = new SC_City_Temperature_Widget();
                $results = $wpdb->get_results("
                    SELECT p.post_title, t.name as country, pm1.meta_value as latitude, pm2.meta_value as longitude
                    FROM {$wpdb->posts} p
                    LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
                    LEFT JOIN {$wpdb->terms} t ON tr.term_taxonomy_id = t.term_id
                    LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_sc_latitude'
                    LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_sc_longitude'
                    WHERE p.post_type = 'cities' 
                    AND p.post_status = 'publish'
                ");

                foreach ($results as $row) :
                    $temperature = $widget->get_temperature($row->latitude, $row->longitude);
                ?>
                    <tr>
                        <td><?php echo esc_html($row->post_title); ?></td>
                        <td><?php echo esc_html($row->country ?: 'N/A'); ?></td>
                        <td><?php echo esc_html($temperature ? $temperature.'°C' : 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php do_action('sc_after_cities_table'); ?>
    </main>
</div>

<?php
get_footer();
?>