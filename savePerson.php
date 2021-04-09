<?php
include_once __DIR__ . "/idCheck.php";
?>

<? if (empty($_POST['name'])) : ?>
    <script>
        alert('None Name')
        history.go(-1)
    </script>
    <? exit; ?>
<? endif; ?>

<?
$mem->setMember($_POST['name'], $_POST['age'], $_POST['gender']);
?>

<script>
    alert('Saved!')
    window.opener.location.reload();
    window.close();
</script>