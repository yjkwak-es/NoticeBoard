<?php
include_once "/ESGROUP/PHPSever/test/class/Admin.php";

session_start();
if (!isset($_SESSION['ID'])) { ?>

    <script>
        alert('None ID')
        top.location.href = 'login_form.html';
    </script>
<?php
}
$admin = new admin();

if ($_SESSION['ID'] === $admin->getID()) :
    $mem = $admin;
else :
    $mem = new member();
    $mem->setID($_SESSION['ID']);
endif;

// define("ID", $_SESSION['ID']);
?>