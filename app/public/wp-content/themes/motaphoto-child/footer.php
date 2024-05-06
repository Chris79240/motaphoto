<footer id="site-footer" class="site-footer">
    <hr class="footer-divider" />
    <nav class="footer-navigation">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'menu-footer',
            'menu_id'        => 'footer-menu',
            'fallback_cb'    => false,
        ));
        ?>
    </nav>

    <!-- Inclusion de la modale de contact -->
    <?php get_template_part('template_parts/modale'); ?>

    <?php wp_footer(); ?>
</footer>

<div class="containerLightbox" style="display: none;">
    <div class="lightbox">
        <div class="lightbox-content">
            <span class="lightboxClose">&times;</span>
            <img class="lightboxImage" src="" alt="Image agrandie">

            <div class="lightbox-controls">
                <button class="lightboxPrevious">Précédente</button>
                <span class="lightboxTitle">Nom de la photo</span>
                <button class="lightboxNext">Suivante</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>