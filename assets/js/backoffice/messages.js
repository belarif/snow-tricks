function deleteMessage(id) {
    if (confirm("Êtes vous sûr de vouloir supprimer le message ?")) {
        window.location.href = "delete/" + id;
    } else {
        alert("Vous avez annulé la suppression");
    }
}

window.deleteMessage = deleteMessage;