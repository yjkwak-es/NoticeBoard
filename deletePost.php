<?php
include __DIR__ . "/idCheck.php";
include __DIR__ . "/class/FileBoard.php";

$board = new FileBoard();
?>

<script>
    <? if (empty($_POST['TID'])) : ?>
        alert('Error')
        history.go(-1);
    <? endif; ?>
</script>

<?
$result = $board->deleteFilePost($_POST['TID']);
$TID = $_POST['TID'];
?>

<script>
    <? if ($result) : ?>
        alert('Deleted!')
        top.location.href = 'noticeBoard.php'
    <? else : ?>
        alert('Deleted Failed!')
        history.go(-1)
    <? endif; ?>
</script>