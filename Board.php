<?php

class Board {
    private $con;

    public function __construct()
    {
        $this->con = mysqli_connect("localhost", "root", "mysqlpassword", "guidb");

        if (mysqli_connect_errno()) :
            echo "DB con failed <br>";
            exit;
        endif;
    }

    public function createPost($ID,$Title,$Paragraph,$FileID="") : bool
    {
        $query = "INSERT INTO board(ID,Title,Paragraph,FileID) VALUES(?,?,?,?)";
        $stmt = mysqli_prepare($this->con, $query);

        if($FileID === "") :
            $FileID = null;
        endif;

        $bind = mysqli_stmt_bind_param($stmt, "ssss", $ID,$Title,$Paragraph,$FileID);
        $exec = mysqli_stmt_execute($stmt);


        return $exec;
    }

    public function getPost($TID) : array
    {
        $query = "SELECT * FROM board WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

    public function getallPosts() : mysqli_result
    {
        $query = "SELECT * FROM board ORDER BY CreatedDate DESC";
        $stmt = mysqli_prepare($this->con, $query);

        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }

    public function setPost($TID,$Title,$Paragraph,$FileID="") : bool
    {
        $query = "UPDATE board SET Title=?,Paragraph=?,FileID=? WHERE TID=?";
        $stmt = mysqli_prepare($this->con,$query);

        if($FileID == "") :
            $FileID = null;
        endif;

        $bind = mysqli_stmt_bind_param($stmt, "sssi", $Title,$Paragraph,$FileID,$TID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    public function deletePost($TID) : bool
    {
        $fileResult = $this->getPost($TID);

        $query = "DELETE FROM board WHERE TID=?";
        $stmt = mysqli_prepare($this->con,$query);
        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);

        if(isset($fileResult['FileID'])) :
            $query = "SELECT * FROM file WHERE FileID=?";
            $stmt = mysqli_stmt_init($this->con);
            mysqli_stmt_prepare($stmt,$query);
            $bind = mysqli_stmt_bind_param($stmt, "s", $fileResult['FileID']);
            
            $exec = mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            
            $query = "DELETE FROM file WHERE FileID=?";
            $stmt = mysqli_stmt_init($this->con);
            mysqli_stmt_prepare($stmt,$query);
            
            $bind = mysqli_stmt_bind_param($stmt, "s", $fileResult['FileID']);
            $exec = mysqli_stmt_execute($stmt);

            if(!$exec) {
                var_dump($stmt->error);
                exit;
            }

            $uploads_dir = 'uploads/';
            unlink($uploads_dir.$row['name_save']);
        endif;

        return $exec;
    }

    public function deleteFile($TID) : bool
    {
        $fileResult = $this->getPost($TID);

        if(isset($fileResult['FileID'])) :
            $query = "UPDATE board SET FileID = NULL WHERE TID=?";
            $stmt = mysqli_stmt_init($this->con);
            mysqli_stmt_prepare($stmt,$query);
            $bind = mysqli_stmt_bind_param($stmt,"i",$TID);
            $exec = mysqli_stmt_execute($stmt);

            $query = "SELECT * FROM file WHERE FileID= ?";
            $stmt = mysqli_prepare($this->con,$query);
            $bind = mysqli_stmt_bind_param($stmt, "s", $fileResult['FileID']);
            $exec = mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            $uploads_dir = 'uploads/';
            unlink($uploads_dir.$row['name_save']);

            $query = "DELETE FROM file WHERE FileID= ?";
            $stmt = mysqli_stmt_init($this->con);
            mysqli_stmt_prepare($stmt,$query);
            $bind = mysqli_stmt_bind_param($stmt, "s", $fileResult['FileID']);
            $exec = mysqli_stmt_execute($stmt);

            if(!$exec) 
                return false;
        endif;

        return true;
    }

    public function getFileName($FileID) : string
    {
        $query = "SELECT name_orig FROM file WHERE FileID=?";
        $stmt = mysqli_prepare($this->con, $query);
        
        $bind = mysqli_stmt_bind_param($stmt,"s",$FileID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) == 0) :
            return false;
        endif;

        $row = mysqli_fetch_assoc($result);
        return $row['name_orig'];
    }

    public function searchPosts($keyword,$Type="Title") : mysqli_result
    {
        $keyword = "%".$keyword."%";
        
        if($Type === "all") :
            $query = "SELECT * FROM board WHERE Title LIKE ? OR Paragraph like ? ORDER BY CreatedDate DESC";
            $stmt = mysqli_prepare($this->con,$query);
            $bind = mysqli_stmt_bind_param($stmt, "ss", $keyword,$keyword);
        elseif($Type === "Paragraph") :
            $query = "SELECT * FROM board WHERE Paragraph LIKE ? ORDER BY CreatedDate DESC";
            $stmt = mysqli_prepare($this->con,$query);
            $bind = mysqli_stmt_bind_param($stmt,"s",$keyword);
        else:
            $query = "SELECT * FROM board WHERE Title LIKE ? ORDER BY CreatedDate DESC";
            $stmt = mysqli_prepare($this->con,$query);
            $bind = mysqli_stmt_bind_param($stmt,"s",$keyword);
        endif;
        
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }
}