function doSubscribersList(action) {
    var checkFound = false;
    var k = document.updatesubsbulk.idEmail.length;
    if (typeof k == "undefined" && document.updatesubsbulk.idEmail.checked == false) {
        checkFound = false;
    }
    if (typeof k == "undefined" && document.updatesubsbulk.idEmail.checked == true) {
        checkFound = true;
    }
    for (var i = 0; i < document.updatesubsbulk.idEmail.length; i++)
        if (document.updatesubsbulk.idEmail[i].checked == true) {
            checkFound = true;
        }
    if (checkFound != true) {
        $("#noneselected").show();
        return false;
    } else {
        $("#noneselected").hide();
        $("#loading").show();
		$( "#delete" ).prop( "disabled", true );
		$( "#remove" ).prop( "disabled", true );
		$( "#confirm" ).prop( "disabled", true );
        var deleteText = $("#deleteText").val();
        var confirmText = $("#confirmText").val();
        var removeText = $("#removeText").val();
        switch (action) {
        case "delete":
            text = deleteText;
            break;
        case "confirm":
            text = confirmText;
            break;
        case "remove":
            text = removeText;
            break;
        }
        var url = "updateSubsBulk.php?action=" + action;
        var params = $("#updatesubsbulk").serialize();
        $("#theparams").val(params);
        $("#isAjax").val("1");
        openConfirmBox(url, text); 
    }
}

function checkAll(action) {
    var k = document.updatesubsbulk.idEmail.length;
    if (typeof k == "undefined") {
        document.updatesubsbulk.idEmail.checked = action;
        return false;
    } else {
        for (var i = 0; i < document.updatesubsbulk.idEmail.length; i++) {
            document.updatesubsbulk.idEmail[i].checked = action;
        }
    }
}