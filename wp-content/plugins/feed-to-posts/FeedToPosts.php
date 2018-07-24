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
    $cronJob = new FeedToPosts_Automation();
    $cronJob->FeedToPosts_automationInit();

    $controller = new FeedToPosts_Controller();
    $controller->FeedToPosts_controllerInit();

    $plugin = new FeedToPosts_Menu(new FeedToPosts_Page());
    $plugin->FeedToPosts_menuInit();

    $notices = new FeedToPosts_Notices();
    $notices->FeedToPosts_noticesInit();
}
