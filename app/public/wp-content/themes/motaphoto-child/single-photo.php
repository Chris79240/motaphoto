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

                <!-- Modalité de contact -->
                <div class="contact-modal" style="display:none;">
                    <div class="contact-modal-content">
                        <span class="close-modal">&times;</span>
                        <?php echo do_shortcode('[contact-form-7 id="d196106" title="Formulaire de contact"]'); ?>
                    </div>
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


        <script>
            jQuery(document).ready(function($) {
                // Ouverture de la modalité
                $('.open-contact-modal').click(function() {
                    $('.contact-modal').fadeIn();
                });

                // Fermeture de la modalité
                $('.close-modal').click(function() {
                    $('.contact-modal').fadeOut();
                });
            });
        </script>

<?php endwhile;
endif; ?>

<hr class="divider" />

<?php get_footer(); ?>