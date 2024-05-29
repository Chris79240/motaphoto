jQuery(document).ready(function($){function updatePhotoGallery(){let category=$('#photo-category').val();let format=$('#photo-format').val();let sort=$('#photo-sort').val();$.ajax({url:ajax_object.ajaxurl,type:'POST',data:{action:'filter_photos',category:category,format:format,sort:sort},success:function(response){if(response.success){$('#photo-gallery').html(response.data.content)}else{$('#photo-gallery').html('<p>Aucune photo trouvée.</p>')}}})}
$('.photo-filter').change(updatePhotoGallery);$('#load-more-photos').on('click',function(){let button=$(this);let page=button.data('page')||1;$.ajax({url:ajax_object.ajaxurl,type:'POST',data:{action:'load_more_photos',page:page},success:function(response){if(response.success){$('#photo-gallery').append(response.data.content);button.data('page',page+1);if(response.data.has_more===!1){button.hide()}}else{button.hide()}}})});$('.open-contact-modal').click(function(e){e.preventDefault();$('.contact-modal').fadeIn().css('display','flex')});$(document).mouseup(function(e){let modal=$(".contact-modal-content");if(!modal.is(e.target)&&modal.has(e.target).length===0){$('.contact-modal').fadeOut()}});$(document).on('click','.fullscreen-icon',function(e){e.preventDefault();e.stopPropagation();let image=$(this).closest('.photo-block').find('img');let src=image.attr('src');let title=image.attr('alt');let category=image.data('category');let reference=image.data('reference');let container=$('.containerLightbox');let lightboxImg=container.find('.lightboxImage');container.find('.lightboxTitle').text(title);container.find('.lightboxCategorie').text(category);container.find('.lightboxReference').text(reference);lightboxImg.attr('src',src);container.fadeIn()});$('.lightboxClose').click(function(){$('.containerLightbox').fadeOut()});const navToggler=document.querySelector('.nav-toggler');const menuContent=document.querySelector('.burger-menu-content');navToggler.addEventListener('click',function(){this.classList.toggle('active');const header=document.querySelector('header');header.classList.toggle('fixed');menuContent.classList.toggle('active')});document.addEventListener('click',function(event){if(!navToggler.contains(event.target)&&!menuContent.contains(event.target)){navToggler.classList.remove('active');menuContent.classList.remove('active')}});$('#photo-category').select2();$('#photo-format').select2();$('#photo-sort').select2()})