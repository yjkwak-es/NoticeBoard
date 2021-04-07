<?php
include "/ESGROUP/PHPSever/test/idCheck.php";
include "/ESGROUP/PHPSever/test/class/FileBoard.php";

$Title = $_POST['title'];
$Paragraph = $_POST['Paragraph'];

if (empty($Title)) { ?>
    <script>
        alert('None Title')
        history.go(-1)
    </script>
<?php
    exit;
}

if (empty($Paragraph)) { ?>
    <script>
        alert('None Paragraph')
        history.go(-1)
    </script>
<?php
    exit;
}

$Title = addslashes($Title);
$Paragraph = addslashes($Paragraph);

$board = new FileBoard();

if (!empty($_POST['TID'])) : //수정
    if (isset($_FILES['upfile']) && $_FILES['upfile']['name'] != "") : //파일첨부함
        $file = $_FILES['upfile'];
        $result = $board->setPost($_POST['TID'], $Title, $Paragraph, $file);
    else : //텍스트만
        $result = $board->setPost($_POST['TID'], $Title, $Paragraph);
    endif;
else : //생성
    if (isset($_FILES['upfile']) && $_FILES['upfile']['name'] != "") : //파일첨부함
        $file = $_FILES['upfile'];
        $result = $board->createPost($mem->getID(), $Title, $Paragraph, $file);
    else : //텍스트만
        $result = $board->createPost($mem->getID(), $Title, $Paragraph);
    endif;
endif;

if (!$result) { ?>
    <script>
        alert('Post Save Failed!')
        history.go(-1)
    </script>
<?php
    exit;
}
?>
<script>
    alert('Post Save Success!')
    top.location.href = 'noticeBoard.php';
</script>