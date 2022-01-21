function deleteTrick(id) {
    if (confirm("Êtes vous sûr de vouloir supprimer le trick ?")) {
        window.location.href = "/snow-tricks/tricks/delete/" + id;
    } else {
        alert("Vous avez annulé la suppression");
    }
}

window.deleteTrick = deleteTrick;
