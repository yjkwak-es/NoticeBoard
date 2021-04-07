<?php
include "/ESGROUP/PHPSever/test/class/FileBoard.php";
$TID = $_POST['TID'];

$board = new FileBoard();
$ret = $board->deleteFile($TID);

if (!$ret) : ?>
    <script>
        alert("Delete error")
        history.go(-1)
    </script>
<?php
else : ?>
    <script>
        alert("Deleted File!")
        history.go(-1)
    </script>
<?php
endif; ?>