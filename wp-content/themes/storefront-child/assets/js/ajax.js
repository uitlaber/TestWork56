jQuery(document).ready(function($) {
    $('#city-search').on('input', function() {
        var searchTerm = $(this).val();

        $.ajax({
            url: scAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'sc_search_cities',
                nonce: scAjax.nonce,
                search: searchTerm
            },
            success: function(response) {
                if (response.success) {
                    var html = '';
                    $.each(response.data, function(i, row) {
                        html += '<tr>' +
                            '<td>' + row.post_title + '</td>' +
                            '<td>' + (row.country || 'N/A') + '</td>' +
                            '<td>' + (row.temperature || 'N/A') + '</td>' +
                            '</tr>';
                    });
                    $('#cities-table tbody').html(html);
                }
            }
        });
    });
});