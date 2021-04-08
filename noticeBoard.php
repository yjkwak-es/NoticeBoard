<?php
include __DIR__."/idCheck.php";
include __DIR__."/class/FileBoard.php";

$board = new FileBoard();
$memberInfo = $mem->getMemberMy();

define("PAGESIZE", 3); //Number of Posts in 1 page
define("PAGEDIV", 3); //Number of pages in 1 Division

$page = 1;
if (isset($_GET['page'])) :
    $page = $_GET['page'];
    $page = $page <= 0 ? 1 : $page;
endif;

$q = "";
$opt = "";
?>

<? if (isset($_GET['q'])) :
    $q = $_GET['q'];
    $opt = $_GET['opt'];
    $posts = $board->searchPosts($q, $opt);
    
    if(mysqli_num_rows($posts) == 0) :?>
        <script>
            alert("no posts!")
            history.go(-1)
        </script>
    <? endif;
    
    $url = "noticeBoard.php?opt=" . $_GET['opt'] . "&q=" . $_GET['q'] . "&";
else :
    $posts = $board->getallPosts();
    $url = "noticeBoard.php?";
endif; ?>

<? if($mem->getID() === $admin->getID()) :
    $popup = "memberInfo.php";
else :
    $popup = "setPerson.php";
endif; ?>

<?
$pageCnt = mysqli_num_rows($posts);
$pageCnt = $pageCnt % PAGESIZE == 0 ? $pageCnt / PAGESIZE : (int)($pageCnt / PAGESIZE) + 1; // Number of Pages
$page = $page > $pageCnt ? $pageCnt : $page; //Out of range

$divStart = (int)(($page - 1) / PAGEDIV);
$divStart = $divStart * PAGEDIV + 1; //Find div starting page

$divEnd = $divStart + PAGEDIV - 1;
$divEnd = $divEnd > $pageCnt ?  $pageCnt : $divEnd; //Find div Ending page

$postNum = mysqli_num_rows($posts) - PAGESIZE * ($page - 1); //Post Number`s start value

for ($i = 0; $i < ($page - 1) * PAGESIZE; $i++) {
    $row = mysqli_fetch_assoc($posts);
}

$pagePoint = 0;
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <link rel="stylesheet" href="Board.css" type="text/css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <script>
        //상세정보 저장용 팝업
        function popup() {
            var option = "left=100,top=100,width=500,height=300";
            window.open("<?=$popup?>", "상세정보저장", option);
        }
    </script>
</head>

<body>
    <div style="text-align: right;">
        <? if (!empty($memberInfo['name'])) : ?>
            <span>접속자 : </span><a href="javascript:popup()"><?= $memberInfo['name'] ?></a>
        <? else : ?>
            <span>현재 ID : </span><a href="javascript:popup()"><?= $mem->getID() ?></a>
        <? endif; ?>
        <span> <button onclick="location.href='logout.php'">로그아웃</button> </span>
    </div>

    <div style="text-align: center;">
        <a href="noticeBoard.php">초기화</a>
    </div>

    <div class="board">
        <table>
            <caption style="display: none;">게시판</caption>
            <colgroup>
                <col width="10%" />
                <col width="50%" />
                <col width="10%" />
                <col width="*" />
            </colgroup>
            <thead>
                <tr>
                    <th> Num </th>
                    <th> Title </th>
                    <th> ID </th>
                    <th> Date </th>
                </tr>
            </thead>
            <tbody>
            <!-- 게시글 목록 -->
                <? while (($row = mysqli_fetch_assoc($posts)) && ($pagePoint++ != PAGESIZE)) :?>
                    <tr>
                        <td><?= $postNum-- ?></td>
                        <td style="text-align: left;">
                            <a href="viewPost.php?TID=<?= $row['TID'] ?>"><?= $row['Title'] ?></a>
                            
                            <!-- admin계정 글삭제 -->
                            <? if ($mem->getID() === $admin->getID()) : ?>
                                <form action="deletePost.php" method='post' style="display:inline">
                                    <input type="hidden" name="TID" value=<?= $row['TID'] ?>>
                                    <input type="submit" value="삭제">
                                </form>
                            <? endif; ?>

                        </td>
                        <td><?= $row['ID'] ?></td>
                        <td><?= $row['CreatedDate'] ?></td>
                    </tr>
                <? endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- 검색창 -->
    <div style="padding-right : 150px; text-align: right;">
        <form action="noticeBoard.php" method="get">
            <select id="searchOption" name="opt">
                <option value="Title">제목</option>
                <option value="Paragraph">본문</option>
                <option value="all">제목, 본문</option>
            </select>
            <input type="search" name="q" style="width: 10%;" required minlength="2" autocomplete="off" value=<?= $q ?>>
            <button>검색</button>
        </form>

        <script>
            if ("<?= $opt ?>" != "") {
                var sel = document.getElementById("searchOption");
                for (var i = 0; i < sel.length; i++) {
                    if (sel[i].value == "<?= $opt ?>") {
                        sel[i].selected = true;
                    }
                }
            }
        </script>
    </div>

    <div style="padding-right : 150px; text-align : right; margin-top:5px">
        <button onclick="location.href='makePost.php'">새글쓰기</button>
    </div>

    <!-- 페이징 -->
    <div class="page">
        <a href="<?= $url ?>page=<?= $page - 1 ?>"><-</a>

        <? for ($i = $divStart; $i <= $divEnd; $i++) : ?>
            <? if ($i == $page) : ?>
                 <a href="<?= $url ?>page=<?= $i ?>" style="color:red"><?= $i ?></a>
            <? else : ?>
                <a href="<?= $url ?>page=<?= $i ?>"><?= $i ?></a>
            <? endif; ?>
        <? endfor; ?>
        
        <a href="<?= $url ?>page=<?= $page + 1 ?>">-></a>
    </div>
</body>

</html>