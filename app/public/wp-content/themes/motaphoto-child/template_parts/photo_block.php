<?php
// Utilise $args['id'] passé via get_template_part pour obtenir des données spécifiques de la photo
$photo_id = $args['id'];
?>

<div class="photo-block">
    <img src="<?php echo get_the_post_thumbnail_url($photo_id); ?>" alt="<?php echo esc_attr(get_the_title($photo_id)); ?>">
    <h3><?php echo get_the_title($photo_id); ?></h3>
    <!-- Autres détails de la photo -->
</div>