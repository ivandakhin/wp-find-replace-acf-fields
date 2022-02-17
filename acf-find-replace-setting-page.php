<?php

require_once(plugin_dir_path(__FILE__) . 'function.php');

function acf_find_replace_render_admin_page(){
    $custom_posts_names = array();
    $custom_posts_labels = array();
    $user_ids = array();
    $user_names = array();

    $args = array(
        'public' => true,
        '_builtin' => false
    );

    $output = 'objects';
    $operator = 'and';
    $post_types = get_post_types($args, $output, $operator);
    foreach ($post_types as $post_type) {
        $custom_posts_names[] = $post_type->name;
        $custom_posts_labels[] = $post_type->labels->singular_name;
    }

    $users = get_users();
    foreach ($users as $user) {
        $user_ids[] = $user->data->ID;
        $user_names[] = $user->data->user_login;
    }

    $file_path = wp_upload_dir();
    $file_name = 'all-urls-' . rand(111111, 999999);
    $file_replace_name = 'replace-acf-' . rand(111111, 999999);    
?>

    <main class="acf_find_replace uk-margin-medium-top">
        <div class="uk-container uk-container-xlarge">
            <h1 class="uk-heading-xsmall">Find<span style="color: #ee395b;">&</span>Replace</h1>

            <ul class="uk-subnav uk-subnav-pill" uk-switcher="animation: uk-animation-fade">
                <li><a href="#">Import</a></li>
                <li><a href="#">Export</a></li>
                <li><a href="#">Find&Replace</a></li>
            </ul>

            <ul class="uk-switcher uk-margin">
                <li>
                    <div>
                        <form id="import-form" class="uk-grid-small" uk-grid>
                            <div class="uk-margin" uk-margin>
                                <div uk-form-custom="target: true">
                                    <input name="import-url" class="uk-input uk-form-width-large" type="url" placeholder="Insert link to Google Docs">
                                </div>
                                <button 
                                    type="button" 
                                    id="load-import"
                                    data-ajax-url="<?= site_url() ?>/wp-admin/admin-ajax.php"  
                                    class="uk-button uk-button-danger">
                                    <span>Load</span>
                                </button>
                            </div>
                        </form>
                        <div id="import-output"></div>
                    </div>
                </li>
                    <div>
                        <form id="export-form" class="uk-form-stacked uk-margin">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="export-key">ACF Field:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input uk-form-width-large" id="export-key" type="text" name="" placeholder="Insert key, name or pattern">
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-form-label">Select a Post Type to Extract Data:</div>
                                <div class="uk-form-controls">
                                    <label><input checked class="uk-radio" type="radio" name="post-type" value="any" required="required"> All Types</label><br>
                                    <label><input class="uk-radio" type="radio" name="post-type" value="page" required="required"> Pages</label><br>
                                    <label><input class="uk-radio" type="radio" name="post-type" value="post" required="required"> Posts</label><br>
                                    <?php
                                            if (!empty($custom_posts_names) && !empty($custom_posts_labels)) {
                                                for ($i = 0; $i < count($custom_posts_names); $i++) {
                                                    echo '<label><input class="uk-radio" type="radio" name="post-type" value="' . $custom_posts_names[$i] . '" required="required" /> ' . $custom_posts_labels[$i] . ' Posts</label><br>';
                                                }
                                            }
                                        ?>
                                </div>
                            </div>
                        </form>
                        <button id="load-export" class="uk-button uk-button-danger" data-ajax-url="<?= site_url() ?>/wp-admin/admin-ajax.php"  >
                            <span>Load</span>
                        </button>
                        <hr class="uk-divider-icon">
                        <div id="export-output" class="uk-margin"></div>    
                    </div>
                </li>
                <li>
                    <div>
                        <form class="uk-grid-small" uk-grid>
                            <div class="uk-margin" uk-margin>
                                <div uk-form-custom="target: true">
                                    <input class="uk-input uk-form-width-large" type="url" placeholder="Insert link to Google Docs">
                                </div>
                                <button id="load-docs" class="uk-button uk-button-danger">load</button>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </main>
<?php 
}
acf_find_replace_render_admin_page(); 
