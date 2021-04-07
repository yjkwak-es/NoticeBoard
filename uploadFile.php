<?php
include_once "/ESGROUP/PHPSever/test/class/FileBoard.php";
$result = false;
if (isset($_FILES['upfile']) && $_FILES['upfile']['name'] != "") :
    $file = $_FILES['upfile'];

    $board = new FileBoard();
    $result = $board->uploadFile($file);
endif;

if ($result) :
?>
    <script>
        alert("파일 업로드 완료!")
    </script>
<?php
else :
?>
    <script>
        alert("파일 업로드 실패")
        history.go(-1)
    </script>
<?php
endif;

    // $uploads_dir = 'uploads/';
    // $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    // $max_file_size = 5242880;
    // $name = $_FILES['upfile']['name'];
    // $ext = substr($file['name'], strrpos($file['name'], '.') + 1);

    // if (!in_array($ext, $allowed_ext)) :
    //     echo "허용되지 않는 확장자입니다.";
    //     exit;
    // endif;

    // if ($file['size'] >= $max_file_size) :
    //     echo "5MB까지만 업로드 가능합니다.";
    // endif;



    // $path = md5(microtime()) . '.' . $ext;
    // if (move_uploaded_file($file['tmp_name'], $uploads_dir.$path)) :
    //     $con = mysqli_connect("localhost","root","mysqlpassword","guidb");
    //     $query = "INSERT INTO file(FileID, name_orig, name_save, reg_time) VALUES(?,?,?,now())";
    //     $file_id = md5(uniqid(rand(), true));
    //     $name_orig = $file['name'];
    //     $name_save = $path;

    //     $stmt = mysqli_prepare($con, $query);
    //     $bind = mysqli_stmt_bind_param($stmt, "sss", $file_id, $name_orig, $name_save);
    //     $exec = mysqli_stmt_execute($stmt);
