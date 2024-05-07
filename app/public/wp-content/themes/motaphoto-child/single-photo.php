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
        $terms = get_queried_object();
        $categories = get_field('categories', $terms)->name;
        $format = get_field('format', $terms)->name;


?>
        <main id="content" <?php post_class('site-main'); ?>>
            <div class="left-side">
                <header class="page-header">
                    <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
                    <p>Référence: <?php echo esc_html($reference); ?></p>
                    <p>Catégories: <?php echo esc_html($categories); ?></p>
                    <p>Format: <?php echo esc_html($format); ?></p>
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
                    <button class="arrow-button" onclick="location.href='<?php echo get_next_post_link(); ?>'">&#x2192;</button>
                </div>
            </div>
        </main>

<?php endwhile;
endif; ?>

<hr class="divider" />

<h3>VOUS AIMEREZ AUSSI</h3>

<div class="left-side">
    <div id="vousaimerez">
        <?php
        $photo_args = array(
            'post_type' => 'photo',
            'posts_per_page' => 2
        );

        $photo_query = new WP_Query($photo_args);

        if ($photo_query->have_posts()) {
            while ($photo_query->have_posts()) {
                $photo_query->the_post();
                get_template_part('template_parts/photo_block', null, ['id' => get_the_ID()]);
            }
        } else {
            echo "<p>Aaaaaaaaaaaaa.</p>";
        }
        wp_reset_postdata();
        ?>
    </div>

    <div class="right-side">

        <div id="vousaimerez">
            <?php
            $photo_args = array(
                'post_type' => 'photo',
                'posts_per_page' => 1
            );
            $photo_query = new WP_Query($photo_args);
            if ($photo_query->have_posts()) {
                while ($photo_query->have_posts()) {
                    $photo_query->the_post();
                    get_template_part('template_parts/photo_block', null, ['id' => get_the_ID()]);
                }
            } else {
                echo "<p>Aaaaaaaaaaaaa.</p>";
            }
            wp_reset_postdata();
            ?>
        </div>



        <?php get_footer(); ?>