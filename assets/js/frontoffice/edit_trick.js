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

/** adding field add one or more videos  **/
jQuery(document).ready(function () {
    jQuery('.add-another-collection-widget').click(function (e) {
        var list = jQuery(jQuery(this).attr('data-list-selector'));
        // Try to find the counter of the list or use the length of the list
        var counter = list.data('widget-counter') || list.children().length;

        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
});
