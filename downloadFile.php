<?php
include_once __DIR__."/class/FileBoard.php";
$file_id = $_GET['file_id'];
$board = new FileBoard();

$result = $board->downloadFile($file_id);
?>

<script>
    window.close();
</script>