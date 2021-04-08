<?php
include_once  __DIR__ . "/TextBoard.php";
include_once __DIR__ . "/FileBoardInterface.php";

class FileBoard extends TextBoard implements FileBoardIneterface
{
    // public function __call($name, $args)
    // {
    //     switch ($name) {
    //         case 'createPost':
    //             switch (count($args)) {
    //                 case 3:
    //                     return call_user_func_array(array($this, 'createTextPost'), $args);
    //                 case 4:
    //                     return call_user_func_array(array($this, 'createFilePost'), $args);
    //             }
    //         case 'setPost':
    //             switch (count($args)) {
    //                 case 3:
    //                     return call_user_func_array(array($this, 'setTextPost'), $args);
    //                 case 4:
    //                     return call_user_func_array(array($this, 'setFilePost'), $args);
    //             }
    //         case 'deletePost':
    //             return call_user_func_array(array($this, 'deleteFilePost'), $args);
    //     }
    // }

    public function createFilePost(string $ID, string $Title, string $Paragraph, array $file): bool
    {
        $file_id = $this->uploadFile($file);

        if ($file_id == false) :
            return $this->createTextPost($ID, $Title, $Paragraph);
        endif;

        return $this->createPost($ID, $Title, $Paragraph, $file_id);
    }

    public function setFilePost(int $TID, string $Title, string $Paragraph, array $file): bool
    {
        $file_id = $this->uploadFile($file);

        if ($file_id == false) :
            $file_id = NULL;
        endif;

        return $this->setPost($TID, $Title, $Paragraph, $file_id);
    }

    public function deleteFilePost(int $TID): bool
    {
        $fileResult = $this->getPost($TID);
        $exec = $this->deleteTextPost($TID);

        if (isset($fileResult['FileID'])) :
            $exec = $this->deleteFile($fileResult['FileID']);
        endif;

        return $exec;
    }

    public function clearFile(int $TID): bool
    {
        $fileResult = $this->getPost($TID);
        if (isset($fileResult['FileID'])) :
            $this->setPost($TID, $fileResult['Title'], $fileResult['Paragraph'], '');

            return $this->deleteFile($fileResult['FileID']);
        endif;

        return true;
    }

    public function downloadFile(string $FileID): bool
    {
        $row = $this->getFile($FileID);

        if (!isset($row)) {
            return false;
        }

        $name_orig = $row['name_orig'];
        $name_save = $row['name_save'];

        $uploads_dir = 'uploads/';
        $path = $uploads_dir . $name_save;
        $length = filesize($path);

        header("Content-Type: application/octet-stream");
        header("Content-Length: $length");
        header("Content-Disposition: attachment; filename=" . iconv('utf-8', 'euc-kr', $name_orig));
        header("Content-Transfer-Encoding: binary");

        $fh = fopen($path, "r");
        fpassthru($fh);

        return true;
    }

    public function getFile(string $FileID): array
    {
        $query = "SELECT * FROM file WHERE FileID = ?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "s", $FileID);
        $exec = mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    private function deleteFile(string $FileID): bool
    {
        $row = $this->getFile($FileID);

        $uploads_dir = 'uploads/';
        unlink($uploads_dir . $row['name_save']);

        $query = "DELETE FROM file WHERE FileID= ?";
        $stmt = mysqli_stmt_init($this->con);
        mysqli_stmt_prepare($stmt, $query);
        $bind = mysqli_stmt_bind_param($stmt, "s", $FileID);
        return mysqli_stmt_execute($stmt);
    }

    private function uploadFile(array $file)
    {
        if (empty($file)) :
            return false;
        endif;

        $uploads_dir = 'uploads/';
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

        $max_file_size = 5242880;
        $name = $_FILES['upfile']['name'];
        $ext = substr($file['name'], strrpos($file['name'], '.') + 1);

        if ((!in_array($ext, $allowed_ext)) || ($file['size'] >= $max_file_size)) :
            return false;
        endif;

        $path = md5(microtime()) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $uploads_dir . $path)) :
            $con = mysqli_connect("localhost", "root", "mysqlpassword", "guidb");
            $query = "INSERT INTO file(FileID, name_orig, name_save, reg_time) VALUES(?,?,?,now())";
            $file_id = md5(uniqid(rand(), true));
            $name_orig = $file['name'];
            $name_save = $path;

            $stmt = mysqli_prepare($con, $query);
            $bind = mysqli_stmt_bind_param($stmt, "sss", $file_id, $name_orig, $name_save);
            $exec = mysqli_stmt_execute($stmt);

            if (!$exec) :
                return false;
            endif;
        endif;

        return $file_id;
    }
}
