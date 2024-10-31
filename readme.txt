=== Poiju ===
Contributors: klumme
Tags: travel, map, maps, location, locations, point of interest, points of interest, marker, markers, mapbox
Requires at least: 4.7
Tested up to: 5.0
Stable tag: trunk
Requires PHP: 5.4
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Poiju displays locations and info about them on a map and in a list on your website.

== Description ==

Poiju a plugin for displaying points of interest on your website. The points can be displayed on a map (powered by [Mapbox](https://www.mapbox.com/)) as well as in a list.

*This plugin is in beta*. It's very bare-bones at this point. If you find bugs or if you wish for specific features, I appreciate it if you share them in the support forum for this plugin.

Poiju uses a custom post type to manage your points of interest. When the plugin is active, you can find a "Points of interest"-heading in the menu to the left in your dashboard. Here, points of interest can be managed similarly to WordPress posts.

To show points of interest on your site, use the following [shortcode](https://codex.wordpress.org/shortcode), wherever shortcodes are allowed:

    [poiju_pois]

You can explicitly disable the map as follows:

    [poiju_pois map="false"]

To use the Mapbox map for points of interest, you must create an account on the [Mapbox site](https://www.mapbox.com/) and get an [API access token](https://www.mapbox.com/help/how-access-tokens-work/). Also, you must specify a latitude and a longitude for at least one of your points of interest.

To make the featured image display in a "lightbox" when clicked, it should be enough to install and activate the [WP Featherlight plugin](https://wordpress.org/plugins/wp-featherlight/). Others probably work too.

== Customization ==

You can override the template used for showing a point of interest in the shortcode. The original is located in the `poiju` plugin directory, in `templates/shortcode/poi.php`. By creating a `poiju` folder in your theme, containing the same directory structure and file, you can provide your own version of this template. For example, if your theme is Twenty Seventeen, the path would be `twentyseventeen/poiju/templates/shortcode/poi.php`.

Look at the original template to see how to display data from the point of interest.

== Screenshots ==

1. Map and list of points of interest
2. Zoomed-in map showing points of interests
3. Point of interest editor

== Changelog ==

= 0.5 =

* Add choice of icons to points (shown on map and in list)
* Add pagination for list of POIs
* Various tweaks and fixes

= 0.4 =
* Add setting for showing point names on map
* Add point clustering
* Add setting for showing map controls
* Lower minimum required PHP and WordPress versions
* Various tweaks and fixes

= 0.3 =
* Show points of interest as markers on map, not as labels.
* Fix some labels in the admin.

= 0.2 =
* Abbreviate point names on map until hover/focus.
* Fix bug with initial map bounds.
* Tweak info, add screenshot.

= 0.1 =
* Initial release
