(function($) {
    $(document).ready(function() {
        $('#load-import').on('click', function() {
            const self = this;
            const regex = /https:\/\/docs\.google\.com\/spreadsheets\/d\/(.*?[^\/])\/.*?gid=(.*?[^\/])$/gm;
            const subst = `https://docs.google.com/spreadsheets/d/$1/export?format=csv&gid=$2`;

            let import_url = $('#import-form').find('input[name=import-url]').val();
            let ajax_url = $(self).data('ajax-url');

            $(self).html('<div uk-spinner></div>');
            $('#import-output').html('');

            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    'action': 'acf_check_import',
                    'csv': import_url.replace(regex, subst),
                },
                success: function(data) {
                    $('#import-output').html(data);
                    $(self).html('<span>LOAD</span>');
                }
            });
        });

        $('#import-output').on('click', '#run-import', function() {
            const self = this;
            let ajax_url = $(self).data('ajax-url');

            $(self).html('<div uk-spinner></div>');

            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    'action': 'acf_run_import',
                    'csv': $(self).data('csv')
                },
                success: function(data) {
                    $('#import-output').html(data);
                }
            });
        })

        $('#load-export').on('click', function() {
            const self = this;
            let ajax_url = $(self).data('ajax-url');
            let export_acf_key = $('#export-key').val();
            let export_post_type = $("#export-form input[type='radio']:checked").val();

            $(self).html('<div uk-spinner></div>');

            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    'action': 'acf_run_export',
                    'export_acf_key': export_acf_key,
                    'export_post_type': export_post_type,
                },
                success: function(data) {
                    $('#export-output').html(data);
                    $(self).html('<span>LOAD</span>');
                }
            });
        })
    })
})(jQuery);