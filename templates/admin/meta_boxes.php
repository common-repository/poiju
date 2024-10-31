<?php
/**
 * Template for displaying meta boxes for custom post type in the dashboard
 */
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}
?>

<label for="<?= PoiPostType::ADDRESS_META_KEY ?>">
    <?= __('Address:', 'poiju') ?>
</label>
<textarea class="large-text"
          id="<?= PoiPostType::ADDRESS_META_KEY ?>"
          name="<?= PoiPostType::ADDRESS_META_KEY ?>"><?= esc_textarea($poi->get_address()) ?></textarea>

<label for="<?= PoiPostType::OPENING_HOURS_META_KEY ?>">
    <?= __('Opening hours:', 'poiju') ?>
</label>
<textarea class="large-text"
          id="<?= PoiPostType::OPENING_HOURS_META_KEY ?>"
          name="<?= PoiPostType::OPENING_HOURS_META_KEY ?>"><?= esc_textarea($poi->get_opening_hours()) ?></textarea>

<label for="<?= PoiPostType::CONTACT_INFO_META_KEY ?>">
    <?= __('Contact information:', 'poiju') ?>
</label>
<textarea class="large-text"
          id="<?= PoiPostType::CONTACT_INFO_META_KEY ?>"
          name="<?= PoiPostType::CONTACT_INFO_META_KEY ?>"><?= esc_textarea($poi->get_contact_info()) ?></textarea>

<h3><?= __('Links', 'poiju') ?></h3>

<label for="<?= PoiPostType::LINKS_META_KEY ?>[www]">
    <?= __('www link:', 'poiju') ?>
</label>
<input class="widefat"
       id="<?= PoiPostType::LINKS_META_KEY ?>[www]"
       name="<?= PoiPostType::LINKS_META_KEY ?>[www]"
       type="url"
       value="<?= esc_attr(value_or_default($poi->get_links(), 'www', '')) ?>">

<label for="<?= PoiPostType::LINKS_META_KEY ?>[facebook]">
    <?= __('Facebook link:', 'poiju') ?>
</label>
<input class="widefat"
       id="<?= PoiPostType::LINKS_META_KEY ?>[facebook]"
       name="<?= PoiPostType::LINKS_META_KEY ?>[facebook]"
       type="url"       
       value="<?= esc_attr(value_or_default($poi->get_links(), 'facebook', '')) ?>">

<label for="<?= PoiPostType::LINKS_META_KEY ?>[instagram]">
    <?= __('Instagram link:', 'poiju') ?>
</label>
<input class="widefat"
       id="<?= PoiPostType::LINKS_META_KEY ?>[instagram]"
       name="<?= PoiPostType::LINKS_META_KEY ?>[instagram]"
       type="url"       
       value="<?= esc_attr(value_or_default($poi->get_links(), 'instagram', '')) ?>">

<label for="<?= PoiPostType::LINKS_META_KEY ?>[twitter]">
    <?= __('Twitter link:', 'poiju') ?>
</label>
<input class="widefat"
       id="<?= PoiPostType::LINKS_META_KEY ?>[twitter]"
       name="<?= PoiPostType::LINKS_META_KEY ?>[twitter]"
       type="url"       
       value="<?= esc_attr(value_or_default($poi->get_links(), 'twitter', '')) ?>">
    
<h3><?= __('Position', 'poiju') ?></h3>

<label for="<?= PoiPostType::ICON_META_KEY ?>">
    <?= __('Icon to show on map', 'poiju') ?>
</label><br>
<select class="poiju-icon-select"
        id="<?= PoiPostType::ICON_META_KEY ?>"
        name="<?= PoiPostType::ICON_META_KEY ?>">
    <?php foreach (Icon::get_choices() as $value => $name): ?>
    <option value="<?= esc_attr($value) ?>"
            <?= ($poi->get_icon() === $value || !$poi->get_icon() && $value === Icon::get_default_icon()) ? ' selected' : '' ?>>
        <?= $name ?>
    </option>
    <?php endforeach ?>
</select>
<?php if ($poi->get_icon()): ?>
<img class="poiju-icon-preview"
     src="<?= Icon::get_url($poi->get_icon()) ?>"
     srcset="<?= Icon::get_hidpi_url($poi->get_icon()) ?> 2x">
<?php else: ?>
<img class="poiju-icon-preview"
     src="<?= Icon::get_default_url() ?>"
     srcset="<?= Icon::get_default_hidpi_url() ?> 2x">
<?php endif ?>
<br>

<label for="<?= PoiPostType::LATITUDE_META_KEY ?>">
    <?= __('Latitude (in decimal form, e.g. "60.4518"):', 'poiju') ?>
</label>
<input class="widefat"
       id="<?= PoiPostType::LATITUDE_META_KEY ?>"
       name="<?= PoiPostType::LATITUDE_META_KEY ?>"
       value="<?= esc_attr($poi->get_latitude()) ?>">

<label for="<?= PoiPostType::LONGITUDE_META_KEY ?>">
    <?= __('Longitude (in decimal form, e.g. "22.2666"):', 'poiju') ?>
</label>
<input class="widefat"
       id="<?= PoiPostType::LONGITUDE_META_KEY ?>"
       name="<?= PoiPostType::LONGITUDE_META_KEY ?>"
       value="<?= esc_attr($poi->get_longitude()) ?>">
