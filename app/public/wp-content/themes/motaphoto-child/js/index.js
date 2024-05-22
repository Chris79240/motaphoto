jQuery(document).ready(function($) {
    // Fonction pour mettre à jour la galerie selon les filtres appliqués
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
                if (response.success) {
                    $('#photo-gallery').html(response.data.content);
                } else {
                    $('#photo-gallery').html('<p>Aucune photo trouvée.</p>');
                }
            }
        });
    }

    $('.photo-filter').change(updatePhotoGallery);

    $('#load-more-photos').on('click', function() {
        var button = $(this);
        var page = button.data('page') || 1;
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_photos',
                page: page
            },
            success: function(response) {
                if (response.success) {
                    $('#photo-gallery').append(response.data.content);
                    button.data('page', page + 1);
                    if (response.data.has_more === false) {
                        button.hide();
                    }
                } else {
                    button.hide();
                }
            }
        });
    });

    // Modale de contact
    $('.open-contact-modal').click(function(e) {
        e.preventDefault();
        $('.contact-modal').fadeIn().css('display', 'flex');
    });

    $('.close-modal').click(function() {
        $('.contact-modal').fadeOut();
    });

    // Lightbox
    document.querySelectorAll('.photo-block img').forEach(function(image) {
        image.addEventListener('click', function() {
            var container = document.querySelector('.containerLightbox');
            var lightboxImg = document.querySelector('.lightboxImage');
            container.style.display = 'flex';
            lightboxImg.src = this.src;
            document.querySelector('.lightboxTitle').textContent = this.alt;
        });
    });

    document.querySelector('.lightboxClose').addEventListener('click', function() {
        document.querySelector('.lightbox').style.display = 'none';
    });

    // Gestion du menu burger
    const navToggler = document.querySelector('.nav-toggler');
    const menuContent = document.querySelector('.burger-menu-content');
    navToggler.addEventListener('click', function() {
        this.classList.toggle('active');
        const header = document.querySelector('header');
        header.classList.toggle('fixed');
        menuContent.classList.toggle('active'); // Utilisez 'active' au lieu de 'display'
    });

    // Cliquer à l'extérieur pour fermer le menu
    document.addEventListener('click', function(event) {
        if (!navToggler.contains(event.target) && !menuContent.contains(event.target)) {
            navToggler.classList.remove('active');
            menuContent.classList.remove('active');
        }
    });
});