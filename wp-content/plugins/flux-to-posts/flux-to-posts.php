<?php
/*
Plugin Name: Flux to posts
Plugin URI: https://github.com/natinho68
Description: Create posts automatically from XML flux
Version: 0.1
Author: Nathan MEYER
Author URI: https://github.com/natinho68
License: MIT
*/


// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

// Include the dependencies needed to instantiate the plugin.
foreach (glob(plugin_dir_path(__FILE__) . 'admin/*.php') as $file) {
    include_once $file;
}

// Include the shared dependency.
include_once(plugin_dir_path(__FILE__) . 'shared/class-deserializer.php');
include_once(plugin_dir_path(__FILE__) . 'public/class-flux-parser.php');
include_once(ABSPATH . 'wp-admin/includes/post.php');

add_action('plugins_loaded', 'custom_admin_settings');

/**
 * Starts the plugin.
 *
 */
function custom_admin_settings()
{
    $serializer = new Serializer();
    $serializer->init();

    $deserializer = new Deserializer();

    $plugin = new Submenu(new Submenu_Page($deserializer));
    $plugin->init();

    $public = new Flux_Parser($serializer, $deserializer);
    $public->postFromFlux();
}
