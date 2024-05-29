<?php
$photo_id = $args['id'];
$categories = wp_get_post_terms($photo_id, 'categories');
$category_names = wp_list_pluck($categories, 'name');
$reference = get_field('reference', $photo_id); // Assuming 'reference' is a custom field

if (has_post_thumbnail($photo_id)) {
    $photo_url = get_permalink($photo_id); // Obtient l'URL de la page de la photo
    echo '<div class="photo-block">';
    echo '<a href="' . esc_url($photo_url) . '">'; // Début du lien
    echo '<img src="' . esc_url(get_the_post_thumbnail_url($photo_id)) . '" alt="' . esc_attr(get_the_title($photo_id)) . '" data-category="' . esc_attr(implode(', ', $category_names)) . '" data-reference="' . esc_attr($reference) . '">';
    echo '<div class="photo-icon"></div>';
    echo '<div class="photo-overlay">';
    echo '<span class="fullscreen-icon" data-fullscreen="true"></span>';
    echo '<span class="photo-reference">' . esc_html($reference) . '</span>';
    echo '<span class="photo-category">' . esc_html(implode(', ', $category_names)) . '</span>';
    echo '</div>';
    echo '</a>'; // Fin du lien
    echo '</div>';
} else {
    echo "<p>Image non disponible.</p>";
}
