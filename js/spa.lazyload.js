spa.lazyload = (function(){

  var stateMap = {
    orientation : null,
    bandwidth : null,
    pixelratio : null,
  };

  var configMap = {
    offsetViewport : 3000,
  },
  // function names
  initModule, loadImage, lazyLoadImages, processResize, processScroll, elementInViewport, loadImage,

  initModule = function(){

    jQuery.hisrc.speedTest({
      speedTestUri: passedData.templateDir+'/js/plugins/hisrc-speedtest/50K.jpg',
      success: function(){
        stateMap.bandwidth = jQuery.hisrc.bandwidth;
        stateMap.pixelratio = jQuery.hisrc.devicePixelRatio;
        lazyLoadImages();
      },
      error: function(){
        stateMap.bandwidth = 'slow';
        stateMap.pixelratio = 1;
        lazyLoadImages();
      }
    });

    processResize = _.debounce(lazyLoadImages, 300);
    processScroll = _.throttle(lazyLoadImages, 100);

    jQuery(window).on('resize orientationchange', processResize);
    jQuery(window).on('scroll', processScroll);

  };

  loadImage = function (el) {
    if( typeof el != "undefined" ){
      var src;
      var width = parseInt(jQuery(el).width(), 10);
      var height = parseInt(jQuery(el).height(), 10);

      if(jQuery(el).hasClass('gif')){
        src = el.getAttribute('data-gif');
      }
      else{
        if(stateMap.bandwidth == 'high' && stateMap.pixelratio >= 2){

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

  elementInViewport = function(el, offset) {
    var rect = el.getBoundingClientRect();
    return (
    rect.top+offset >= 0 &&
        rect.left >= 0 &&
        rect.bottom-offset <= (window.innerHeight || document.documentElement.clientHeight) && 
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) 
    );
  } 

  lazyLoadImages = function(){
    stateMap.orientation = window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
          
    jQuery.each(jQuery('img'), function(){
      if (elementInViewport(this, configMap.offsetViewport)) {
        loadImage(this);
      }   
    });

  };

  return {
    initModule : initModule
  }

}());