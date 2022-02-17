<?php

/*
Plugin Name: ACF Find Replace
Plugin URI: https://eos.com/
Description: Глобально изменить ACF поле
Version: 1.0
Author: EOS DA
Author URI: https://eos.com/
*/
require_once(plugin_dir_path(__FILE__) . 'inc/import-check.php');
require_once(plugin_dir_path(__FILE__) . 'inc/import-run.php');
require_once(plugin_dir_path(__FILE__) . 'inc/export-run.php');

function acf_find_replace_assets() {
    wp_register_style('acf-find-replace-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_register_script('acf-find-replace-scripts',  plugin_dir_url(__FILE__) . 'assets/js/main.js');

    wp_enqueue_style('acf-find-replace-styles');
    wp_enqueue_script('acf-find-replace-scripts');

    wp_register_style('uikit-style', 'https://cdn.jsdelivr.net/npm/uikit@3.6.18/dist/css/uikit.min.css');
    wp_register_script('uikit-scripts', 'https://cdn.jsdelivr.net/npm/uikit@3.6.18/dist/js/uikit.min.js');
    wp_register_script('uikit-icon-scripts', 'https://cdn.jsdelivr.net/npm/uikit@3.6.18/dist/js/uikit-icons.min.js');

    wp_enqueue_style('uikit-style');
    wp_enqueue_script('uikit-scripts');
    wp_enqueue_script('uikit-icon-scripts');
}

function acf_find_replace_include_settings_page(){
    include( plugin_dir_path(__FILE__) . 'acf-find-replace-setting-page.php' );
}

function acf_find_replace_add_nav(){

    $page_title  = "Find&Replace";
    $menu_title  = "Find&Replace";
    $capability  = "administrator";
    $menu_slug   = "acf-find-replace";
    $icon_url    = "dashicons-forms";
    $function    = "acf_find_replace_include_settings_page";

    $acf_find_replace_page = add_menu_page( $page_title, $menu_title, $capability, $menu_slug,  $function, $icon_url, $position = null );
    add_action( "admin_print_scripts-$acf_find_replace_page", 'acf_find_replace_assets' );
}

add_action( 'admin_menu', 'acf_find_replace_add_nav' );


