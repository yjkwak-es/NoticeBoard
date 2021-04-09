<?php
include __DIR__ . "/idCheck.php";
include __DIR__ . "/class/Reply.php";

$reply = new Reply();

$Paragraph = $_POST['Paragraph']; //댓글 본문
$Parent = $_POST['Parent']; //댓글이 달린 게시글 번호
?>

<script>
    <? if (empty($Paragraph)) : ?>
        alert('None Reply')
    <? endif; ?>

    <? if (empty($Parent)) : ?>
        alert('Can`t read Post')
        history.go(-1)
    <? endif; ?>
</script>

<?
if((empty($Paragraph)) || (empty($Parent))) {
    exit;
}

$Paragraph = addslashes($Paragraph); //특수문자 입력처리

if (isset($_POST['RID'])) :
    $RID = $_POST['RID']; //수정 댓글일 경우 RID 또한 전송 받음
    $result = $reply->setReply($RID, $Paragraph);
else :
    $result = $reply->createReply($Parent, $mem->getID(), $Paragraph);
endif;
?>

<script>
    <? if (!$result) : ?>
        alert('Saving Reply was Failed!')
        history.go(-1)
    <? else : ?>
        alert('Saved Reply!')
        top.location.href = "viewPost.php?TID=<?= $Parent ?>"
    <? endif; ?>
</script>