<?php
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Poi is a model class for a point of interest
 */
class Poi {
    /**
     * @var int The ID of the WordPress post providing the backend
     *     for this point of interest
     */
    private $post_id;
    /** @var string The name of the point of interest */
    private $name;
    /** @var string Slug */
    private $slug;
    /** @var string Additional information  */
    private $description;
    /** @var string Address or location */
    private $address;
    /** @var string Opening hours */
    private $opening_hours;
    /** @var string Contact information */
    private $contact_info;
    /** @var string[] Various links */
    private $links;
    /** @var int ID for the featured image */
    private $image_id;
    /** @var float Latitude in decimal degrees */
    private $latitude;
    /** @var float Longitude in decimal degrees */
    private $longitude;
    /** @var string Icon slug */
    private $icon;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $slug
     * @param string $description
     * @param string $address
     * @param string $opening_hours
     * @param string $contact_info
     * @param string[] $links
     * @param int $image_id
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($post_id, $name, $slug, $description, $address, $opening_hours, $contact_info, $links, $image_id, $latitude, $longitude, $icon) {
        $this->post_id = $post_id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->address = $address;
        $this->opening_hours = $opening_hours;
        $this->contact_info = $contact_info;
        $this->links = $links;
        $this->image_id = $image_id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->icon = $icon;
    }

    /**
     * Get the post ID of the underlying WordPress post
     *
     * @return int
     */
    public function get_post_id() {
        return $this->post_id;
    }

    /**
     * Get the name of the point of interest
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get the slug
     *
     * @return string
     */
    public function get_slug() {
        return $this->slug;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get the address or location
     *
     * @return string
     */
    public function get_address() {
        return $this->address;
    }

    /**
     * Set the address or location
     *
     * @param string $address
     */
    public function set_address($address) {
        $this->address = $address;
    }

    /**
     * Get the opening hours
     *
     * @return string
     */
    public function get_opening_hours() {
        return $this->opening_hours;
    }

    /**
     * Set the opening hours
     *
     * @param string $opening_hours
     */
    public function set_opening_hours($opening_hours) {
        $this->opening_hours = $opening_hours;
    }

    /**
     * Get contact information
     *
     * @return string
     */
    public function get_contact_info() {
        return $this->contact_info;
    }

    /**
     * Set contact information
     *
     * @param string $contact_info
     */
    public function set_contact_info($contact_info) {
        $this->contact_info = $contact_info;
    }

    /**
     * Get the links
     *
     * @return string[]
     */
    public function get_links() {
        return $this->links;
    }

    /**
     * Set the links
     *
     * @param string[] $links
     */
    public function set_links($links) {
        $this->links = $links;
    }

    /**
     * Get the featured image ID
     *
     * @return int|null
     */
    public function get_image_id() {
        return $this->image_id;
    }

    /**
     * Set the featured image ID
     *
     * @param int $image_id
     */
    public function set_image_id($image_id) {
        $this->image_id = $image_id;
    }

    /**
     * Get the latitude (in decimal degrees)
     *
     * @return float
     */
    public function get_latitude() {
        return $this->latitude;
    }

    /**
     * Set the latitude (in decimal degrees)
     *
     * @param float $latitude
     */
    public function set_latitude($latitude) {
        $this->latitude = $latitude;
    }

    /**
     * Get the longitude (in decimal degrees)
     *
     * @return float
     */
    public function get_longitude() {
        return $this->longitude;
    }

    /**
     * Set the longitude (in decimal degrees)
     *
     * @param float $longitude
     */
    public function set_longitude($longitude) {
        $this->longitude = $longitude;
    }

    /**
     * Get the icon slug
     *
     * @return string
     */
    public function get_icon() {
        return $this->icon;
    }

    /**
     * Set the icon slug
     *
     * @param string $icon
     */
    public function set_icon($icon) {
        $this->icon = $icon;
    }
}
