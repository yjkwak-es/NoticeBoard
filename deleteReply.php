<?php
include "/ESGROUP/PHPSever/test/class/Reply.php";
$RID = $_POST['RID'];

if (empty($_POST['RID'])) : ?>
    <script>
        alert('error : reply delete')
        history.go(-1)
    </script>
<?php
endif;

$reply = new Reply();
$result = $reply->deleteReply($RID);

if ($result) : ?>
    <script>
        alert('Reply Deleted!')
        history.go(-1)
    </script>
<?php
else : ?>
    <script>
        alert('error : reply delete')
        history.go(-1)
    </script>
<?php
endif;
