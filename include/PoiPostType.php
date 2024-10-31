<?php
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * PoiPostType handles the custom post type for a point of interest
 */
class PoiPostType {
    /** @var string The slug for the custom post type */
    const POST_TYPE = 'poiju-poi';
    /* Metadata keys for properties of the Poi class */
    const ADDRESS_META_KEY = 'poiju-poi-address';
    const OPENING_HOURS_META_KEY = 'poiju-poi-opening-hours';
    const CONTACT_INFO_META_KEY = 'poiju-poi-contact-info';
    const LINKS_META_KEY = 'poiju-poi-links';
    const LATITUDE_META_KEY = 'poiju-poi-latitude';
    const LONGITUDE_META_KEY = 'poiju-poi-longitude';
    const ICON_META_KEY = 'poiju-poi-icon';

    /**
     * Register the point of interest custom post type
     *
     * This must be done before the custom post type is used.
     */
    public static function register() {
        $post_type_args = [
            'label' => __('Points of Interest', 'poiju'),
            'labels' => [
                'singular_name' => __('Point of Interest', 'poiju'),
                'add_new_item' => __('Add New Point of Interest', 'poiju'),
                'edit_item' => __('Edit Point of Interest', 'poiju')
            ],
            'show_ui' => true,
            'menu_icon' => 'dashicons-location',
            'supports' => ['title', 'editor', 'thumbnail'],
            'register_meta_box_cb' => __NAMESPACE__ . '\PoiPostType::add_meta_boxes',
        ];

        register_post_type(PoiPostType::POST_TYPE, $post_type_args);
    }

    /**
     * Load custom post type editor scripts and styles
     */
    public static function load_assets($page) {
        if (in_array($page, ['post.php', 'post-new.php'])) {
            wp_enqueue_script(
                'poiju-editor-js',
                PLUGIN_URL . 'assets/js/editor.js',
                [],
                false,
                true
            );

            // Send icon URLs to script
            $icons = array_keys(Icon::get_choices());
            $icon_urls = [];
            foreach ($icons as $icon) {
                $icon_urls[$icon] = [
                    'url' => Icon::get_url($icon),
                    'urlHidpi' => Icon::get_hidpi_url($icon),
                ];
            }
            wp_localize_script(
                'poiju-editor-js',
                'poijuEditorData',
                [
                    'iconUrls' => $icon_urls,
                ]
            );

            wp_enqueue_style('poiju-editor-css', PLUGIN_URL . 'assets/css/editor.css');
        }
    }

    /**
     * Callback passed to register_post_type() to add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'poiju-poi-metadata',
            __('Information', 'poiju'),
            __NAMESPACE__ . '\PoiPostType::meta_boxes',
            PoiPostType::POST_TYPE
        );
    }

    /**
     * Callback to display meta boxes
     *
     * @param WP_Post $post The WP_Post instance being edited
     */
    public static function meta_boxes($post) {
        $poi = PoiPostType::poi_from_post_id($post->ID);
        require PLUGIN_PATH . 'templates/admin/meta_boxes.php';
    }

    /**
     * Callback run when a point of interest-post is saved
     *
     * @param int $post_id The ID of the post being saved
     */
    public static function save_post($post_id) {
        $poi = PoiPostType::poi_from_post_id($post_id);

        if (array_key_exists(PoiPostType::ADDRESS_META_KEY, $_POST) && $_POST[PoiPostType::ADDRESS_META_KEY] !== '') {
            $poi->set_address($_POST[PoiPostType::ADDRESS_META_KEY]);
        } else {
            $poi->set_address(null);
        }

        if (array_key_exists(PoiPostType::OPENING_HOURS_META_KEY, $_POST) && $_POST[PoiPostType::OPENING_HOURS_META_KEY] !== '') {
            $poi->set_opening_hours($_POST[PoiPostType::OPENING_HOURS_META_KEY]);
        } else {
            $poi->set_opening_hours(null);
        }

        if (array_key_exists(PoiPostType::CONTACT_INFO_META_KEY, $_POST) && $_POST[PoiPostType::CONTACT_INFO_META_KEY] !== '') {
            $poi->set_contact_info($_POST[PoiPostType::CONTACT_INFO_META_KEY]);
        } else {
            $poi->set_contact_info(null);
        }

        $links = [];
        if (array_key_exists(PoiPostType::LINKS_META_KEY, $_POST)) {
            $links_input = $_POST[PoiPostType::LINKS_META_KEY];
            if ($links_input['www'] !== '') {
                $links['www'] = $links_input['www'];
            }
            if ($links_input['facebook'] !== '') {
                $links['facebook'] = $links_input['facebook'];
            }
            if ($links_input['instagram'] !== '') {
                $links['instagram'] = $links_input['instagram'];
            }
            if ($links_input['twitter'] !== '') {
                $links['twitter'] = $links_input['twitter'];
            }
        }
        $poi->set_links($links);

        if (array_key_exists(PoiPostType::LATITUDE_META_KEY, $_POST) && $_POST[PoiPostType::LATITUDE_META_KEY] !== '') {
            $poi->set_latitude((float) $_POST[PoiPostType::LATITUDE_META_KEY]);
        } else {
            $poi->set_latitude(null);
        }

        if (array_key_exists(PoiPostType::LONGITUDE_META_KEY, $_POST) && $_POST[PoiPostType::LONGITUDE_META_KEY] !== '') {
            $poi->set_longitude((float) $_POST[PoiPostType::LONGITUDE_META_KEY]);
        } else {
            $poi->set_longitude(null);
        }

        if (array_key_exists(PoiPostType::ICON_META_KEY, $_POST) && $_POST[PoiPostType::ICON_META_KEY] !== '' && array_key_exists($_POST[PoiPostType::ICON_META_KEY], Icon::get_choices())) {
            $poi->set_icon($_POST[PoiPostType::ICON_META_KEY]);
        } else {
            $poi->set_icon(null);
        }

        PoiPostType::save_metadata($poi);
    }

    /**
     * Save point of interest metadata to the underlying WordPress post
     *
     * @param Poi $poi
     */
    public static function save_metadata($poi) {
        // Update metadata
        update_post_meta($poi->get_post_id(), PoiPostType::ADDRESS_META_KEY, $poi->get_address());
        update_post_meta($poi->get_post_id(), PoiPostType::OPENING_HOURS_META_KEY, $poi->get_opening_hours());
        update_post_meta($poi->get_post_id(), PoiPostType::CONTACT_INFO_META_KEY, $poi->get_contact_info());
        update_post_meta($poi->get_post_id(), PoiPostType::LINKS_META_KEY, $poi->get_links());
        update_post_meta($poi->get_post_id(), PoiPostType::LATITUDE_META_KEY, $poi->get_latitude());
        update_post_meta($poi->get_post_id(), PoiPostType::LONGITUDE_META_KEY, $poi->get_longitude());
        update_post_meta($poi->get_post_id(), PoiPostType::ICON_META_KEY, $poi->get_icon());
    }

    /**
     * Get a Poi instance from a WordPress post ID
     *
     * @param int $post_id A WordPress post ID for a post of the point of
     *     interest custom type
     *
     * @return Poi
     */
    public static function poi_from_post_id($post_id) {
        $post = get_post($post_id);

        if ($post === null || $post->post_type !== PoiPostType::POST_TYPE) {
            return null;
        }

        // Load properties stored as metadata
        $address = get_post_meta($post_id, PoiPostType::ADDRESS_META_KEY, true);
        $opening_hours = get_post_meta($post_id, PoiPostType::OPENING_HOURS_META_KEY, true);
        $contact_info = get_post_meta($post_id, PoiPostType::CONTACT_INFO_META_KEY, true);
        $links = get_post_meta($post_id, PoiPostType::LINKS_META_KEY, true);
        if ($links === '') {
            $links = [];
        }
        $image_id = get_post_thumbnail_id($post_id);
        if ($image_id === '' || $image_id === false) {
            $image_id = null;
        }
        $latitude = get_post_meta($post_id, PoiPostType::LATITUDE_META_KEY, true);
        if ($latitude === '') {
            $latitude = null;
        }
        $longitude = get_post_meta($post_id, PoiPostType::LONGITUDE_META_KEY, true);
        if ($longitude === '') {
            $longitude = null;
        }
        $icon = get_post_meta($post_id, PoiPostType::ICON_META_KEY, true);

        $poi = new Poi(
            $post_id,
            apply_filters('the_title', $post->post_title),
            $post->post_name,
            apply_filters('the_content', $post->post_content),
            $address,
            $opening_hours,
            $contact_info,
            $links,
            $image_id,
            $latitude,
            $longitude,
            $icon
        );

        return $poi;
    }
}
