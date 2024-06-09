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
    <div id="lightbox" class="lightbox">
        <div class="lightbox-content">
            <span class="lightboxClose">&times;</span>
            <div class="lightboxPhoto">
                <img class="lightboxImage" src="" alt="Image de la lightbox">
            </div>
            <div class="lightbox-details">
                <span class="lightboxReference"></span>
                <span class="lightboxCategorie"></span>
            </div>
            <span class="lightboxPrevious"></span>
            <span class="lightboxNext"></span>
        </div>
    </div>
</div>

</body>

</html>