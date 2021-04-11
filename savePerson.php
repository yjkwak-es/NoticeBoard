<?php
include_once __DIR__ . "/idCheck.php";
?>

<script>
    <? if (empty($_POST['name'])) : ?>
        alert('None Name')
        history.go(-1)
        <? exit; ?>
    <? endif; ?>
</script>

<?
$mem->setMember($_POST['name'], $_POST['age'], $_POST['gender']);
?>

<script>
    alert('Saved!')
    window.opener.location.reload();
    window.close();
</script>