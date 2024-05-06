jQuery(document).ready(function($) {
    // Initialiser les écouteurs d'événements pour les filtres et les interactions de la modale.

    // Écoute les changements sur les sélecteurs pour filtrer et trier les photos.
    $('#photo-category, #photo-format, #photo-sort').change(updatePhotoGallery);

    // Fonction pour mettre à jour la galerie selon les filtres appliqués.
    function updatePhotoGallery() {
        var category = $('#photo-category').val();
        var format = $('#photo-format').val();
        var sort = $('#photo-sort').val();
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_photos',
                category: category,
                format: format,
                sort: sort
            },
            success: function(response) {
                $('#photo-gallery').html(response.content);
            }
        });
    }

    // Gestion du chargement de plus de photos avec pagination infinie.
    $('#load-more-photos').on('click', function() {
        var button = $(this);
        var page = button.data('page') || 2;
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_photos',
                page: page
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.content) {
                    $('#photo-gallery').append(response.content);
                    button.data('page', response.page);
                }
            }
        });
    });

    // Gestion de l'ouverture et de la fermeture de la modale de contact.
    $('.open-contact-modal').click(function(e) {
        e.preventDefault();
        $('.contact-modal').fadeIn().css('display', 'flex');
    });

    $('.close-modal').click(function() {
        $('.contact-modal').fadeOut();
    });
});

// Gestion des interactions de la lightbox une fois que le DOM est entièrement chargé.
document.addEventListener('DOMContentLoaded', function() {
    // Ouverture de la lightbox au clic sur les images de la galerie.
    document.querySelectorAll('.photo-block img').forEach(function(image) {
        image.addEventListener('click', function() {
            var container = document.querySelector('.containerLightbox');
            var lightboxImg = document.querySelector('.lightboxImage');
            container.style.display = 'flex';
            lightboxImg.src = this.src;
            document.querySelector('.lightboxTitle').textContent = this.alt;
        });
    });

    // Fermeture de la lightbox.
    document.querySelector('.lightboxClose').addEventListener('click', function() {
        document.querySelector('.lightbox').style.display = 'none';
    });
});

// Gestion de la pagination infinie et du chargement dynamique des termes de taxonomies pour les filtres.
document.addEventListener('DOMContentLoaded', function() {
    let page = 1;

    function loadPhotos() {
        $.ajax({
            url: '/wp-json/mytheme/v1/photos/?page=' + page,
            type: 'GET',
            success: function(data) {
                if (data.length) {
                    data.forEach(photo => {
                        $('#photo-gallery').append(`<div><img src="${photo.url}" alt="${photo.title}" /></div>`);
                    });
                    page++;
                } else {
                    $('#load-more-photos').hide();
                }
            }
        });
    }

    $('#load-more-photos').on('click', loadPhotos);
    loadPhotos(); // Charge initial des photos.

    // Chargement dynamique des termes de taxonomies pour les filtres.
    function loadTerms(taxonomy) {
        $.ajax({
            url: '/wp-json/mytheme/v1/terms/?taxonomy=' + taxonomy,
            type: 'GET',
            success: function(terms) {
                terms.forEach(term => {
                    $(`#select-${taxonomy}`).append(`<option value="${term.term_id}">${term.name}</option>`);
                });
            }
        });
    }

    loadTerms('photo_categories');
    loadTerms('photo_formats');
    
    // Réapplique les filtres et rafraîchit la galerie lorsque les options de filtrage changent.
    $('.photo-filter').on('change', function() {
        $('#photo-gallery').empty();
        page = 1;
        loadPhotos(); // Recharger les photos selon les nouveaux filtres.
    });
});




jQuery(document).ready(function($) {
    // Intercepte le clic sur le lien de contact
    $('.contact-link').click(function(e) {
        e.preventDefault(); // Empêche la navigation vers l'URL du href
        $('#popup-contact').fadeIn().css('display', 'flex'); // Affiche la modale
    });

    // Fermeture de la modale
    $('.close-modal').click(function() {
        $('#popup-contact').fadeOut(); // Cache la modale
    });
});