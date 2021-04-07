<?php
include "/ESGROUP/PHPSever/test/idCheck.php";
include "/ESGROUP/PHPSever/test/class/FileBoard.php";

$board = new FileBoard();

if (empty($_POST['TID'])) : ?>
    <script>
        alert('Error')
        history.go(-1);
    </script>
<?php
endif;
$result = $board->deletePost($_POST['TID']);
$TID = $_POST['TID'];
if ($result) : ?>
    <script>
        alert('Deleted!')
        history.go(-1)
    </script>
<?php
else : ?>
    <script>
        alert('Deleted Failed!')
        history.go(-1)
    </script>
<?php 
endif;