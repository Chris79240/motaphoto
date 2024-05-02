jQuery(document).ready(function($) {
    $('#photo-category, #photo-format, #photo-sort').change(function() {
        updatePhotoGallery();
    });

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

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            loadMorePhotos();
        }
    });

    function loadMorePhotos() {
        var nextPage = parseInt($('#load-more-photos').data('page')) + 1 || 2;
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_photos',
                page: nextPage
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

    jQuery(document).ready(function($) {
        $('.contact-btn').on('click', function(e) {
            e.preventDefault();
            $('.modal-overlay').fadeIn();
        });
        $('.close').on('click', function() {
            $('.modal-overlay').fadeOut();
        });
    });






    // Fermeture de la popup
    $('.close-btn').on('click', function() {
        $(this).closest('.popup-overlay').hide();
    });





    jQuery(document).ready(function($) {
        // Ouverture de la modale
        $('.open-contact-modal').click(function(e) {
            e.preventDefault();
            $('.contact-modal').fadeIn();
        });
    
        // Fermeture de la modale
        $('.close-modal').click(function() {
            $('.contact-modal').fadeOut();
        });
    });







    