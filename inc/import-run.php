<?php 

function run_acf_import(){
    $csv_file       = file_get_contents( $_POST['csv'] );
    $csv_content    = explode( "\r\n", $csv_file );
    $csv_table      = array_map( 'str_getcsv', $csv_content );
    $csv_head       = array_shift( $csv_table );
    $csv_pattern    = explode( "=>", $csv_head[2] );

    preg_match( '/(.*)\((.*)\)/', $csv_pattern[1], $acf_update );

    $acf_update_keys    = $csv_pattern[0];
    $acf_update_type    = $acf_update[1];
    $acf_update_depth   = explode( ", ", $acf_update[2] );

    $counter = 0;

    foreach($csv_table as $csv_index => $csv_row){
        $post_id = $csv_row[0];
         
        switch ($acf_update_type) {
            case "array":
                $value = preg_split('/\r\n|[\r\n]/',  $csv_row[2]);
                break;

            case "object":
                $value = [];
                $input_values = preg_split('/\r\n|[\r\n]/',  $csv_row[2]);
                foreach($input_values as $input_value_index => $input_value){
                    $input_array = explode( ", ", $input_value );
                    $repeater_item = array_combine( $acf_update_depth, $input_array);
                    array_push($value, $repeater_item);
                };

                break;

            default:
                $value = $csv_row[2];
        }

        update_field( $acf_update_keys, $value, $post_id );
        $counter++;
    }

    $str = '<div class="uk-alert-success" uk-alert>';
    $str .= '<a class="uk-alert-close" uk-close></a>';
    $str .= '<p>The import was successful, ' . $counter . ' posts were changed.</p>';
    $str .= '</div>';

    echo $str;

    die();
}

add_action('wp_ajax_acf_run_import', 'run_acf_import');
add_action('wp_ajax_nopriv_acf_run_import', 'run_acf_import');