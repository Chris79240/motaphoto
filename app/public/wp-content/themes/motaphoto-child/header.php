<?php
// Modèle d'en-tête : ouvre la section <head>, la balise <body> et ajoute l'en-tête du site.
if (!defined('ABSPATH')) {
    exit; // Empêche l'accès direct au script.
}
$viewport_content = apply_filters('hello_elementor_viewport_content', 'width=device-width, initial-scale=1');
$enable_skip_link = apply_filters('hello_elementor_enable_skip_link', true);
$skip_link_url = apply_filters('hello_elementor_skip_link_url', '#content');
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="<?php echo esc_attr($viewport_content); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php if ($enable_skip_link) { ?>
        <a class="skip-link screen-reader-text" href="<?php echo esc_url($skip_link_url); ?>"><?php echo esc_html__('Passer au contenu', 'hello-elementor'); ?></a>
    <?php } ?>
    <?php
    // Affiche l'en-tête dynamique ou l'en-tête standard avec le menu de navigation
    if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('header')) {
        if (did_action('elementor/loaded') && hello_header_footer_experiment_active()) {
            get_template_part('template-parts/dynamic-header');
        } else {
            get_template_part('template-parts/header');
            // Affiche le menu de navigation
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'navbar-nav mr-auto',
                'container'      => 'nav',
                'container_class' => 'main-navigation',
            ));
        }
    }
    ?>