import '../../styles/frontoffice/home.css';

/** load more tricks **/
$(document).ready(function () {
    $(".card-trick").slice(0, 15).show();
    $("#loadMore").on("click", function (e) {
        e.preventDefault();
        $(".card-trick:hidden").slice(0, 5).slideDown();
        if ($(".card-trick:hidden").length === 0) {
            $("#loadMore").text("Aucun trick");
        }
    });
})

