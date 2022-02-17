/** medias carousel **/
var multipleCardCarousel = document.querySelector(
    "#carouselExampleControls"
);
if (window.matchMedia("(min-width: 768px)").matches) {
    var carousel = new bootstrap.Carousel(multipleCardCarousel, {
        interval: false,
    });
    var carouselWidth = $(".carousel-inner")[0].scrollWidth;
    var cardWidth = $(".carousel-item").width();
    var scrollPosition = 0;
    $("#carouselExampleControls .carousel-control-next").on("click", function () {
        if (scrollPosition < carouselWidth - cardWidth * 4) {
            scrollPosition += cardWidth;
            $("#carouselExampleControls .carousel-inner").animate(
                {scrollLeft: scrollPosition},
                600
            );
        }
    });
    $("#carouselExampleControls .carousel-control-prev").on("click", function () {
        if (scrollPosition > 0) {
            scrollPosition -= cardWidth;
            $("#carouselExampleControls .carousel-inner").animate(
                {scrollLeft: scrollPosition},
                600
            );
        }
    });
} else {
    $(multipleCardCarousel).addClass("slide");
}

/** click modal button automatically when page loaded **/
window.onload = function () {
    $("#modal-btn").click();
}

$(document).ready(function () {
    $(".card-message").slice(0, 3).show();
    $("#loadMore").on("click", function (e) {
        e.preventDefault();
        $(".card-message:hidden").slice(0, 2).slideDown();
        if ($(".card-message:hidden").length === 0) {
            $("#loadMore").text("Aucun message");
        }
    });
})