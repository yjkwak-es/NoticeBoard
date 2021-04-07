<?php
include_once "/ESGROUP/PHPSever/test/class/FileBoard.php";
$file_id = $_GET['file_id'];
$board = new FileBoard();

$board->downloadFile($file_id);
?>
<script>
    window.close();
</script>
<?php