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
                'action': 'filter_photos', // Action que WordPress doit exécuter sur le serveur.
                'category': category,
                'format': format,
                'sort': sort
            },
            success: function(response) {
                // Met à jour le contenu de la galerie avec les nouvelles photos.
                $('#photo-gallery').html(response.content);
            },
            error: function() {
                // Gère les erreurs ici si nécessaire.
                $('#photo-gallery').html('<p>Erreur lors du chargement des photos.</p>');
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
        if (currentPage < maxPages) {
            currentPage++; // Incrémenter la page courante.
            $.ajax({
                url: ajax_object.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'load_photos',
                    'page': currentPage,
                    'category': $('#photo-category').val(),
                    'format': $('#photo-format').val(),
                    'sort': $('#photo-sort').val()
                },
                success: function(response) {
                    if (response.content) {
                        $('#photo-gallery').append(response.content);
                        if (currentPage >= response.max_pages) {
                            $('#load-more-photos').hide(); // Cache le bouton si aucune autre page.
                        }
                    } else {
                        $('#load-more-photos').hide();
                    }
                },
                error: function() {
                    $('#photo-gallery').append('<p>Erreur lors du chargement des photos.</p>');
                }
            });
        }
    }
});