<?php
include __DIR__ . "/class/Reply.php";
$RID = $_POST['RID'];
?>

<script>
    <? if (empty($_POST['RID'])) : ?>
        alert('error : reply delete')
        history.go(-1)
    <? endif; ?>

    <?
    $reply = new Reply();
    $result = $reply->deleteReply($RID);
    ?>

    <? if ($result) : ?>
        alert('Reply Deleted!')
    <? else : ?>
        alert('error : reply delete')
    <? endif; ?>
    history.go(-1)
</script>