<?php
// Enregistrement des styles et des scripts
function theme_enqueue_styles_and_scripts()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/css/theme.css', array('parent-style'));
    wp_enqueue_script('child-theme-script', get_stylesheet_directory_uri() . '/js/index.js', array('jquery'), null, true);

    // Localiser le script pour passer 'ajaxurl'
    wp_localize_script('child-theme-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts');

// Gestion AJAX pour le filtrage et la pagination des photos
function filter_photos_ajax()
{
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    $format = filter_input(INPUT_POST, 'format', FILTER_SANITIZE_NUMBER_INT);
    $sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_STRING);

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => -1,
        'tax_query' => [],
        'orderby' => $sort ? $sort : 'date',
        'order' => 'ASC'
    ];

    if (!empty($category)) {
        $args['tax_query'][] = [
            'taxonomy' => 'categories',
            'field' => 'term_id',
            'terms' => $category
        ];
    }
    if (!empty($format)) {
        $args['tax_query'][] = [
            'taxonomy' => 'format',
            'field' => 'term_id',
            'terms' => $format
        ];
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/photo_block', null, array('id' => get_the_ID()));
        }
        $content = ob_get_clean();
    } else {
        $content = '<p>Aucune photo trouvée.</p>';
    }

    echo json_encode(array('content' => $content));
    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');



function load_more_photos_ajax()
{
    // Gestion de la pagination AJAX
}
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos_ajax');
add_action('wp_ajax_load_more_photos', 'load_more_photos_ajax');
