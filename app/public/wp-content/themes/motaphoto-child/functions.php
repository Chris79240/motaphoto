<?php

/**
 * Enregistre les styles et les scripts pour le thème.
 */
function theme_enqueue_styles_and_scripts()
{
    // Enqueue le style du thème parent
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    // Enqueue le style du thème enfant en dépendance du style parent
    wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/sass/style.css', ['parent-style']);
    // Enqueue le script principal du thème enfant
    wp_enqueue_script('theme-script', get_stylesheet_directory_uri() . '/js/index.js', ['jquery'], null, true);
    // Localiser le script avec l'URL AJAX pour l'utiliser dans le script JavaScript
    wp_localize_script('theme-script', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
}
// Action pour enqueuer les styles et les scripts lors du chargement des scripts du thème
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts');

/**
 * Enregistre les taxonomies pour les photos.
 */
function create_photo_taxonomies()
{
    // Taxonomie 'categories'
    $labels_categories = array(
        'name'              => _x('Catégories', 'taxonomy general name'), // Nom de la taxonomie au pluriel
        'singular_name'     => _x('Catégorie', 'taxonomy singular name'), // Nom de la taxonomie au singulier
        'search_items'      => __('Rechercher des catégories'), // Texte pour rechercher des catégories
        'all_items'         => __('Toutes les catégories'), // Texte pour toutes les catégories
        'parent_item'       => __('Catégorie parente'), // Texte pour la catégorie parente
        'parent_item_colon' => __('Catégorie parente:'), // Texte pour la catégorie parente avec deux-points
        'edit_item'         => __('Modifier la catégorie'), // Texte pour modifier une catégorie
        'update_item'       => __('Mettre à jour la catégorie'), // Texte pour mettre à jour une catégorie
        'add_new_item'      => __('Ajouter une nouvelle catégorie'), // Texte pour ajouter une nouvelle catégorie
        'new_item_name'     => __('Nom de la nouvelle catégorie'), // Texte pour le nom de la nouvelle catégorie
        'menu_name'         => __('Catégories'), // Texte pour le nom du menu de la taxonomie
    );

    $args_categories = array(
        'hierarchical'      => true, // Permet d'avoir des catégories imbriquées
        'labels'            => $labels_categories, // Étiquettes définies ci-dessus
        'show_ui'           => true, // Affiche l'interface utilisateur pour cette taxonomie
        'show_admin_column' => true, // Affiche cette taxonomie dans les colonnes d'administration
        'query_var'         => true, // Permet d'utiliser cette taxonomie dans les requêtes
        'rewrite'           => array('slug' => 'categories'), // URL slug pour cette taxonomie
    );

    // Enregistre la taxonomie 'categories' pour le type de publication 'photo'
    register_taxonomy('categories', array('photo'), $args_categories);

    // Taxonomie 'format'
    $labels_format = array(
        'name'              => _x('Formats', 'taxonomy general name'), // Nom de la taxonomie au pluriel
        'singular_name'     => _x('Format', 'taxonomy singular name'), // Nom de la taxonomie au singulier
        'search_items'      => __('Rechercher des formats'), // Texte pour rechercher des formats
        'all_items'         => __('Tous les formats'), // Texte pour tous les formats
        'parent_item'       => __('Format parent'), // Texte pour le format parent
        'parent_item_colon' => __('Format parent:'), // Texte pour le format parent avec deux-points
        'edit_item'         => __('Modifier le format'), // Texte pour modifier un format
        'update_item'       => __('Mettre à jour le format'), // Texte pour mettre à jour un format
        'add_new_item'      => __('Ajouter un nouveau format'), // Texte pour ajouter un nouveau format
        'new_item_name'     => __('Nom du nouveau format'), // Texte pour le nom du nouveau format
        'menu_name'         => __('Formats'), // Texte pour le nom du menu de la taxonomie
    );

    $args_format = array(
        'hierarchical'      => true, // Permet d'avoir des formats imbriqués
        'labels'            => $labels_format, // Étiquettes définies ci-dessus
        'show_ui'           => true, // Affiche l'interface utilisateur pour cette taxonomie
        'show_admin_column' => true, // Affiche cette taxonomie dans les colonnes d'administration
        'query_var'         => true, // Permet d'utiliser cette taxonomie dans les requêtes
        'rewrite'           => array('slug' => 'format'), // URL slug pour cette taxonomie
    );

    // Enregistre la taxonomie 'format' pour le type de publication 'photo'
    register_taxonomy('format', array('photo'), $args_format);
}
// Action pour enregistrer les taxonomies lors de l'initialisation de WordPress
add_action('init', 'create_photo_taxonomies');

/**
 * Gère les requêtes AJAX pour filtrer les photos selon les catégories et formats.
 */
function filter_photos_ajax()
{
    $category = isset($_POST['category']) ? $_POST['category'] : ''; // Obtenir la catégorie sélectionnée
    $format = isset($_POST['format']) ? $_POST['format'] : ''; // Obtenir le format sélectionné
    $sort = isset($_POST['sort']) ? $_POST['sort'] : 'date'; // Obtenir le tri sélectionné, par défaut par date
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1; // Obtenir la page actuelle, par défaut la page 1
    $args = [
        'post_type' => 'photo', // Type de publication 'photo'
        'posts_per_page' => 8, // Nombre de photos par page
        'paged' => $page, // Page actuelle
        'tax_query' => [
            'relation' => 'AND' // Relation entre les taxonomies
        ],
        'orderby' => $sort ?: 'date', // Trier par le critère sélectionné ou par date par défaut
        'order' => 'ASC' // Ordre de tri
    ];

    if ($category) {
        $args['tax_query'][] = [
            'taxonomy' => 'categories', // Taxonomie 'categories'
            'field' => 'term_id', // Filtrer par identifiant de terme
            'terms' => $category, // Catégorie sélectionnée
        ];
    }

    if ($format) {
        $args['tax_query'][] = [
            'taxonomy' => 'format', // Taxonomie 'format'
            'field' => 'term_id', // Filtrer par identifiant de terme
            'terms' => $format, // Format sélectionné
        ];
    }

    $query = new WP_Query($args); // Créer une nouvelle requête WP_Query avec les arguments définis
    $content = '';
    if ($query->have_posts()) {
        ob_start(); // Commencer la mise en mémoire tampon de sortie
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template_parts/photo_block', null, ['id' => get_the_ID()]); // Charger le template pour chaque photo
        }
        $content = ob_get_clean(); // Récupérer le contenu mis en mémoire tampon

    } else {

        $content = '<p>Aucune photo trouvée.</p>'; // Afficher un message si aucune photo n'est trouvée
    }
    wp_send_json_success(['content' => $content]); // Envoyer une réponse JSON avec le contenu
}

// Actions pour gérer les requêtes AJAX, pour les utilisateurs connectés et non connectés
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');
