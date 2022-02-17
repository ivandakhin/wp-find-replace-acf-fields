<?php 
function check_acf_import(){
    $csv_file       = file_get_contents( $_POST['csv'] );
    $csv_content    = explode( "\r\n", $csv_file );
    $csv_table      = array_map( 'str_getcsv', $csv_content );
    $csv_head       = array_shift( $csv_table );
    $csv_pattern    = explode( "=>", $csv_head[2] );
    
    if($csv_file && count($csv_table) >= 1){
        $str = '<div class="uk-alert-success" uk-alert>';
        $str .= 'CSV file uploaded successfully. Run import to replace <b>'. count($csv_table) .'</b> posts';
        $str .= '</div>';
        $str .= '<button id="run-import" data-csv="'. $_POST['csv'] .'" data-ajax-url="'. site_url() .'/wp-admin/admin-ajax.php" class="uk-button uk-button-secondary">Run Import</button>';
    }else if(!$csv_file){
        $str = '<div class="uk-alert-danger" uk-alert>';
        $str .= 'CSV file not loaded. Check if the URL is correct';
        $str .= '</div>';
    }else if(count($csv_table) < 1){
        $str = '<div class="uk-alert-danger" uk-alert>';
        $str .= 'CSV file uploaded successfully. No posts were found to replace.';
        $str .= '</div>';
    }else{
        $str = '<div class="uk-alert-warning" uk-alert>';
        $str .= 'Unknown error.';
        $str .= '</div>';
    }

    echo $str;
    die();
}

add_action('wp_ajax_acf_check_import', 'check_acf_import');
add_action('wp_ajax_nopriv_acf_check_import', 'check_acf_import');
?>


