// Plugins
$.extend( $.easing,
    { def: 'easeOutQuad', easeOutQuad: function (x, t, b, c, d) { return -c *(t/=d)*(t-2) + b } }
);

// On document ready
$(function(){
    
    // Message 1ere visite
    var firsttime = $( "#first-time" );

    var showFirstTimeMessage = function() {
        firsttime.slideDown( 100, "easeOutQuad", function(){
            $( this ).addClass( "shown" )
        })
        .find( ".nothanks" ).click( function(e) {
            firsttime.slideUp( 150 );
            e.preventDefault();
        });
    };
    setTimeout(showFirstTimeMessage, 700);
    
    // Audio, Video via MediaElementPlayer
    typeof $.fn.mediaelementplayer !== "function" || $('video,audio').mediaelementplayer();

    // Boutons Twitter
    !$(".twitter-follow-button").length || $("#twjs").length ||
        $('<script src="//platform.twitter.com/widgets.js" id="twjs">').prependTo('body');

    // Back to top
    $(".to-top").click( function(e) {
        $("html, body").stop().animate( { scrollTop: 0 }, 400, "easeOutQuad" );
        e.preventDefault();
    });
    
    $('a[rel="external"]').click(function(){
        $(this).attr('target', '_blank');
    });

});