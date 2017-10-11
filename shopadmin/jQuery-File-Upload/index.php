<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link href="uploadfile.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="jquery.uploadfile.min.js"></script>
</head>
<body>
Scroll Issue:

<div id="mulitplefileuploader">Upload</div>

<div id="status"></div>
<form action="test.php" method="post">
<div id="fileslist"></div>
<input name="1" type="submit" value="提交">
</form>
<script>
$(document).ready(function()
{
var settings = {
    url: "upload.php",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"jpg,png,gif,doc,pdf,zip",	
    returnType:"json",
	 onSuccess:function(files,data,xhr)
    {
        alert((data));
		$("#fileslist").append("<input type='text' name='files[]' value='" + data + "'>"); 
    },
    showDelete:true,
    deleteCallback: function(data,pd)
	{
    for(var i=0;i<data.length;i++)
    {
        $.post("delete.php",{op:"delete",name:data[i]},
        function(resp, textStatus, jqXHR)
        {
            //Show Message 
			len = document.getElementsByName("files[]").length;
			obj_file = document.getElementsByName("files[]");
			for(j=0;j<len;j++){
				if(obj_file[j].text=resp){
					obj_file[j].remove();
				}
			}
			alert(resp);
            $("#status").append("<div>File Deleted</div>");      
        });
     }      
    pd.statusbar.hide(); //You choice to hide/not.

}
}
var uploadObj = $("#mulitplefileuploader").uploadFile(settings);


});
</script>
</body>

