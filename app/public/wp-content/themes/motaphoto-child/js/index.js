/*jQuery(document).ready(function($) {
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

    // Fermer la modale au clic à l'extérieur
    $(document).mouseup(function(e) {
        var modal = $(".contact-modal-content");
        if (!modal.is(e.target) && modal.has(e.target).length === 0) {
            $('.contact-modal').fadeOut();
        }
    });

    // Lightbox
    /*document.querySelectorAll('.photo-block img').forEach(function(image) {
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
    });*/
   /*/ $(document).on('click', '.photo-block .fullscreen-icon', function(e) {
        e.preventDefault();
        var image = $(this).closest('.photo-block').find('img');
        var src = image.attr('src');
        var title = image.attr('alt');
        var category = image.data('category');
        var reference = image.data('reference');
        
        var container = $('.containerLightbox');
        var lightboxImg = container.find('.lightboxImage');
        container.find('.lightboxTitle').text(title);
        container.find('.lightboxCategorie').text(category);
        container.find('.lightboxReference').text(reference);
        lightboxImg.attr('src', src);
        container.fadeIn();
    });

    $('.lightboxClose').click(function() {
        $('.containerLightbox').fadeOut();
    });

    // Gestion du menu burger
    const navToggler = document.querySelector('.nav-toggler');
    const menuContent = document.querySelector('.burger-menu-content');
    navToggler.addEventListener('click', function() {
        this.classList.toggle('active');
        const header = document.querySelector('header');
        header.classList.toggle('fixed');
        menuContent.classList.toggle('active');
    });

    // Cliquer à l'extérieur pour fermer le menu
    document.addEventListener('click', function(event) {
        if (!navToggler.contains(event.target) && !menuContent.contains(event.target)) {
            navToggler.classList.remove('active');
            menuContent.classList.remove('active');
        }
    });

    // Initialize Select2
    $('#photo-category').select2();
    $('#photo-format').select2();
    $('#photo-sort').select2();
});*/


jQuery(document).ready(function($) {
    // Fonction pour mettre à jour la galerie selon les filtres appliqués
    function updatePhotoGallery() {
        let category = $('#photo-category').val();
        let format = $('#photo-format').val();
        let sort = $('#photo-sort').val();
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
        let button = $(this);
        let page = button.data('page') || 2;
        let category = $('#photo-category').val();
let format = $('#photo-format').val();
let sort = $('#photo-sort').val();
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_photos',
                page: page,
                category: category,
format: format,
sort: sort
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
        let image = $(this).closest('.photo-block').find('img');
        jQuery("input[name='your-subject']").val(jQuery('.page-header p').html().slice(11));
        console.log("reference:"+image.data('reference'));
    });

    // Fermer la modale au clic à l'extérieur
    $(document).mouseup(function(e) {
        let modal = $(".contact-modal-content");
        if (!modal.is(e.target) && modal.has(e.target).length === 0) {
            $('.contact-modal').fadeOut();
        }
    });

    // Lightbox
    $(document).on('click', '.fullscreen-icon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let image = $(this).closest('.photo-block').find('img');
        let src = image.attr('src');
        let title = image.attr('alt');
        let category = image.data('category');
        let reference = image.data('reference');
        
        let container = $('.containerLightbox');
        let lightboxImg = container.find('.lightboxImage');
        container.find('.lightboxTitle').text(title);
        container.find('.lightboxCategorie').text(category);
        container.find('.lightboxReference').text(reference);
        lightboxImg.attr('src', src);
        container.fadeIn();
    });

    $('.lightboxClose').click(function() {
        $('.containerLightbox').fadeOut();
    });

    // Gestion du menu burger
    const navToggler = document.querySelector('.nav-toggler');
    const menuContent = document.querySelector('.burger-menu-content');
    navToggler.addEventListener('click', function() {
        this.classList.toggle('active');
        const header = document.querySelector('header');
        header.classList.toggle('fixed');
        menuContent.classList.toggle('active');
    });

    // Cliquer à l'extérieur pour fermer le menu
    document.addEventListener('click', function(event) {
        if (!navToggler.contains(event.target) && !menuContent.contains(event.target)) {
            navToggler.classList.remove('active');
            menuContent.classList.remove('active');
        }
    });

    // Initialize Select2
   /* $('#photo-category').select2();
    $('#photo-format').select2();
    $('#photo-sort').select2();*/
});