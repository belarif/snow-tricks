import '../../styles/frontoffice/home.css';

$(document).ready(function () {
    $(".card").slice(0, 15).show();
    $("#loadMore").on("click", function (e) {
        e.preventDefault();
        $(".card:hidden").slice(0, 5).slideDown();
        if ($(".card:hidden").length === 0) {
            $("#loadMore").text("Aucun trick");
        }
    });
})

