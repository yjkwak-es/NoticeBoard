<?php
include __DIR__ . "/idCheck.php";
include __DIR__ . "/class/TextBoard.php";

$board = new TextBoard();

$tid = "";
$title = "";
$paragraph = "text here";
$FileID = "";
?>

<?
if (!empty($_GET)) :
    $tid = $_GET['TID'];
    $row = $board->getPost($tid); ?>

    <? if ($row['ID'] !== $mem->getID()) : ?>
        <script>
            top.location.href = "makePost.php"
        </script>
    <? endif;

    $title = $row['Title'];
    $paragraph = $row['Paragraph'];
    $FileID = $row['FileID'];
endif;
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <link rel="stylesheet" href="Board.css" type="text/css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        textarea {
            resize: none;
            border: none;
            outline: none;
        }
    </style>
</head>

<body>
    <div style="font-size:20px; text-align : right;">
        현재 ID : <?= $mem->getID() ?>
    </div>
    <div class="board">
        <!-- form -> TID(When Update mode), Title, Paragraph -->
        <form enctype="multipart/form-data" action="savePost.php" method=post name="postForm">
            <input type="hidden" name="TID" value=<?= $tid ?>>
            <table>
                <colgroup>
                    <col width="10%" />
                    <col width="*" />
                </colgroup>
                <tbody>
                    <tr>
                        <td style="text-align : right;"> 제목 : </td>
                        <td style="text-align : left;"> <input type="text" name="title" style="width : 60%;" value="<?= $title ?>"> </td>
                    </tr>
                    <tr>
                        <td colspan="2"> <textarea class="paragraph" name="Paragraph"><?= $paragraph ?></textarea></td>
                    </tr>
                    <tr>
                        <? if ($FileID == "") : ?>
                            <td colspan="2">
                                <label for="upfile">첨부파일 : </label>
                                <input type="file" id="upfile" name="upfile">
                            </td>
                        <? endif; ?>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: right;">
                <input type="submit" value="저장하기">
            </div>
        </form>
    </div>
</body>

</html>