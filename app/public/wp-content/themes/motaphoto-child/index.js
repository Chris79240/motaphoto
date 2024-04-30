jQuery(document).ready(function($) {
    // Écoute les changements dans les sélecteurs de catégorie, de format et de tri.
    $('#photo-category, #photo-format, #photo-sort').change(function() {
        updatePhotoGallery(); // Appelle cette fonction chaque fois qu'une option est modifiée.
    });

    // Fonction pour mettre à jour la galerie de photos en fonction des filtres sélectionnés.
    function updatePhotoGallery() {
        var category = $('#photo-category').val(); // Valeur du sélecteur de catégorie.
        var format = $('#photo-format').val(); // Valeur du sélecteur de format.
        var sort = $('#photo-sort').val(); // Valeur du sélecteur de tri.

        $.ajax({
            url: ajax_object.ajaxurl, // ajaxurl défini dans WordPress via wp_localize_script().
            type: 'POST',
            data: {
                action: 'filter_photos', // Action que WordPress doit exécuter sur le serveur.
                category: category,
                format: format,
                sort: sort
            },
            success: function(response) {
                // Met à jour le contenu de la galerie avec les nouvelles photos.
                $('#photo-gallery').html(response.content);
            }
        });
    }

    // Gestion de la pagination infinie, si applicable.
    $(window).scroll(function() {
        // Déclenche la fonction de chargement plus de photos si l'utilisateur arrive en bas de la page.
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) { // 100px avant le bas
            loadMorePhotos();
        }
    });

    // Fonction pour charger plus de photos, appelée lors du défilement.
    function loadMorePhotos() {
        var nextPage = parseInt($('#load-more-photos').data('page')) + 1 || 2;
        var category = $('#photo-category').val();
        var format = $('#photo-format').val();
        var sort = $('#photo-sort').val();

        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_photos',
                page: nextPage,
                category: category,
                format: format,
                sort: sort
            },
            success: function(response) {
                if (response.content) {
                    $('#photo-gallery').append(response.content);
                    $('#load-more-photos').data('page', nextPage);
                } else {
                    $('#load-more-photos').hide();
                }
            }
        });
    }
});