<?php
/**
 * Template for displaying a point of interest in a shortcode on the front-end
 */
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}
?>

<article class="poiju-poi">
    <?php if ($poi->get_name() !== ''): ?>
    <h1 id="<?= $poi->get_slug() ?>" class="poiju-poi__name">
        <img class="poiju-poi__icon"
             src="<?= Icon::get_url($poi->get_icon() ? : Icon::get_default_icon()) ?>"
             srcset="<?= Icon::get_hidpi_url($poi->get_icon() ? : Icon::get_default_icon()) ?> 2x">
        <?= $poi->get_name() ?>
    </h1>
    <?php endif ?>

    <?php if ($poi->get_image_id() !== null): ?>
    <div class="poiju-poi__image">
        <a href="<?= wp_get_attachment_url($poi->get_image_id()) ?>">
            <?= wp_get_attachment_image($poi->get_image_id(), $atts['image_size']) ?>
        </a>
    </div>
    <?php endif ?>

    <?php if ($poi->get_description() !== ''): ?>
    <div class="poiju-poi__content"><?= $poi->get_description() ?></div>
    <?php endif ?>

    <?php if ($poi->get_address()): ?>
    <h2 class="poiju-poi__label"><?= __('Address', 'poiju') ?></h2>
    <p class="poiju-poi__address"><?= nl2br(esc_html($poi->get_address())) ?></p>
    <?php endif ?>

    <?php if ($poi->get_opening_hours()): ?>
    <h2 class="poiju-poi__label"><?= __('Opening hours', 'poiju') ?></h2>    
    <p class="poiju-poi__opening-hours"><?= nl2br(esc_html($poi->get_opening_hours())) ?></p>
    <?php endif ?>

    <?php if ($poi->get_contact_info()): ?>
    <h2 class="poiju-poi__label"><?= __('Contact information', 'poiju') ?></h2>    
    <p class="poiju-poi__contact-info"><?= nl2br(esc_html($poi->get_contact_info())) ?></p>
    <?php endif ?>

    <?php if (count($poi->get_links()) > 0): ?>
    <nav class="poiju-poi__links">
        <ul>
            <?php if (value_or_default($poi->get_links(), 'www', '') !== ''): ?>
            <li>
                <a href="<?= esc_url($poi->get_links()['www']) ?>"
                    class="dashicons-before dashicons-admin-links">www</a>
            </li>
            <?php endif ?>

            <?php if (value_or_default($poi->get_links(), 'facebook', '') !== ''): ?>
            <li>
                <a href="<?= esc_url($poi->get_links()['facebook']) ?>"
                    class="dashicons-before dashicons-facebook">Facebook</a>
            </li>
            <?php endif ?>

            <?php if (value_or_default($poi->get_links(), 'instagram', '') !== ''): ?>
            <li>
                <a href="<?= esc_url($poi->get_links()['instagram']) ?>"
                    class="dashicons-before dashicons-camera">Instagram</a>
            </li>
            <?php endif ?>

            <?php if (value_or_default($poi->get_links(), 'twitter', '') !== ''): ?>
            <li>
                <a href="<?= esc_url($poi->get_links()['twitter']) ?>"
                    class="dashicons-before dashicons-twitter">Twitter</a>
            </li>
            <?php endif ?>
        </ul>
    </nav>
    <?php endif ?>
</article>
