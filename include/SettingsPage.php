<?php
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Functionality related to the plugin settings page
 */
class SettingsPage {
    /** @var string The slug of the settings page */
    const MENU_SLUG = 'poiju-settings';
    /** @var string Capability required to access the settings page */
    const CAPABILITY = 'manage_options';
    /** @var string The slug for the general settings section */
    const GENERAL_SETTINGS_SECTION = 'poiju-general-settings';
    /**
     * @var string The option group used for the Poiju settings
     *
     * The purpose of the option group is not quite clear, but it is used in a
     * few places.
     */
    const OPTION_GROUP = 'poiju-option-group';
    /** @var string The slug for the Mapbox access token setting */
    const MAPBOX_ACCESS_TOKEN_SETTING = 'poiju-mapbox-access-token';
    /** @var string The slug for the map controls setting */
    const MAP_CONTROLS_SETTING = 'poiju-map-controls';
    /** @var string The slug for the map labels setting */
    const MAP_LABELS_SETTING = 'poiju-map-labels';

    /**
     * Add a page for plugin settings in the admin
     *
     * This is meant to be registered as a callback for the "admin_menu" action
     * hook.
     */
    public static function add_page() {
        add_submenu_page(
            'options-general.php',
            __('Poiju settings', 'poiju'),
            'Poiju',
            SettingsPage::CAPABILITY,
            SettingsPage::MENU_SLUG,
            __NAMESPACE__ . '\SettingsPage::show_page'
        );
    }

    /**
     * Callback for displaying the settings page
     */
    public static function show_page() {
        if (!current_user_can(SettingsPage::CAPABILITY)) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'poiju'));
        } ?>
<div class="wrap">
    <h1>Poiju</h1>
    
    <form action="options.php" method="POST">
        <?php
        settings_fields(SettingsPage::OPTION_GROUP);
        do_settings_sections(SettingsPage::MENU_SLUG);
        submit_button(); ?>
    </form>
</div>
        <?php
    }

    /**
     * Register settings and add settings sections and fields
     *
     * This is meant to be registered as a callback for the "admin_init" action
     * hook.
     */
    public static function add_settings() {
        register_setting(SettingsPage::OPTION_GROUP, SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING);
        register_setting(SettingsPage::OPTION_GROUP, SettingsPage::MAP_CONTROLS_SETTING);
        register_setting(SettingsPage::OPTION_GROUP, SettingsPage::MAP_LABELS_SETTING);

        add_settings_section(
            SettingsPage::GENERAL_SETTINGS_SECTION,
            __('General settings', 'poiju'),
            null,
            SettingsPage::MENU_SLUG
        );

        add_settings_field(
            SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING,
            __('Mapbox access token', 'poiju'),
            __NAMESPACE__ . '\SettingsPage::show_mapbox_token_field',
            SettingsPage::MENU_SLUG,
            SettingsPage::GENERAL_SETTINGS_SECTION
        );

        add_settings_field(
            SettingsPage::MAP_CONTROLS_SETTING,
            __('Map controls', 'poiju'),
            __NAMESPACE__ . '\SettingsPage::show_map_controls_field',
            SettingsPage::MENU_SLUG,
            SettingsPage::GENERAL_SETTINGS_SECTION
        );

        add_settings_field(
            SettingsPage::MAP_LABELS_SETTING,
            __('Map labels', 'poiju'),
            __NAMESPACE__ . '\SettingsPage::show_map_labels_field',
            SettingsPage::MENU_SLUG,
            SettingsPage::GENERAL_SETTINGS_SECTION
        );
    }

    public static function show_mapbox_token_field() {
        ?>
<input class="widefat"
    id="<?= SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING ?>"
    name="<?= SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING ?>"
    value="<?= esc_attr(get_option(SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING) ? : '') ?>">
<p class="description"><?= sprintf(__('To use the map, you need an account at <a href="%s">Mapbox</a> and a token. An account can be created for free. See <a href="%s">here</a> for more information.', 'poiju'), 'https://www.mapbox.com/', 'https://www.mapbox.com/help/define-access-token/') ?></p>
        <?php
    }

    public static function show_map_controls_field() {
        ?>
<input type="checkbox"
    id="<?= SettingsPage::MAP_CONTROLS_SETTING ?>"
    name="<?= SettingsPage::MAP_CONTROLS_SETTING ?>"
    value="1"<?php if (get_option(SettingsPage::MAP_CONTROLS_SETTING)) echo ' checked' ?>>
<label for="<?= SettingsPage::MAP_CONTROLS_SETTING ?>"><?= __('Show map controls', 'poiju') ?></label>
        <?php
    }

    public static function show_map_labels_field() {
        ?>
<input type="checkbox"
    id="<?= SettingsPage::MAP_LABELS_SETTING ?>"
    name="<?= SettingsPage::MAP_LABELS_SETTING ?>"
    value="1"<?php if (get_option(SettingsPage::MAP_LABELS_SETTING)) echo ' checked' ?>>
<label for="<?= SettingsPage::MAP_LABELS_SETTING ?>"><?= __('Show point names on map', 'poiju') ?></label>
        <?php
    }
}
