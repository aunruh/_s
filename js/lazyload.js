var loadImage;
var offsetViewport = 3000;

jQuery(document).ready(function(){

  jQuery.hisrc.speedTest({
    speedTestUri: passedData.templateDir+'/js/plugins/hisrc/50K.jpg',
    success: function(){
      lazyLoad(jQuery.hisrc.bandwidth, jQuery.hisrc.devicePixelRatio);
      console.log('highspeed');
    },
    error: function(){
      lazyLoad('slow', 1);
      console.log('lowspeed');
    }
  });

  function lazyLoad(bandwidth, pixelratio){

    loadImage = function (el, callback) {
      if( typeof el != "undefined" ){
        var src;
        var width = parseInt(jQuery(el).width(), 10);
        var height = parseInt(jQuery(el).height(), 10);

        if(jQuery(el).hasClass('gif')){
          src = el.getAttribute('data-gif');
        }
        else{
          if(bandwidth == 'high' && pixelratio >= 2){

            if( width <= 133 ){
              src = el.getAttribute('data-r265');
            }     
            else if( width <= 265 ){
              src = el.getAttribute('data-r512');
            }
            else if( width <= 512 ){
              src = el.getAttribute('data-r1024');
            }
            else if( width <= 640 ){
              src = el.getAttribute('data-r1280');
            }     
            else{
              src = el.getAttribute('data-r1920');
            }

            // console.log('retina');
          }
          else{

            if( width <= 265 ){
              src = el.getAttribute('data-r265');
            }
            else if( width <= 512 ){
              src = el.getAttribute('data-r512');
            }
            else if( width <= 768 ){
              src = el.getAttribute('data-r768');
            }
            else if( width <= 1024 ){
              src = el.getAttribute('data-r1024');
            }
            else if( width <= 1280 ){
              src = el.getAttribute('data-r1280');
            }     
            else{
              src = el.getAttribute('data-r1920');
            }   
            
            // console.log('normal, width: '+width);
          }
        }

      el.src = src;
      jQuery(el).addClass('loaded');

      jQuery(el.parentNode).waitForImages(function() {
        jQuery(el).css('opacity', 1);

        if(typeof callback === 'function'){
          callback();
        }
      });

      }
    };

    function elementInViewport(el, offset) {
      var rect = el.getBoundingClientRect();
      return (
      rect.top+offset >= 0 &&
          rect.left >= 0 &&
          rect.bottom-offset <= (window.innerHeight || document.documentElement.clientHeight) && 
          rect.right <= (window.innerWidth || document.documentElement.clientWidth) 
      );
    } 

    var images = jQuery('img');

    var processResize = _.throttle(function(event) {

      jQuery('img.loaded').removeClass('loaded');
        
      jQuery.each(images, function(){
        if (elementInViewport(this, offsetViewport)) {
          loadImage(this);
        }   
      });

    }, 300); 

    var processScroll = _.throttle(function(event) {

        jQuery.each(images, function(){
          if (elementInViewport(this, offsetViewport) && !jQuery(this).hasClass('loaded')) {
            loadImage(this);
          }   
        });
      
    }, 300);

    jQuery(window).on('DOMContentLoaded load scroll touchstart touchend touchmove', processScroll); 

    jQuery(window).on('resize', processResize); 

  }

});