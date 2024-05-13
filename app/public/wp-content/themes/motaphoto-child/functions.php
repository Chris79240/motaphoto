<?php

/**
 * Enregistre les styles et les scripts pour le thème.
 */
function theme_enqueue_styles_and_scripts()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/css/theme.css', ['parent-style']);
    wp_enqueue_script('theme-script', get_stylesheet_directory_uri() . '/js/index.js', ['jquery'], null, true);
    wp_localize_script('theme-script', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts');

/**
 * Gère les requêtes AJAX pour filtrer les photos selon les catégories et formats.
 */
function filter_photos_ajax()
{
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    $format = filter_input(INPUT_POST, 'format', FILTER_SANITIZE_NUMBER_INT);
    $sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_STRING);
    $args = [
        'post_type' => 'photo',
        'posts_per_page' => -1,
        'tax_query' => [
            ['taxonomy' => 'photo_categories', 'field' => 'term_id', 'terms' => $category],
            ['taxonomy' => 'photo_formats', 'field' => 'term_id', 'terms' => $format]
        ],
        'orderby' => $sort ?: 'date',
        'order' => 'ASC'
    ];
    $query = new WP_Query($args);
    $content = '';
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template_parts/photo_block', null, ['id' => get_the_ID()]);
        }
        $content = ob_get_clean();
    } else {
        $content = '<p>Aucune photo trouvée.</p>';
    }
    wp_send_json_success(['content' => $content]);
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');

/**
 * Gère la pagination infinie AJAX pour les photos.
 */

function load_more_photos_ajax()
{
    // S'assurer que le numéro de page est correctement obtenu et incrémenté
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $page,
    ];

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template_parts/photo_block', null, ['id' => get_the_ID()]);
        }
        $content = ob_get_clean();
        wp_send_json_success(['page' => $page + 1, 'content' => $content, 'max_page' => $query->max_num_pages]);
    } else {
        wp_send_json_error(['message' => 'No more photos']);
    }
}
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos_ajax');
add_action('wp_ajax_load_more_photos', 'load_more_photos_ajax');
