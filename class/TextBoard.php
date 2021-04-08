<?php
include_once __DIR__ . "/TextBoardinterface.php";
include_once __DIR__ . "/Reply.php";

class TextBoard implements TextBoardInterface
{
    protected $con;

    public function __construct()
    {
        $this->con = mysqli_connect("localhost", "root", "mysqlpassword", "guidb");

        if (mysqli_connect_errno()) :
            echo "DB con failed <br>";
            exit;
        endif;
    }

    // public function __call($name, $args)
    // {
    //     switch ($name) {
    //         case 'createPost':
    //             return call_user_func_array(array($this, 'createTextPost'), $args);
    //         case 'setPost':
    //             return call_user_func_array(array($this, 'setTextPost'), $args);
    //         case 'deletePost':
    //             return call_user_func_array(array($this, 'deleteTextPost'), $args);
    //     }
    // }

    public function createTextPost($ID, $Title, $Paragraph): bool
    {
        return $this->createPost($ID, $Title, $Paragraph, '');
    }

    public function getPost(int $TID): array
    {
        $query = "SELECT * FROM board WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function getallPosts(): mysqli_result
    {
        $query = "SELECT * FROM board ORDER BY CreatedDate DESC";
        $stmt = mysqli_prepare($this->con, $query);

        $exec = mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function setTextPost(int $TID, string $Title, string $Paragraph): bool
    {
        return $this->setPost($TID, $Title, $Paragraph, '');
    }

    public function deleteTextPost(int $TID): bool
    {
        $reply = new Reply();
        $reply->deleteAllReplyAtPosts($TID);

        $query = "DELETE FROM board WHERE TID=?";
        $stmt = mysqli_stmt_init($this->con);
        mysqli_stmt_prepare($stmt, $query);
        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        return mysqli_stmt_execute($stmt);
    }

    public function searchPosts(string $keyword, string $Type = "Title"): mysqli_result
    {
        $keyword = "%" . $keyword . "%";

        if ($Type === "all") :
            $query = "SELECT * FROM board WHERE Title LIKE ? OR Paragraph like ? ORDER BY CreatedDate DESC";
            $stmt = mysqli_prepare($this->con, $query);
            $bind = mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
        elseif ($Type === "Paragraph") :
            $query = "SELECT * FROM board WHERE Paragraph LIKE ? ORDER BY CreatedDate DESC";
            $stmt = mysqli_prepare($this->con, $query);
            $bind = mysqli_stmt_bind_param($stmt, "s", $keyword);
        else :
            $query = "SELECT * FROM board WHERE Title LIKE ? ORDER BY CreatedDate DESC";
            $stmt = mysqli_prepare($this->con, $query);
            $bind = mysqli_stmt_bind_param($stmt, "s", $keyword);
        endif;

        $exec = mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    protected function createPost(string $ID, string $Title, string $Paragraph, string $FileID)
    {
        if(empty($FileID)) {
            $FileID = null;
        }

        $query = "INSERT INTO board(ID,Title,Paragraph,FileID) VALUES(?,?,?,?)";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "ssss", $ID, $Title, $Paragraph, $FileID);
        return mysqli_stmt_execute($stmt);
    }

    protected function setPost(int $TID, string $Title, string $Paragraph, string $FileID)
    {
        if(empty($FileID)) {
            $FileID = null;
        }
        
        $query = "UPDATE board SET Title=?,Paragraph=?,FileID=? WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "sssi", $Title, $Paragraph, $FileID, $TID);
        return mysqli_stmt_execute($stmt);
    }
}
