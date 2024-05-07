function deleteUser(id) {
  if (confirm("Êtes vous sûr de vouloir supprimer l'utilisateur ?")) {
    window.location.href = "delete/" + id;
  } else {
    alert("Vous avez annulé la suppression");
  }
}

window.deleteUser = deleteUser;
