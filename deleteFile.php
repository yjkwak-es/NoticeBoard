<?php
include __DIR__ . "/class/FileBoard.php";
$TID = $_POST['TID'];

$board = new FileBoard();
$ret = $board->clearFile($TID);
?>

<script>
    <? if (!$ret) : ?>
        alert("Delete error")
    <? else : ?>
        alert("Deleted File!")
    <? endif; ?>
    
    history.go(-1)
</script>