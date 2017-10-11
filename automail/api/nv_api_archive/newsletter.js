function loadMenu() {
    $.ajax({
        type: "GET",
        url: url + "api/loadMenu.php",
        success: function (response) {
            $("#menuDiv").replaceWith(response);
        },
        error: function (xhr, status) {
            alert('Unknown error ' + status);
        }
    });
}

if (typeof jQuery == 'undefined') {
    var headTag = document.getElementsByTagName("head")[0];
    var jqTag = document.createElement('script');
    jqTag.type = 'text/javascript';
    jqTag.src = url + "admin/scripts/jQuery_2.1.0.js";
    jqTag.onload = loadMenu;
    headTag.appendChild(jqTag);
} else {
    loadMenu();
}

function loadNewsletter(idNewsletter) {
    if ($('#layerFrame')) {
        $('#layerFrame').remove();
    }
    var idNewsletter = $("#idNewsletter").val();
    var myFrame = document.createElement('iframe');
    myFrame.id = 'layerFrame';
    myFrame.style.height = '100%';
    myFrame.style.width = '100%';
    myFrame.style.frameborder = 'no';
    myFrame.style.border = 'none';
    myFrame.src = url + 'api/loadNewsletter.php?idNewsletter=' + idNewsletter;
    $("#holderDiv").append(myFrame);
}
