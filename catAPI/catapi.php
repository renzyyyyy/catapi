<?php

/**
 * Plugin Name: CAT API
 * Plugin URI: https://docs.thecatapi.com/
 * Description: custom CAT plugin by Renzy for IONA
 * Version: 1.0
 * Author: Renzy
 * Author URI: https://github.com/renzyyyyy/renzy-catproject
 **/

// error_reporting(E_ALL);
// ini_set('display_errors', 'true');
include(plugin_dir_path(__FILE__) . 'includes/Cats.class.php');

/**
 * Add New Page for Single Cat Page
 */
function catapi_add_cat_pages()
{
    $check_page_exists = get_page_by_title('Cat', 'OBJECT', 'page');
    if (empty($check_page_exists)) {
        $page_id = wp_insert_post(
            array(
                'comment_status' => 'close',
                'ping_status'    => 'close',
                'post_author'    => 1,
                'post_title'     => 'Cat',
                'post_name'      => 'Cat',
                'post_status'    => 'publish',
                'post_content'   => '',
                'post_type'      => 'page',
            )
        );
    }
}
register_activation_hook(__FILE__, 'cpi_add_cat_catapi_add_cat_pagespages');

/**
 * Change template being used for Homepage & Single Cat Pages
 * @param page_template template file
 */
function catapi_change_homepage_template($page_template)
{
    if (is_home()) {
        $page_template = plugin_dir_path(__FILE__) . 'templates/homepage.php';
    }

    if (is_page('cat')) {
        $page_template = plugin_dir_path(__FILE__) . 'templates/page-cat.php';
    }
    return $page_template;
}
add_action('template_include', 'catapi_change_homepage_template');

/**
 * Enable url parameters
 * @param vars 
 */
function catapi_custom_query_vars($vars)
{
    $vars[] = 'breed';
    $vars[] = 'page';
    $vars[] = 'id';

    return $vars;
}
add_filter('query_vars', 'catapi_custom_query_vars');

/**
 * Enqueue scripts and css
 */
function catapi_add_scripts()
{
    if (is_page('cat')) {
        wp_enqueue_style('catapi-css', plugins_url('/assets/css/cat.css', __FILE__));
    }
    if (is_home()) {
        wp_enqueue_style('catapi-css', plugins_url('/assets/css/homepage.css', __FILE__));
        wp_enqueue_script('catapi-js', plugins_url('/assets/js/homepage.js', __FILE__), array('jquery'), '1.0', true);
        wp_localize_script(
            'catapi-js',
            'catapi',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'cat_url' => site_url('cat'),
                'error_msg' => 'Apologies but we could not load new cats for you at this time! Miau!'
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'catapi_add_scripts');

/**
 * Breeds dropdown from API
 */
function catapi_breeds()
{
    $catAPI = new Cats();
    list($header, $breeds) = $catAPI->get_breeds();
    if (!empty($breeds)) {
        echo '<select id = "breeds" class="breeds-dropdown">';
        echo '<option>SELECT BREED</option>';
        foreach ($breeds as $breed) {
            echo '<option value="' . $breed['id'] . '" ' . ($breed['id'] == get_query_var('breed') ? 'selected' : '') . '>' . $breed['name'] . '</option>';
        }
        echo '</select>';
    }
}

/**
 * Return cat objects from API
 */
function catapi_get_cats()
{
    $breed = $_GET['breed'];
    $page = $_GET['page'];
    if (!empty($breed)) {
        $catAPI = new Cats();
        return $catAPI->get_cats($breed, $page);
    }
}

/**
 * For AJAX calls from Homepage
 */
function catapi_get_cats_wrapper()
{
    header('Content-type: application/json');
    $result = catapi_get_cats();
    echo json_encode($result);
    exit;
}

// register the ajax action for authenticated users
add_action('wp_ajax_catapi_get_cats_wrapper', 'catapi_get_cats_wrapper');
// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_catapi_get_cats_wrapper', 'catapi_get_cats_wrapper');
