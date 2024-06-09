jQuery(document).ready(function($) {
    let currentIndex = -1; // Initialiser l'index actuel à -1
    let images = []; // Initialiser le tableau des images

    // Fonction pour mettre à jour la galerie selon les filtres appliqués
    function updatePhotoGallery() {
        let category = $('#photo-category').val(); // Obtenir la valeur de la catégorie sélectionnée
        let format = $('#photo-format').val(); // Obtenir la valeur du format sélectionné
        let sort = $('#photo-sort').val(); // Obtenir la valeur du tri sélectionné
        $.ajax({
            url: ajax_object.ajaxurl, // URL de l'action AJAX
            type: 'POST', // Type de requête
            data: {
                action: 'filter_photos', // Action pour filtrer les photos
                category: category, // Catégorie sélectionnée
                format: format, // Format sélectionné
                sort: sort // Tri sélectionné
            },
            success: function(response) {
                if (response.success) {
                    $('#photo-gallery').html(response.data.content); // Mettre à jour le contenu de la galerie
                } else {
                    $('#photo-gallery').html('<p>Aucune photo trouvée.</p>'); // Afficher un message si aucune photo n'est trouvée
                }
                updateImagesArray(); // Mettre à jour le tableau des images après le filtrage
            }
        });
    }

    // Attacher la fonction de mise à jour de la galerie au changement de filtre
    $('.photo-filter').change(updatePhotoGallery);

    // Charger plus de photos au clic sur le bouton "Charger plus"
    $('#load-more-photos').on('click', function() {
        let button = $(this);
        let page = button.data('page') || 2; // Obtenir la page actuelle ou par défaut la page 2
        let category = $('#photo-category').val(); // Obtenir la catégorie sélectionnée
        let format = $('#photo-format').val(); // Obtenir le format sélectionné
        let sort = $('#photo-sort').val(); // Obtenir le tri sélectionné
        $.ajax({
            url: ajax_object.ajaxurl, // URL de l'action AJAX
            type: 'POST', // Type de requête
            data: {
                action: 'filter_photos', // Action pour filtrer les photos
                page: page, // Page actuelle
                category: category, // Catégorie sélectionnée
                format: format, // Format sélectionné
                sort: sort // Tri sélectionné
            },
            success: function(response) {
                if (response.success) {
                    $('#photo-gallery').append(response.data.content); // Ajouter les nouvelles photos à la galerie
                    button.data('page', page + 1); // Incrémenter la page pour le prochain chargement
                    if (response.data.has_more === false) {
                        button.hide(); // Cacher le bouton s'il n'y a plus de photos à charger
                    }
                } else {
                    button.hide(); // Cacher le bouton s'il y a une erreur
                }
                updateImagesArray(); // Mettre à jour le tableau des images après le chargement supplémentaire
            }
        });
    });

    // Mettre à jour le tableau des images pour la navigation dans la lightbox
    function updateImagesArray() {
        images = [];
        $('.photo-block img').each(function(index) {
            images.push({
                src: $(this).attr('src'), // Source de l'image
                title: $(this).attr('alt'), // Titre de l'image
                category: $(this).data('category'), // Catégorie de l'image
                reference: $(this).data('reference') // Référence de l'image
            });
        });
    }
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

    // Ouvrir la lightbox
    $(document).on('click', '.fullscreen-icon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let image = $(this).closest('.photo-block').find('img'); // Trouver l'image associée à l'icône cliquée
        let src = image.attr('src'); // Obtenir la source de l'image
        let title = image.attr('alt'); // Obtenir le titre de l'image
        let category = image.data('category'); // Obtenir la catégorie de l'image
        let reference = image.data('reference'); // Obtenir la référence de l'image
        let container = $('.containerLightbox'); // Trouver la lightbox
        let lightboxImg = container.find('.lightboxImage'); // Trouver l'élément image de la lightbox
        container.find('.lightboxTitle').text(title); // Mettre à jour le titre de la lightbox
        container.find('.lightboxCategorie').text(category); // Mettre à jour la catégorie de la lightbox
        container.find('.lightboxReference').text(reference); // Mettre à jour la référence de la lightbox
        lightboxImg.attr('src', src); // Mettre à jour la source de l'image de la lightbox
        currentIndex = images.findIndex(img => img.src === src); // Trouver l'index de l'image actuelle
        container.fadeIn(); // Afficher la lightbox
    });

    // Fermer la lightbox
    $('.lightboxClose').click(function() {
        $('.containerLightbox').fadeOut();
    });

    // Navigation dans la lightbox
    function showImage(index) {
        if (index >= 0 && index < images.length) {
            currentIndex = index; // Mettre à jour l'index actuel
            let image = images[currentIndex]; // Obtenir l'image actuelle
            let container = $('.containerLightbox'); // Trouver la lightbox
            let lightboxImg = container.find('.lightboxImage'); // Trouver l'élément image de la lightbox
            container.find('.lightboxTitle').text(image.title); // Mettre à jour le titre de la lightbox
            container.find('.lightboxCategorie').text(image.category); // Mettre à jour la catégorie de la lightbox
            container.find('.lightboxReference').text(image.reference); // Mettre à jour la référence de la lightbox
            lightboxImg.attr('src', image.src); // Mettre à jour la source de l'image de la lightbox
        }
    }

    // Navigation vers l'image précédente
    $(document).on('click', '.lightboxPrevious', function(e) {
        e.preventDefault();
        showImage(currentIndex - 1); // Afficher l'image précédente
    });

    // Navigation vers l'image suivante
    $(document).on('click', '.lightboxNext', function(e) {
        e.preventDefault();
        showImage(currentIndex + 1); // Afficher l'image suivante
    });

    // Gestion du menu burger
    const navToggler = document.querySelector('.nav-toggler');
    const menuContent = document.querySelector('.burger-menu-content');
    navToggler.addEventListener('click', function() {
        this.classList.toggle('active'); // Activer/désactiver l'état actif du menu
        const header = document.querySelector('header');
        header.classList.toggle('fixed'); // Activer/désactiver la classe fixe du header
        menuContent.classList.toggle('active'); // Activer/désactiver l'état actif du contenu du menu
    });

    // Cliquer à l'extérieur pour fermer le menu
    document.addEventListener('click', function(event) {
        if (!navToggler.contains(event.target) && !menuContent.contains(event.target)) {
            navToggler.classList.remove('active'); // Désactiver l'état actif du menu
            menuContent.classList.remove('active'); // Désactiver l'état actif du contenu du menu
        }
    });

    // Chargement initial du tableau des images
    updateImagesArray();
});