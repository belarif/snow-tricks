import '../../styles/frontoffice/trick_details.css';

/** click modal button automatically when page loaded **/
window.onload = function () {
    $("#modal-btn").click();
}

/** load more messages **/
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