/* - VIDEO FIXED ON SCROLL -------------------- */

// Check if video-fixed element exists -> if yex, load function

$(function(){

    if($(".video-fixed").length > 0){
        onVideoFixedPageLoad();
    }

});



function onVideoFixedPageLoad(){

    /*- VIDEO FIXED ON WINDOW SCROLL ---- */
    //POSSIBLE TO ONLY RUN THIS CODE WHEN VIDEO IS FOUND ON A PAGE??
   
    if($(".video-fixed")){

        var $window = $(window);
        var $videoWrap = $(".video-wrap");
        var $video = $(".video-fixed");
        var $iframe = $(".video-fixed iframe");

        var videoHeight = $video.outerHeight();

        var player = new Vimeo.Player($iframe);

    }
    
    player.on('play', function() {
        $video.addClass("is-playing");
        $video.removeClass("is-paused");

    });
 
    player.on('pause', function() {
        $video.addClass("is-paused");
        $video.removeClass("is-playing");
    });

    $(window).on('scroll',  function() {
        
        console.log("hello world");

        var windowScrollTop = $window.scrollTop();
        var videoBottom = videoHeight + $videoWrap.offset().top;
        
        if (windowScrollTop > videoBottom && $video.hasClass("is-playing")) {
            $videoWrap.addClass('stuck');
            $video.addClass('stuck');
        } else {
            $videoWrap.removeClass('stuck');
            $video.removeClass('stuck');
        }
    });


}