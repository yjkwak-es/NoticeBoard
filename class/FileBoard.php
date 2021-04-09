<?php
include_once  __DIR__ . "/TextBoard.php";
include_once __DIR__ . "/FileBoardInterface.php";

class FileBoard extends TextBoard implements FileBoardIneterface
{
    public function createFilePost(string $ID, string $Title, string $Paragraph, array $file): bool
    {
        $file_id = $this->uploadFile($file);
        return $this->createPost($ID, $Title, $Paragraph, $file_id);
    }

    public function setFilePost(int $TID, string $Title, string $Paragraph, array $file): bool
    {
        $file_id = $this->uploadFile($file);
        return $this->setPost($TID, $Title, $Paragraph, $file_id);
    }

    public function deleteFilePost(int $TID): bool
    {
        $fileResult = $this->getPost($TID);
        $execPost = $this->deleteTextPost($TID);

        if (!empty($fileResult['FileID'])) :
            $execFile = $this->deleteFile($fileResult['FileID']);
        endif;

        return $execPost && $execFile;
    }

    public function clearFile(int $TID): bool
    {
        $fileResult = $this->getPost($TID);
        
        if (empty($fileResult['FileID'])) :
            return true;
        endif;
        
        $this->setPost($TID, $fileResult['Title'], $fileResult['Paragraph'], '');
        return $this->deleteFile($fileResult['FileID']);
    }

    public function downloadFile(string $FileID): bool
    {
        $row = $this->getFile($FileID);

        if (empty($row)) {
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

    private function uploadFile(array $file): string
    {
        if (empty($file)) :
            return NULL;
        endif;

        $uploads_dir = 'uploads/';
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

        $max_file_size = 5242880;
        $name = $_FILES['upfile']['name'];
        $ext = substr($file['name'], strrpos($file['name'], '.') + 1);

        if ((!in_array($ext, $allowed_ext)) || ($file['size'] >= $max_file_size)) :
            return NULL;
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
                return NULL;
            endif;
        endif;

        return $file_id;
    }
}
