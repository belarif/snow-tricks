$(document).ready(function () {
    $('#show').click(function () {
        $("#edit_trick_images").show();
    });
});

function deleteImage(id) {
    if (confirm("Êtes vous sûr de vouloir supprimer l'image ?")) {
        window.location.href = "/snow-tricks/medias/image/delete/" + id;
    } else {
        alert("Vous avez annulé la suppression");
    }
}

window.deleteImage = deleteImage;

