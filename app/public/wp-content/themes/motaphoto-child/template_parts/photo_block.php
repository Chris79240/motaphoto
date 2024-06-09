<?php
$photo_id = $args['id']; // Récupère l'id
$categories = wp_get_post_terms($photo_id, 'categories'); // Récupère les termes de la taxonomie 'categories' associés à la photo.
$category_names = wp_list_pluck($categories, 'name'); // Extrait les noms des catégories dans un tableau.
$reference = get_field('reference', $photo_id); // Récupère le champ personnalisé 'reference' de la photo.

// Vérifie si la photo a une image à la une (thumbnail).
if (has_post_thumbnail($photo_id)) {
    $photo_url = get_permalink($photo_id); // Obtient l'URL de la page de la photo
    echo '<div class="photo-block">'; // Début du conteneur de la photo.
    echo '<a href="' . esc_url($photo_url) . '">'; // Début du lien vers la page de la photo.
    echo '<img src="' . esc_url(get_the_post_thumbnail_url($photo_id)) . '" alt="' . esc_attr(get_the_title($photo_id)) . '" data-category="' . esc_attr(implode(', ', $category_names)) . '" data-reference="' . esc_attr($reference) . '">';
    echo '<div class="photo-icon"></div>';
    echo '<div class="photo-overlay">'; // Début de la superposition d'informations sur la photo.
    echo '<span class="fullscreen-icon" data-fullscreen="true"></span>';
    echo '<span class="photo-reference">' . esc_html($reference) . '</span>';
    echo '<span class="photo-category">' . esc_html(implode(', ', $category_names)) . '</span>';
    echo '</div>'; // Fin de la superposition d'informations sur la photo.
    echo '</a>'; // Fin du lien
    echo '</div>'; // Fin du conteneur de la photo.
} else {
    // Affiche un message si l'image de la photo n'est pas disponible.
    echo "<p>Image non disponible.</p>";
}
