// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('header').outerHeight();
var navbarHeight = $('nav').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
		

    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > 168){
        // Scroll Down
        $('.warpper-head').removeClass('nav-down').addClass('nav-up');
		$('.menumobile').removeClass('nav-down-m').addClass('nav-up-m');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('.warpper-head').removeClass('nav-up').addClass('nav-down');
			$('.menumobile').removeClass('nav-up-m').addClass('nav-down-m');
        }
    }
    
    lastScrollTop = st;
}