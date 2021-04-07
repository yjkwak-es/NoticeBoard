<?php
include_once "/ESGROUP/PHPSever/test/idCheck.php";

if (empty($_POST['name'])) : ?>
    <script>
        alert('None Name')
        history.go(-1)
    </script>
<?php
    exit;
endif;

// $_SESSION['name'] = $_POST['name'];
// $_SESSION['age'] = $_POST['age'];
// $_SESSION['gender'] = $_POST['gender']; 

$mem->setMember($_POST['name'],$_POST['age'],$_POST['gender']);

?>
<script>
    alert('Saved!')
    window.opener.location.reload();
    window.close();
</script>