<?php
include_once __DIR__ . "/class/Admin.php";
session_start();
?>

<script>
    <? if (!isset($_SESSION['ID'])) : ?>
        alert('None ID')
        top.location.href = 'login_form.html'
    <? endif; ?>
</script>

<?
$admin = new Admin();

$mem = $_SESSION['ID'] === $admin->getID() ? $admin : new Member($_SESSION['ID']);
?>