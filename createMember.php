<?php
include_once "/ESGROUP/PHPSever/test/class/Admin.php";

$ID = $_POST['ID'];
$PW = $_POST['PW'];
?>

<script>
    <?php if (!$ID) : ?>
        alert('None ID');
    <?php endif; ?>
    <?php if (!$PW) : ?>
        alert('None PW')
    <?php endif; ?>
    history.go(-1)
</script>

<?php

if (!$ID || !$PW) {
    exit;
}

$mem = new Admin();
$result = $mem->createMember($ID, $PW);

?>

<? if (!$result) : ?>
    <script>
        alert('already have ID')
        history.go(-1)
    </script>
<?php else : ?>
    <script>
        alert('Create Success')
        top.location.href = "login_form.html"
    </script>
<?php endif; ?>