<?php
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * This class holds static methods related to the shortcode for displaying
 * points of interest.
 */
class Shortcode {
    const SHORTCODE = 'poiju_pois';
    const PAGINATION_QUERY_VAR = 'poi_paged';
    const POIS_PER_PAGE = 10;

    static $pois = [];

    /**
     * Get points of interest, filtered to include only those with coordinates.
     *
     * Requires the register() method and the init hook to be run first, to
     * populate self::$pois.
     *
     * @return Poi[]
     */
    private static function get_pois_with_coordinates() {
        /**
         * array_filter() preserves keys, which means we may end up with
         * something considered an associative array after filtering (not
         * starting with the key 0). Run array_values() on the result to avoid
         * this.
         */
        return array_values(array_filter(self::$pois, function ($poi) {
            if ($poi->get_latitude() !== null && $poi->get_longitude() !== null) {
                return true;
            }
            return false;
        }));
    }

    /**
     * Setup shortcode
     */
    static function register() {
        add_filter('query_vars', __NAMESPACE__ . '\Shortcode::add_query_vars');
        add_action('wp_enqueue_scripts', __NAMESPACE__ . '\Shortcode::load_assets');
        add_shortcode(self::SHORTCODE, __NAMESPACE__ . '\Shortcode::display');

        // Populate $pois
        add_action('init', function () {
            $poi_args = [
                'post_type' => PoiPostType::POST_TYPE,
                'nopaging' => true,
                'order' => 'ASC'
            ];
            $query = new \WP_Query($poi_args);
            Shortcode::$pois = array_map(function ($post) {
                return PoiPostType::poi_from_post_id($post->ID);
            }, $query->posts);
        });
    }

    /**
     * Add query vars.
     *
     * This function is meant to be registered as a callback for the "query_vars"
     * filter.
     */
    static function add_query_vars($vars) {
        $vars[] = self::PAGINATION_QUERY_VAR;
        return $vars;
    }

    /**
     * Load scripts and styles.
     *
     * This function is meant to be registered as a callback for the
     * "wp_enqueue_scripts" action hook.
     */
    static function load_assets() {
        wp_enqueue_script('poiju-mapbox-js', 'https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js', [], false, true);
        wp_enqueue_script('poiju-map-js', PLUGIN_URL . 'assets/js/map.js', ['poiju-mapbox-js'], false, true);

        /**
         * Convert to GeoJSON form. Save page along with POI, so map can link to
         * the correct page.
         */
        $pois_with_page = [];
        foreach (self::$pois as $index => $poi) {
            if ($poi->get_latitude() !== null && $poi->get_longitude() !== null) {
                $pois_with_page[] = [
                    'page' => ceil(($index + 1) / self::POIS_PER_PAGE),
                    'poi' => $poi,
                ];
            }
        }

        $features = array_map(function ($poi_with_page) {
            $poi = $poi_with_page['poi'];
            $page = $poi_with_page['page'];
            return [
                'type' => 'Feature',
                'properties' => [
                    'name' => $poi->get_name(),
                    'slug' => $poi->get_slug(),
                    'description' => $poi->get_description(),
                    'icon' => $poi->get_icon() ? : Icon::get_default_icon(),
                    'page' => $page,
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $poi->get_longitude(),
                        $poi->get_latitude(),
                    ],
                ],
            ];
        }, $pois_with_page);

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        /**
         * This will turn numbers into strings (see
         * https://core.trac.wordpress.org/ticket/25280). Use parseInt() and
         * parseFloat() on the JavaScript side to convert back.
         */
        wp_localize_script('poiju-map-js', 'poijuMapData', [
            'accessToken' => esc_js(get_option(SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING)),
            'geojson' => $geojson,
            'pluginURL' => PLUGIN_URL,
            'showControls' => get_option(SettingsPage::MAP_CONTROLS_SETTING) === '1',
            'showLabels' => get_option(SettingsPage::MAP_LABELS_SETTING) === '1',
            'paginationQueryVar' => self::PAGINATION_QUERY_VAR,
            'currentPage' => get_query_var(self::PAGINATION_QUERY_VAR, 1),
        ]);

        wp_enqueue_style('poiju-mapbox-css', 'https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css');
        wp_enqueue_style('poiju-map-css', PLUGIN_URL . 'assets/css/map.css', ['poiju-mapbox-css']);
        wp_enqueue_style('poiju-shortcode-css', PLUGIN_URL . 'assets/css/shortcode.css');

        /**
         * Load Dashicons on front-end (in addition to admin) as they are used
         * in the POI list.
         */
        wp_enqueue_style('dashicons');
    }

    /**
     * Display the shortcode.
     *
     * @param array|string $atts Attributes supplied by the user in the shortcode,
     *     empty string if no attributes supplied
     *
     * @return string HTML for showing points of interest
     */
    static function display($atts) {
        $atts = shortcode_atts([
            'image_size' => 'thumbnail',
            'map' => 'true'
        ], $atts);

        $query = new \WP_Query([
            'post_type' => PoiPostType::POST_TYPE,
            'posts_per_page' => self::POIS_PER_PAGE,
            'paged' => get_query_var(self::PAGINATION_QUERY_VAR, 1),
            'order' => 'ASC'
        ]);
        $pois = array_map(function ($post) {
            return PoiPostType::poi_from_post_id($post->ID);
        }, $query->posts);

        // Turn on output buffering; shortcode output should be returned
        ob_start();
        // Show map by default, turn off using map="false" option
        if (count(self::get_pois_with_coordinates()) > 0
            && $atts['map'] !== 'false'
            && get_option(SettingsPage::MAPBOX_ACCESS_TOKEN_SETTING) !== false) {
            require PLUGIN_PATH . 'templates/shortcode/map.php';
        }

        foreach ($pois as $poi) {
            // Let users override template
            $override = locate_template('poiju/templates/shortcode/poi.php');
            if ($override !== '') {
                require $override;
            } else {
                require PLUGIN_PATH . 'templates/shortcode/poi.php';
            }
        }

        print(paginate_links([
            'total' => $query->max_num_pages,
            'current' => get_query_var(self::PAGINATION_QUERY_VAR, 1),
            'format' => '?' . self::PAGINATION_QUERY_VAR . '=%#%'
        ]));

        return ob_get_clean();
    }
}