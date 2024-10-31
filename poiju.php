<?php
/*
 * Plugin Name: Poiju
 * Description: A plugin for listing points of interest and information about them using a shortcode.
 * Version: 0.5
 * Author: Kristian Lumme
 * Author URI: https://klart.fi/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: poiju
 */
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * The path to the main directory for the plugin
 *
 * Includes trailing slash.
 */
define(__NAMESPACE__ . '\PLUGIN_PATH', plugin_dir_path(__FILE__));
/**
 * The URL for the main plugin directory
 *
 * Includes trailing slash.
 */
define(__NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url(__FILE__));

require_once 'include/functions.php';
require_once 'include/Icon.php';
require_once 'include/Poi.php';
require_once 'include/PoiPostType.php';
require_once 'include/SettingsPage.php';
require_once 'include/shortcode.php';

/**
 * Load text domain
 */
add_action('plugins_loaded', function () {
    load_plugin_textdomain('poiju', false, basename(__DIR__) . '/languages');
});

/**
 * Register custom post type for points of interest
 */
add_action('init', __NAMESPACE__ . '\PoiPostType::register');

/**
 * Add hook for saving custom post type metadata
 */
add_action('save_post_' . PoiPostType::POST_TYPE, __NAMESPACE__ . '\PoiPostType::save_post');

// Load custom post type admin assets
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\PoiPostType::load_assets');

/**
 * Add shortcode for displaying points of interest
 */
Shortcode::register();

/**
 * Add settings page in admin
 */
add_action('admin_menu', __NAMESPACE__ . '\SettingsPage::add_page');

/**
 * Add settings
 */
add_action('admin_init', __NAMESPACE__ . '\SettingsPage::add_settings');