<?php
include "/ESGROUP/PHPSever/test/idCheck.php";
include "/ESGROUP/PHPSever/test/class/Reply.php";

$reply = new Reply();

$Paragraph = $_POST['Paragraph']; //댓글 본문
$Parent = $_POST['Parent']; //댓글이 달린 게시글 번호

if (empty($Paragraph)) : ?>
    <script>
        alert('None Reply')
        history.go(-1)
    </script>
<?php
exit;
endif;

if (empty($Parent)) { ?>
    <script>
        alert('Can`t read Post')
        history.go(-1)
    </script>
<?php
exit;
}
$Paragraph = addslashes($Paragraph); //특수문자 입력처리

if(isset($_POST['RID'])) {
    $RID = $_POST['RID']; //수정 댓글일 경우 RID 또한 전송 받음
    $result = $reply->setReply($RID,$Paragraph);
}
else {
    $id = $mem->getID();
    $result = $reply->createReply($Parent,$id,$Paragraph);
}

if (!$result) { ?>
    <script>
        alert('Saving Reply was Failed!')
        history.go(-1)
    </script>
<?php
exit;
}?>
    <script>
        alert('Saved Reply!')
        top.location.href = "viewPost.php?TID=<?=$Parent?>"
    </script>
