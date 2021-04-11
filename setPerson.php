<?php
include_once __DIR__ . "/idCheck.php";

$row = $mem->getMemberMy();
$name = isset($row['name']) ? $row['name'] : "";
$age = isset($row['age']) ? $row['age'] : "";
?>

<!DOCTYPE html>
<html lang="ko-kr">

<head>
    <style>
        div {
            text-align: center;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <form action="savePerson.php" id="personForm" method="post">
        <div>
            <span> 이름 </span> <input type="text" id="name" name="name" autocomplete="off" value=<?= $name ?>>
        </div>

        <div>
            <span> 나이 </span> <input type="text" id="age" name="age" autocomplete="off" value=<?= $age ?>>
        </div>

        <div>
            <label for="male">Male</label>
            <input type="radio" name="gender" id="male" value="M">

            <label for="female">Female</label>
            <input type="radio" name="gender" id="female" value="F">
        </div>

        <div>
            <input type="submit" value="저장">
        </div>
    </form>

    <script>
        <? if (isset($row['gender'])) : ?>
            <? if ($row['gender'] === 'M') : ?>
                document.getElementById('male').checked = true;
            <? else : ?>
                document.getElementById('female').checked = true;
            <? endif; ?>
        <? endif; ?>
    </script>
</body>

<script>
    //이름 : 특수문자 및 완성되지 않은 한글 입력제한
    var replaceChar = /[~!@\#$%^&*\()\-=+_'\;<>0-9\/.\`:\"\\,\[\]?|{}]/gi;
    var replaceNotFullKorean = /[ㄱ-ㅎㅏ-ㅣ]/gi;
    $(document).ready(function() {
        $("#name").on("focusout", function() {
            var x = $(this).val();
            if (x.length > 0) {
                if (x.match(replaceChar) || x.match(replaceNotFullKorean)) {
                    x = x.replace(replaceChar, "").replace(replaceNotFullKorean, "");
                }
                $(this).val(x);
            }
        }).on("keyup", function() {
            $(this).val($(this).val().replace(replaceChar, ""));
        });
    })
    
    //나이 : 숫자 외 입력제한
    var replaceNotInt = /[^0-9]/gi;
    $(document).ready(function() {
        $("#age").on("focusout", function() {
            var x = $(this).val();
            if (x.length > 0) {
                if (x.match(replaceNotInt)) {
                    x = x.replace(replaceNotInt, "");
                }
                $(this).val(x);
            }
        }).on("keyup", function() {
            $(this).val($(this).val().replace(replaceNotInt, ""));
        });
    });
</script>

</html>