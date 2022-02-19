import '../../styles/frontoffice/edit_trick.css';

$(document).ready(function () {
    $('#showFormImage').click(function () {
        $(".editImageTrick").show();
    });
});

$(document).ready(function () {
    $('#showFormVideo').click(function () {
        $(".editVideoTrick").show();
    });
});

/** click modal button automatically when page loaded **/
window.onload = function () {
    $("#modal-btn").click();
}

function deleteImage(id) {
    if (confirm("Êtes vous sûr de vouloir supprimer l'image ?")) {
        window.location.href = "/snow-tricks/medias/image/delete/" + id;
    } else {
        alert("Vous avez annulé la suppression");
    }
}

window.deleteImage = deleteImage;

function deleteVideo(id) {
    if (confirm("Êtes vous sûr de vouloir supprimer la vidéo ?")) {
        window.location.href = "/snow-tricks/medias/video/delete/" + id;
    } else {
        alert("Vous avez annulé la suppression");
    }
}

window.deleteVideo = deleteVideo;
