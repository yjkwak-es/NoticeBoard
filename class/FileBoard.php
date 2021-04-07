<?php
include_once "/ESGROUP/PHPSever/test/class/TextBoard.php";
include_once "/ESGROUP/PHPSever/test/class/FileBoardInterface.php";

class FileBoard extends TextBoard implements FileBoardIneterface
{
    public function __call($name, $args)
    {
        switch ($name) {
            case 'createPost':
                switch (count($args)) {
                    case 3:
                        return call_user_func_array(array($this, 'createTextPost'), $args);
                    case 4:
                        return call_user_func_array(array($this, 'createFilePost'), $args);
                }
            case 'setPost':
                switch (count($args)) {
                    case 3:
                        return call_user_func_array(array($this, 'setTextPost'), $args);
                    case 4:
                        return call_user_func_array(array($this, 'setFilePost'), $args);
                }
            case 'deletePost':
                return call_user_func_array(array($this, 'deleteFilePost'), $args);
        }
    }

    protected function createFilePost(string $ID, string $Title, string $Paragraph, array $file): bool
    {
        $file_id = $this->uploadFile($file);

        if ($file_id == false) :
            $file_id = NULL;
        endif;

        $query = "INSERT INTO board(ID,Title,Paragraph,FileID) VALUES(?,?,?,?)";
        $stmt = mysqli_stmt_init($this->con);
        mysqli_stmt_prepare($stmt, $query);

        $bind = mysqli_stmt_bind_param($stmt, "ssss", $ID, $Title, $Paragraph, $file_id);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    protected function setFilePost(int $TID, string $Title, string $Paragraph, array $file): bool
    {
        $file_id = $this->uploadFile($file);

        if ($file_id == false) :
            $file_id = NULL;
        endif;

        $query = "UPDATE board SET Title=?,Paragraph=?,FileID=? WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "sssi", $Title, $Paragraph, $file_id, $TID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    protected function deleteFilePost(int $TID): bool
    {
        $fileResult = $this->getPost($TID);
        $exec = parent::deleteTextPost($TID);

        if (isset($fileResult['FileID'])) :
            $exec = $this->clearFile($fileResult['FileID']);
        endif;

        return $exec;
    }

    public function deleteFile(int $TID): bool
    {
        $fileResult = $this->getPost($TID);
        $exec = true;

        if (isset($fileResult['FileID'])) :
            $query = "UPDATE board SET FileID = NULL WHERE TID=?";
            $stmt = mysqli_stmt_init($this->con);
            mysqli_stmt_prepare($stmt, $query);
            $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
            $exec = mysqli_stmt_execute($stmt);

            $exec = $this->clearFile($fileResult['FileID']);
        endif;

        return $exec;
    }

    public function downloadFile(string $FileID)
    {
        $query = "SELECT * FROM file WHERE FileID = ?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "s", $file_id);
        $exec = mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

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
    }

    public function getFileName(string $FileID): string
    {
        $query = "SELECT name_orig FROM file WHERE FileID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "s", $FileID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) :
            return false;
        endif;

        $row = mysqli_fetch_assoc($result);
        return $row['name_orig'];
    }

    private function clearFile(string $FileID)
    {
        $query = "SELECT * FROM file WHERE FileID= ?";
        $stmt = mysqli_prepare($this->con, $query);
        $bind = mysqli_stmt_bind_param($stmt, "s", $FileID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        $uploads_dir = 'uploads/';
        unlink($uploads_dir . $row['name_save']);

        $query = "DELETE FROM file WHERE FileID= ?";
        $stmt = mysqli_stmt_init($this->con);
        mysqli_stmt_prepare($stmt, $query);
        $bind = mysqli_stmt_bind_param($stmt, "s", $FileID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    private function uploadFile(array $file)
    {
        if (!isset($file)) :
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
