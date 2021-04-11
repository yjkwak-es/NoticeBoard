<?php
include_once __DIR__ . "/class/Admin.php";

$ID = $_POST['ID'];
$PW = $_POST['PW'];
?>

<script>
    <? if (!isset($ID)) : ?>
        alert('None ID!')
        history.go(-1)
    <? endif ?>

    <? if (!isset($PW)) : ?>
        alert('None PW!')
        history.go(-1)
    <? endif ?>
</script>

<?
if (!isset($ID) || !isset($PW)) {
    exit;
}

$admin = new admin();
$row = $admin->getMemberID($ID);
?>


<script>
    <? if ($row['PW'] != $PW) : ?>
        alert('PW Incorrect')
        history.go(-1)
    <? endif; ?>
</script>

<?
session_start();
$_SESSION['ID'] = $ID;
?>

<script>
    top.location.href = 'noticeBoard.php'
</script>