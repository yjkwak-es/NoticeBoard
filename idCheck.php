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
$admin = new admin();

if ($_SESSION['ID'] === $admin->getID()) :
    $mem = $admin;
else :
    $mem = new member();
    $mem->setID($_SESSION['ID']);
endif;
?>