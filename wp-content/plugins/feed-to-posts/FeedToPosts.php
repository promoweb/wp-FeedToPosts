<?php
/*
Plugin Name: Feed to posts
Description: Create posts automatically from JSON feed
Version: 1.0
Author: Nathan MEYER
Author URI: https://github.com/natinho68
License: GNU GENERAL PUBLIC LICENSE v2
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
include_once(ABSPATH . 'wp-admin/includes/post.php');

add_action('plugins_loaded', 'FeedToPosts_Settings');

/**
 * Starts the plugin.
 *
 */
function FeedToPosts_Settings()
{
    $serializer = new FeedToPosts_Serializer();
    $serializer->FeedToPosts_init();

    $deserializer = new FeedToPosts_Deserializer();

    $plugin = new FeedToPosts_Menu(new FeedToPosts_Page($deserializer));
    $plugin->FeedToPosts_init();

    $notices = new FeedToPosts_Notices();
    $notices->FeedToPosts_init();

    $public = new FeedToPosts_Controller($deserializer, $notices);
    $public->FeedToPosts_Feed();
}
