<?php 
function run_acf_export(){
    $file_name = 'acf-export-' . rand(111111, 999999);
    
    $data = array();
    $counter = 0;
    $acf_pattern = '';
    $posts_query = new WP_Query(array(
        'post_type' => $_POST['export_post_type'],
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
    ));

    if (!$posts_query->have_posts()) {
        $str = '<div class="uk-alert-danger" uk-alert>';
        $str .= 'No result found in that range, please <strong>reselect and try again</strong>!';
        $str .= '</div>';
        echo $str;
        return;
    }


    if(isset($_POST['export_acf_key'])){
        $export_acf_key = $_POST['export_acf_key'];
        while ($posts_query->have_posts()):
            $data['acf_field'][$counter] = (isset($data['acf_field'][$counter]) ? "" : null);
            $data['post_id'][$counter] = (isset($data['post_id'][$counter]) ? "" : null);
            $data['url'][$counter] = (isset($data['url'][$counter]) ? "" : null);
            $posts_query->the_post();
            $array = get_field($export_acf_key, get_the_ID());
            $value = '';
            if(is_array($array)){
                if(is_array($array[0])){
                    $keys = array();
                    foreach($array as $key => $sub_array){
                        $array[$key] = implode(', ', $sub_array);
                        array_push($keys, $key);
                    }
                    $keys = implode(', ', $keys);
                    $acf_pattern = $export_acf_key . '=>object(' . $keys . ')';
                    $value = implode('\r\n', $array);
                }else{
                    $value = implode('\r\n', $array);
                    $acf_pattern = $export_acf_key . '=>array()';
                }
            }else{
                $value = $array;
                $acf_pattern = $export_acf_key . '=>text()';
            }

            $data['acf_field'][$counter] .= $value;
            $data['post_id'][$counter] .= get_the_ID();
            $data['url'][$counter] .=  get_permalink();
            $counter++;
        endwhile;

        $csv_file_data = '';
        $headers = array();
        $file_source = plugin_dir_path(__DIR__) . '/exports/'. $file_name . '.CSV';
        $csv_file = @fopen($file_source, "w") or die("Unable to create a file on your server!");
        fprintf($csv_file, "\xEF\xBB\xBF");
        $headers[] = 'Post ID';
        $headers[] = 'URLs';
        $headers[] = $acf_pattern;
        fputcsv($csv_file, $headers);
        
        for ($i = 0; $i < $counter; $i++) {
            $csv_file_data = array(
                isset($data['post_id']) ? $data['post_id'][$i] : "",
                isset($data['url']) ? $data['url'][$i] : "",
                isset($data['acf_field']) ? $data['acf_field'][$i] : "",
            );
            fputcsv($csv_file, $csv_file_data);
        }
    
        fclose($csv_file);
    }

    $str = '<a id="download-export" href="'. plugin_dir_url(__DIR__) . '/exports/'. $file_name . '.CSV' .'" class="uk-button uk-button-secondary" data-ajax-url="<?= site_url() ?>/wp-admin/admin-ajax.php" >';
    $str .= '<span>Download CSV</span>';
    $str .= '</a>';

    $str .= '<table class="uk-table uk-table-hover uk-table-justify uk-table-divider">';
    $str .= '<thead>';
    $str .=     '<tr>';
    $str .=         '<th class="uk-width-small">Post ID</th>';
    $str .=         '<th class="uk-width-medium">Link</th>';
    $str .=         '<th>'. $acf_pattern .'</th>';
    $str .=     '</tr>';
    $str .= '</thead>';
    $str .= '<tbody>';

    for ($i = 0; $i < $counter; $i++){
        $str .= '<tr>';
        $str .= '<td>'. $data['post_id'][$i].'</td>';
        $str .= '<td>'. $data['url'][$i].'</td>';
        $str .= '<td>'. $data['acf_field'][$i].'</td>';
        $str .= '</tr>';                             
    }

    $str .= '</tbody>';
    $str .= '</table>';

    echo $str; 

    die();
}

add_action('wp_ajax_acf_run_export', 'run_acf_export');
add_action('wp_ajax_nopriv_acf_run_export', 'run_acf_export');
?>


