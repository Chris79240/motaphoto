<?php

/**
 * Enregistre les styles et les scripts pour le thème
 */
function theme_enqueue_styles_and_scripts()
{
    // Enqueue le style parent
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue le style enfant, dépendant du style parent
    wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/css/theme.css', array('parent-style'));

    // Enqueue le script pour les interactions spécifiques de la modale de contact
    wp_enqueue_script('theme-contact-modal', get_stylesheet_directory_uri() . '/js/index.js', array('jquery'), null, true);

    // Localise le script pour fournir des données supplémentaires comme 'ajaxurl'
    wp_localize_script('theme-contact-modal', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts');

/**
 * Gère les requêtes AJAX pour filtrer les photos selon les catégories et formats
 */
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
        $args['tax_query'][] = ['taxonomy' => 'categories', 'field' => 'term_id', 'terms' => $category];
    }
    if (!empty($format)) {
        $args['tax_query'][] = ['taxonomy' => 'format', 'field' => 'term_id', 'terms' => $format];
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/photo_block', null, ['id' => get_the_ID()]);
        }
        $content = ob_get_clean();
    } else {
        $content = '<p>Aucune photo trouvée.</p>';
    }

    echo json_encode(['content' => $content]);
    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');

/**
 * Gère la pagination infinie AJAX pour les photos
 */
function load_more_photos_ajax()
{
    $page = $_POST['page'] ?? 1; // Utilisation de NULL coalesce pour définir une valeur par défaut

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
    } else {
        $content = '<p>Aucune photo supplémentaire à afficher.</p>';
    }

    echo json_encode(['page' => $page + 1, 'content' => $content, 'max_page' => $query->max_num_pages]);
    wp_die();
}
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos_ajax');
add_action('wp_ajax_load_more_photos', 'load_more_photos_ajax');

/**
 * Insère une image de contact par shortcode
 */
function insert_contact_image()
{
    $image_path = get_stylesheet_directory_uri() . '/assets/images/contactcontact.png';
    return '<img src="' . esc_url($image_path) . '" alt="Contact">';
}
add_shortcode('contact_image', 'insert_contact_image');

/**
 * Enregistre les scripts spécifiques à la lightbox
 */
function my_custom_lightbox_scripts()
{
    wp_enqueue_script('custom-lightbox-script', get_stylesheet_directory_uri() . '/js/lightbox.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'my_custom_lightbox_scripts');

/**
 * Enregistre les taxonomies personnalisées pour les photos
 */
function register_photo_taxonomies()
{
    register_taxonomy('photo_categories', 'photo', ['label' => 'Catégories de Photos', 'rewrite' => ['slug' => 'photo-categories'], 'hierarchical' => true]);
    register_taxonomy('photo_formats', 'photo', ['label' => 'Formats de Photos', 'rewrite' => ['slug' => 'photo-formats'], 'hierarchical' => true]);
}
add_action('init', 'register_photo_taxonomies');

/**
 * Fournit les termes de taxonomies via l'API REST
 */
function get_photo_taxonomy_terms()
{
    $taxonomy = sanitize_text_field($_GET['taxonomy']);
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    return new WP_REST_Response($terms, 200);
}

add_action('rest_api_init', function () {
    register_rest_route('mytheme/v1', '/terms/', [
        'methods' => 'GET',
        'callback' => 'get_photo_taxonomy_terms',
        'args' => [
            'taxonomy' => [
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                }
            ],
        ],
    ]);
});

function add_specific_menu_item_class($classes, $item, $args)
{
    if (stripos($item->title, "CONTACT") !== false) { // Changez "CONTACT" par le texte exact du lien dans votre menu
        $classes[] = 'contact-link';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_specific_menu_item_class', 10, 3);
