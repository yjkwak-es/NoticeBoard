<?php
include_once __DIR__ . "/class/Admin.php";

$ID = $_POST['ID'];
$PW = $_POST['PW'];
?>

<script>
    <? if (!$ID) : ?>
        alert('None ID');
        history.go(-1)
    <? endif; ?>

    <? if (!$PW) : ?>
        alert('None PW')
        history.go(-1)
    <? endif; ?>
</script>

<?
if (!$ID || !$PW) {
    exit;
}

$mem = new Admin();
$result = $mem->createMember($ID, $PW);
?>

<script>
    <? if (!$result) : ?>
        alert('already have ID')
        history.go(-1)
    <? else : ?>
        alert('Create Success')
        top.location.href = "login_form.html"
    <? endif; ?>
</script>