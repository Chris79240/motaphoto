<?php

/**
 * Modèle pour l'affichage du pied de page.
 * Contient les balises de fermeture <body> et <html>.
 * @package HelloElementor
 */

 // Vérifie si le script est exécuté dans le contexte WordPress
if (!defined('ABSPATH')) {
    exit; // Quitte si le script est accédé directement.
}

// Vérifie si un emplacement spécifique pour le pied de page est défini et actif
if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
    // Vérifie si Elementor est chargé et si l'expérimentation d'en-têtes/pieds de page est active
    if (did_action('elementor/loaded') && hello_header_footer_experiment_active()) {
        get_template_part('template-parts/dynamic-footer'); // Charge le pied de page dynamique si disponible
    } else {
        get_template_part('template-parts/footer'); // Sinon, charge le pied de page standard
    }   
}


// Insère tous les scripts nécessaires à WordPress avant la fermeture de <body>
?>
<?php wp_footer(); ?>
</body>

</html>