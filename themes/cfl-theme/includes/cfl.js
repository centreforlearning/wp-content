/**
 * Created with JetBrains PhpStorm.
 * User: sQrt121
 * Date: 9/25/13
 * Time: 1:39 PM
 * To change this template use File | Settings | File Templates.
 */

(function ($) {
    $(document).ready(function($){
      var isMobile = ($('body').hasClass('coll-mobile')) ? true : false;
  		if (isMobile) {
  			$('.magadi-days-grid .item').on('click', function () {
  				$(this).toggleClass('hover');
  			})
  		}

      var _gallery = $('.cfl-gallery');
      var _sizer = _gallery.find('.photo');
      var _gutter = _gallery.find('.gutter');
      _gallery.isotope({
        // options
        itemSelector: '.photo',
        percentPosition: true,
        masonry: {
          columnWidth: _sizer[0],
          gutter: _gutter[0]
        }
      });
      _gallery.imagesLoaded().progress( function() {
        _gallery.isotope('layout');
        $(document).trigger('coll.container.update');
      });

      $("[data-fancybox]").fancybox({
      	loop: true,
        buttons : [
          'slideShow',
          'fullScreen',
          'close'
        ],
        animationEffect : "fade",
        animationDuration : 500,
        transitionDuration : 500,
      });
    });
}(jQuery));
