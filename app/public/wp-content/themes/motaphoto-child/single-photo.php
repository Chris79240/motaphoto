<?php

/**
 * The template for displaying all single posts of type 'photo'
 * @package HelloElementor
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();
        // Variables pour les champs personnalisés ACF
        $reference = get_field('reference');
        $type = get_field('type');
        $annee = get_field('annee');
        $categories = wp_get_post_terms(get_the_ID(), 'categories');
        $format = wp_get_post_terms(get_the_ID(), 'format');


?>
        <main id="content" <?php post_class('site-main'); ?>>
            <div class="left-side">
                <header class="page-header">
                    <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
                    <p>Référence: <?php echo esc_html($reference); ?></p>
                    <p>Catégories:
                        <?php
                        if (!empty($categories) && !is_wp_error($categories)) {
                            foreach ($categories as $category) {
                                echo esc_html($category->name) . ' ';
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </p>
                    <p>Format:
                        <?php
                        if (!empty($format) && !is_wp_error($format)) {
                            foreach ($format as $formats) {
                                echo esc_html($formats->name) . ' ';
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </p>
                    <p>Type: <?php echo esc_html($type); ?></p>
                    <p>Année: <?php echo esc_html($annee); ?></p>
                </header>

                <hr class="header-divider" />

                <div class="button-photo">
                    <p>Cette photo vous intéresse-t-elle ?</p>

                    <!-- Bouton pour ouvrir la modalité -->
                    <button class="button open-contact-modal">Contact</button>
                </div>
            </div>

            <div class="right-side">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="photo-main">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>




                <div class="photo-navigation">

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="photo-thumbnail">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </div>
                    <?php endif; ?>
                    <?php
                    $prev_post = get_previous_post();
                    if (!empty($prev_post)) : ?>
                        <button class="arrow-button" onclick="location.href='<?php echo get_permalink($prev_post->ID); ?>'">&#x2190;</button>
                    <?php endif; ?>
                    <?php echo get_next_post_link('%link', '→'); ?>


                </div>
            </div>
        </main>

<?php endwhile;
endif; ?>

<hr class="divider" />

<h3 id="aussi">VOUS AIMEREZ AUSSI</h3>

<div class="related-photos-container">
    <?php
    $photo_args = array(
        'post_type' => 'photo',
        'posts_per_page' => 2, // Adjust the number of photos as needed
        'orderby' => 'rand', // Randomize photos
        'post__not_in' => array(get_the_ID()) // Exclude current post
    );

    $photo_query = new WP_Query($photo_args);

    if ($photo_query->have_posts()) {
        while ($photo_query->have_posts()) {
            $photo_query->the_post();
            get_template_part('template_parts/photo_block', null, ['id' => get_the_ID()]);
        }
    } else {
        echo "<p>Aucune photo supplémentaire à afficher.</p>";
    }
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>