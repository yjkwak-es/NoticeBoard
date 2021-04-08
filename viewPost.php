<?php
include_once __DIR__ . "/idCheck.php";
include_once __DIR__ . "/class/FileBoard.php";
?>

<script>
    <? if (empty($_GET['TID'])) : ?>
        top.location.href = "noticeBoard.php"
    <? endif; ?>
</script>

<?
$board = new FileBoard();
$reply = new Reply();

$TID = $_GET['TID'];
$row = $board->getPost($TID);

$replyResult = $reply->getallReplysAtPost($row['TID']);
$replyRow = mysqli_fetch_all($replyResult, MYSQLI_ASSOC);
?>

<!-- 댓글 수정 시 사용할 form 동적 생성 -->
<script>
    $(".updateBtn").click(function() {
        var RID = $(this).attr('id');

        var tr = $(this).parent().parent();
        var td = tr.children();

        var Paragraph = td.eq[1].innerHTML();
        var Parent = new RegExp('[\?&]' + 'TID' + '=([^&#]*)').exec(window.location.href);

        var newForm = $('<form></form>');

        newForm.attr("method", "post");
        newForm.attr("action", "SaveReply.php");

        newForm.append($('textarea'), {
            style: 'width : 100%; height : 100%',
            name: 'Paragraph',
            value: Paragraph
        });
        newForm.append($('input'), {
            type: 'hidden',
            name: 'RID',
            value: RID
        });
        newForm.append($('input'), {
            type: 'hidden',
            name: 'Parent',
            value: Parent
        });
        newForm.append($('input'), {
            type: 'submit',
            value: '수정완료'
        });

        newForm.appendTo(td.eq[1]);
    })
</script>

<!DOCTYPE html>
<html lang="ko-kr">

<head>
    <link rel="stylesheet" href="Board.css" type="text/css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="right">
        현재 ID : <?= $mem->getID() ?>
    </div>
    
    <!-- 본인 게시글 삭제 및 수정 -->
    <div class="board">
        <? if ($mem->getID() === $row['ID']) : ?>
            <div class="right">
                <form action="deletePost.php" method="post" style="display :inline;">
                    <input type="hidden" name="TID" value=<?= $TID ?>>
                    <input type="submit" value="삭제하기">
                </form>

                <button onclick="document.location='makePost.php?TID=<?= $TID ?>'">수정하기</button>
            </div>
        <? endif; ?>

        <!-- 게시글 보기 -->
        <table>
            <colgroup>
                <col width="10%" />
                <col width="75%" />
                <col width="5%" />
                <col width="*" />
            </colgroup>
            <tbody>
                <tr>
                    <td style="text-align: right;"> 제목 : </td>
                    <td style="text-align: left;"> <?= $row['Title'] ?> </td>
                    <td>작성자 : </td>
                    <td> <?= $row['ID'] ?></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="paragraph" id="paragraph"> <?= nl2br($row['Paragraph']) ?> </div>
                    </td>
                </tr>
                <tr>
                    <? if (isset($row['FileID'])) :
                        $File = $board->getFile($row['FileID']); ?>
                        <td colspan="4">
                            <a href="downloadFile.php?file_id=<?= $row['FileID'] ?>" target="_blank"><?= $File['name_orig'] ?></a>

                            <? if ($mem->getID() === $row['ID']) : ?>
                                <form action="deleteFile.php" method="post">
                                    <input type="hidden" name="TID" value=<?= $row['TID'] ?>>
                                    <input type="submit" value="삭제">
                                </form>
                            <? endif; ?>
                        </td>
                    <? endif; ?>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 댓글목록 -->
    <div class="Reply">
        <? if (mysqli_num_rows($replyResult)) : ?>
            <table id="replyTable">
                <colgroup>
                    <col width="10%" />
                    <col width="80%" />
                    <col width="*" />
                </colgroup>
                <tbody>
                    <? foreach ($replyRow as $rr => $val) : ?>
                        <tr>
                            <td style="text-align : right;"><?= $replyRow[$rr]['ID'] ?></td>
                            <td id="viewReply"><?= nl2br($replyRow[$rr]['Paragraph']) ?></td>
                            <td><?= $replyRow[$rr]['CreatedDate'] ?>
                                <? if ($mem->getID() === $replyRow[$rr]['ID']) : ?>
                                    <form action="deleteReply.php" method="post" style="display:inline">
                                        <input type="hidden" name="RID" value=<?= $replyRow[$rr]['RID'] ?>>
                                        <input type="submit" value="삭제">
                                    </form>

                                    <button id=<?= $replyRow[$rr]['RID'] ?> class="updateBtn">수정</button>
                                <? endif; ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        <? endif; ?>

        <!-- 댓글달기 -->
        <div style="text-align:right;">
            <form action="saveReply.php" name="Reply" method="Post">
                <input type="hidden" name="Parent" value=<?= $TID ?>>
                <textarea name="Paragraph" style="width : 100%; margin-top : 5px;white-space:pre-wrap; outline :black; border:black;"></textarea>
                <input type="submit" value="답글달기">
            </form>
        </div>
    </div>
</body>

<script>
    // function makeUpdate(rowNum, RID, Parent) {
    //     var replyTable = document.getElementById("replyTable");
    //     var inner = replyTable.rows[rowNum].cells[1].innerHTML;
    //     inner = inner.replace(/<\s*\/?br\s*[\/]?>/gi, "");

    //     var form = document.createElement("form");

    //     form.setAttribute("charset", "UTF-8");
    //     form.setAttribute("method", "Post");
    //     form.setAttribute("action", "SaveReply.php");
    //     form.setAttribute("id", rowNum);

    //     var inputField = document.createElement("textarea");
    //     inputField.setAttribute("style", "width : 100%;height : 100%;");
    //     inputField.setAttribute("name", "Paragraph");
    //     inputField.innerHTML = inner;
    //     form.appendChild(inputField);
    //     replyTable.rows[rowNum].cells[1].innerHTML = " ";

    //     var hiddenTID = document.createElement("input");
    //     hiddenTID.setAttribute("type", "hidden");
    //     hiddenTID.setAttribute("name", "RID");
    //     hiddenTID.setAttribute("value", RID);
    //     form.appendChild(hiddenTID);

    //     var hiddenParent = document.createElement("input");
    //     hiddenParent.setAttribute("type", "hidden");
    //     hiddenParent.setAttribute("name", "Parent");
    //     hiddenParent.setAttribute("value", Parent);
    //     form.appendChild(hiddenParent);

    //     var submitbtn = document.createElement("input");
    //     submitbtn.setAttribute("type", "submit");
    //     submitbtn.setAttribute("value", "수정완료");
    //     form.appendChild(submitbtn);

    //     replyTable.rows[rowNum].cells[1].appendChild(form);

    //     var btn = document.getElementById("updateBtn" + rowNum);
    //     btn.setAttribute("onclick", "");
    // }
</script>

</html>