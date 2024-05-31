<?php

/**
 * Enregistre les styles et les scripts pour le thème.
 */
function theme_enqueue_styles_and_scripts()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/sass/style.css', ['parent-style']);
    wp_enqueue_script('theme-script', get_stylesheet_directory_uri() . '/js/index.js', ['jquery'], null, true);
    wp_localize_script('theme-script', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts');

/**
 * Enregistre les taxonomies pour les photos.
 */
function create_photo_taxonomies()
{
    // Taxonomie 'categories'
    $labels_categories = array(
        'name'              => _x('Catégories', 'taxonomy general name'),
        'singular_name'     => _x('Catégorie', 'taxonomy singular name'),
        'search_items'      => __('Rechercher des catégories'),
        'all_items'         => __('Toutes les catégories'),
        'parent_item'       => __('Catégorie parente'),
        'parent_item_colon' => __('Catégorie parente:'),
        'edit_item'         => __('Modifier la catégorie'),
        'update_item'       => __('Mettre à jour la catégorie'),
        'add_new_item'      => __('Ajouter une nouvelle catégorie'),
        'new_item_name'     => __('Nom de la nouvelle catégorie'),
        'menu_name'         => __('Catégories'),
    );

    $args_categories = array(
        'hierarchical'      => true,
        'labels'            => $labels_categories,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'categories'),
    );

    register_taxonomy('categories', array('photo'), $args_categories);

    // Taxonomie 'format'
    $labels_format = array(
        'name'              => _x('Formats', 'taxonomy general name'),
        'singular_name'     => _x('Format', 'taxonomy singular name'),
        'search_items'      => __('Rechercher des formats'),
        'all_items'         => __('Tous les formats'),
        'parent_item'       => __('Format parent'),
        'parent_item_colon' => __('Format parent:'),
        'edit_item'         => __('Modifier le format'),
        'update_item'       => __('Mettre à jour le format'),
        'add_new_item'      => __('Ajouter un nouveau format'),
        'new_item_name'     => __('Nom du nouveau format'),
        'menu_name'         => __('Formats'),
    );

    $args_format = array(
        'hierarchical'      => true,
        'labels'            => $labels_format,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'format'),
    );

    register_taxonomy('format', array('photo'), $args_format);
}
add_action('init', 'create_photo_taxonomies');

/**
 * Gère les requêtes AJAX pour filtrer les photos selon les catégories et formats.
 */
function filter_photos_ajax()
{
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $format = isset($_POST['format']) ? $_POST['format'] : '';
    $sort = isset($_POST['sort']) ? $_POST['sort'] : 'date';
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $page,
        'tax_query' => [
            'relation' => 'AND'
        ],
        'orderby' => $sort ?: 'date',
        'order' => 'ASC'
    ];

    if ($category) {
        $args['tax_query'][] = [
            'taxonomy' => 'categories',
            'field' => 'term_id',
            'terms' => $category,
        ];
    }

    if ($format) {
        $args['tax_query'][] = [
            'taxonomy' => 'format',
            'field' => 'term_id',
            'terms' => $format,
        ];
    }

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
/*function load_more_photos_ajax()
{
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
*/