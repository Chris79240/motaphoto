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
    <?php get_template_part('template_parts/modal-contact'); ?>

    <!-- Modale de contact -->
    <div class="contact-modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <?php echo do_shortcode('[contact-form-7 id="d196106" title="Formulaire de contact"]'); ?>
        </div>
    </div>

    <?php wp_footer(); ?>
</footer>
</body>

</html>