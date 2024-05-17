jQuery(document).ready(function($) {
    // Fonction pour mettre à jour la galerie selon les filtres appliqués
    function updatePhotoGallery() {
        var category = $('#photo-category').find(":selected").text();
        var format = $('#photo-format').find(":selected").text();
        var sort = $('#photo-sort').find(":selected").text();
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
                $('#photo-gallery').html(response.data.content);
            }
        });
    }

    
    $('.photo-filter').change(updatePhotoGallery);

    $('#load-more-photos').on('click', function() {
        var button = $(this);
        var page = button.data('page') || 1; // Récupère le numéro de page actuel ou définit à 1 s'il n'est pas défini
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
                    button.data('page', response.data.page); // Met à jour le numéro de page
                    if (response.data.page > response.data.max_page) button.hide();
                } else {
                    button.hide(); // Cache le bouton s'il n'y a plus de photos à charger
                }
            }
        });
    });


    $('.open-contact-modal').click(function(e) {
        e.preventDefault();
        $('.contact-modal').fadeIn().css('display', 'flex');
    });

    $('.close-modal').click(function() {
        $('.contact-modal').fadeOut();
    });

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
});



document.addEventListener('DOMContentLoaded', function() {
    var burgerButton = document.querySelector('.burger-menu-button');
    var burgerContent = document.querySelector('.burger-menu-content');

    burgerButton.addEventListener('click', function() {
        var display = burgerContent.style.display;
        burgerContent.style.display = display === 'block' ? 'none' : 'block';
    });

    // Close the burger menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!burgerButton.contains(event.target) && !burgerContent.contains(event.target) && burgerContent.style.display === 'block') {
            burgerContent.style.display = 'none';
        }
    });
});



//gestion du menu burger//
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