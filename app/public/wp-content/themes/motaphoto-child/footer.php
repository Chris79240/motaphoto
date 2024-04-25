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
    <?php wp_footer(); ?>
</footer>
</body>

</html>