<div><?php
include_once "Check_Admin.php";
include RootDocumentShare."/cache/Productclass_show.php";
echo $Char_class->get_page_select("bid" . intval($_GET['count']),"","  class=\"trans-input\" ");
?>
</div>