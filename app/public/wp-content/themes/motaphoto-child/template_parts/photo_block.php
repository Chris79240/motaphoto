<?php
$photo_id = $args['id'];

if (has_post_thumbnail($photo_id)) {
    $photo_url = get_permalink($photo_id); // Obtient l'URL de la page de la photo
    echo '<div class="photo-block">';
    echo '<a href="' . esc_url($photo_url) . '">'; // Début du lien
    echo '<img src="' . esc_url(get_the_post_thumbnail_url($photo_id)) . '" alt="' . esc_attr(get_the_title($photo_id)) . '">';
    echo '</a>'; // Fin du lien
    echo '<h3>' . get_the_title($photo_id) . '</h3>';
    echo '</div>';
} else {
    echo "<p>Image non disponible.</p>";
}
