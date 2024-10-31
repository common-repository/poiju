<?php
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Icon is a class that provides some static methods for handling icons
 */
class Icon {
    /**
     * Get choices for icon
     *
     * @return array
     */
    public static function get_choices() {
        $choices = [
            'airplane' => __('Airplane', 'poiju'),
            'art' => __('Art', 'poiju'),
            'bed' => __('Bed', 'poiju'),
            'car' => __('Car', 'poiju'),
            'castle' => __('Castle', 'poiju'),
            'coffee' => __('Coffee', 'poiju'),
            'drink' => __('Drink', 'poiju'),
            'food' => __('Food', 'poiju'),
            'fuel' => __('Fuel', 'poiju'),
            'home' => __('Home', 'poiju'),
            'info' => __('Info', 'poiju'),
            'marker' => __('Marker', 'poiju'),
            'music' => __('Music', 'poiju'),
            'shopping' => __('Shopping', 'poiju'),
            'train' => __('Train', 'poiju'),
        ];

        return $choices;
    }

    /**
     * Get icon URL based on slug
     *
     * @param string $slug
     *
     * @return string|null
     */
    public static function get_url($slug) {
        if (!$slug) {
            return null;
        }
        return PLUGIN_URL . "assets/images/{$slug}.png";
    }

    /**
     * Get HiPDI icon URL based on slug
     *
     * @param string $slug
     *
     * @return string|null
     */
    public static function get_hidpi_url($slug) {
        if (!$slug) {
            return null;
        }
        return PLUGIN_URL . "assets/images/{$slug}-2x.png";
    }

    /**
     * Get the slug for the default icon
     *
     * @return string
     */
    public static function get_default_icon() {
        return 'marker';
    }

    /**
     * Get the URL for the default icon
     *
     * @return string
     */
    public static function get_default_url() {
        return Icon::get_url(Icon::get_default_icon());
    }

    /**
     * Get the HiDPI URL for the default icon
     *
     * @return string
     */
    public static function get_default_hidpi_url() {
        return Icon::get_hidpi_url(Icon::get_default_icon());
    }
}
