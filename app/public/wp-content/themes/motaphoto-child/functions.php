<?php
// Enregistrement des styles et des scripts
function theme_enqueue_styles_and_scripts()
{
    // Enqueue les styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css'); // Style du thème parent
    wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/css/theme.css', array('parent-style')); // Style du thème enfant

    // Enqueue les scripts
    wp_enqueue_script('child-theme-script', get_stylesheet_directory_uri() . '/index.js', array('jquery'), null, true); // Ton script custom

    // Localize script pour passer les données du serveur vers le script JS
    wp_localize_script('child-theme-script', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'), // URL pour le traitement AJAX côté serveur
        'some_other_data' => 'value' // Vous pouvez passer ici d'autres données au besoin
    ));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts');

// Enregistrement des menus
function register_my_menus()
{
    register_nav_menus(array(
        'menu-header' => __('Menu principal', 'textdomain'),
        'menu-footer' => __('Menu footer', 'textdomain')
    ));
}
add_action('init', 'register_my_menus');

// Fonction pour traiter les requêtes AJAX pour le filtrage des photos
function filter_photos_ajax()
{
    // Assurez-vous de sécuriser l'accès à cette fonction avec des contrôles nonces ou des permissions si nécessaire

    // Traitement des filtres
    $category = isset($_POST['category']) ? intval($_POST['category']) : '';
    $format = isset($_POST['format']) ? intval($_POST['format']) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'date';

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => -1,
        'tax_query' => []
    ];

    if (!empty($category)) {
        $args['tax_query'][] = [
            'taxonomy' => 'categories',
            'field' => 'term_id',
            'terms' => $_POST['category']
        ];
    }

    if (!empty($format)) {
        $args['tax_query'][] = [
            'taxonomy' => 'format',
            'field' => 'term_id',
            'terms' => $_POST['format']
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

    wp_reset_postdata();
    echo json_encode(array('content' => $content));
    wp_die(); // arrête correctement l'exécution du script
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');


// Enregistrement des taxonomies personnalisées pour le type de post 'photo'
add_action('init', function () {
    register_taxonomy('categories', array('photo'), array(
        'labels' => array(
            'name' => 'Catégories',
            'singular_name' => 'Catégorie',
            // Autres labels ici...
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('hierarchical' => true),
    ));

    register_taxonomy('format', array('photo', 'page'), array(
        'labels' => array(
            'name' => 'Formats',
            'singular_name' => 'Format',
            // Autres labels ici...
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('hierarchical' => true),
    ));
});
