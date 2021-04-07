<?php
include_once "/ESGROUP/PHPSever/test/class/Admin.php";

$ID = $_POST['ID'];
$PW = $_POST['PW'];

if (!$ID) : ?>
    <script>
        alert('None ID')
        history.go(-1)
    </script>
<?php
    exit;
endif;

if (!$PW) : ?>
    <script>
        alert('None PW')
        history.go(-1)
    </script>
<?php
    exit;
endif;

$admin = new admin();
$row = $admin->getMember($ID);

if ($row['PW'] == $PW) :
    session_start();
    $_SESSION['ID'] = $ID; ?>
    <script>
        top.location.href = 'noticeBoard.php';
    </script>
<?php
else : ?>
    <script>
        alert('PW Incorrect')
        history.go(-1)
    </script>
<?php
    exit;
endif;