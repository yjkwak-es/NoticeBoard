<?php
include __DIR__ . "/idCheck.php";
include __DIR__ . "/class/FileBoard.php";

$Title = $_POST['title'];
$Paragraph = $_POST['Paragraph'];
?>

<script>
    <? if (empty($Title)) : ?>
        alert('None Title')
    <? endif; ?>

    <? if (empty($Paragraph)) : ?>
        alert('None Paragraph')
        history.go(-1)
    <? endif; ?>
</script>

<?
$Title = addslashes($Title);
$Paragraph = addslashes($Paragraph);

$board = new FileBoard();

if (isset($_FILES['upfile']) && $_FILES['upfile']['name'] != "") : //파일첨부
    $file = $_FILES['upfile'];
else :
    $file = array();
endif;

if (!empty($_POST['TID'])) : //수정
    $result = $board->setFilePost($_POST['TID'], $Title, $Paragraph, $file);
else : //생성
    $result = $board->createFilePost($mem->getID(), $Title, $Paragraph, $file);
endif;
?>

<script>
    <? if (!$result) : ?>
        alert('Post Save Failed!')
        history.go(-1)
    <? else : ?>
        alert('Post Save Success!')
        top.location.href = 'noticeBoard.php';
    <? endif; ?>
</script>