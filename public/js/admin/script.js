/*jslint sloppy: true*/
/*global $ */

$(function () {

    // SIDEBAR
    var sidebarAnimation = function () {
        $("#sidebar").removeClass("animated")
            .addClass("animated")
            .toggleClass("slideOutLeft slideInLeft");
        $("#content").toggleClass("full-width");
        $("#nav-toggle").toggleClass("active");
    };

    $("#nav-toggle").click(function () {
        sidebarAnimation();
    });



    var hideOnSmallScreen = function () {
        if ($("#sidebar").hasClass("slideInLeft")) {
            sidebarAnimation();
        }
    };

    var showOnWideScreen = function () {
        if ($("#sidebar").hasClass("slideOutLeft")) {
            sidebarAnimation();
        }
    };

    if ($(window).width() <= 992) {
        sidebarAnimation();
    }

    $(window).on('resize', function () {
        if ($(window).width() <= 992) {
            hideOnSmallScreen();
        } else {
            showOnWideScreen();
        }
    });



    // jquery.Nicescroll    
    $("#live-chat-body").niceScroll({
        horizrailenabled: false
    });

    $("html").niceScroll({
        cursorwidth: "5px",
        cursorcolor: "#353c40",
        cursorborder: "none",
        zindex: 3
    });

})
