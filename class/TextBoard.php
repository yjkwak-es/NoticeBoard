<?php
include_once "/ESGROUP/PHPSever/test/class/TextBoardinterface.php";
include_once "/ESGROUP/PHPSever/test/class/Reply.php";

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

    public function __call($name, $args)
    {
        switch ($name) {
            case 'createPost':
                return call_user_func_array(array($this, 'createTextPost'), $args);
            case 'setPost':
                return call_user_func_array(array($this, 'setTextPost'), $args);
            case 'deletePost':
                return call_user_func_array(array($this, 'deleteTextPost'), $args);
        }
    }

    protected function createTextPost($ID, $Title, $Paragraph): bool
    {
        $query = "INSERT INTO board(ID,Title,Paragraph,FileID) VALUES(?,?,?,null)";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "sss", $ID, $Title, $Paragraph);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    public function getPost(int $TID): array
    {
        $query = "SELECT * FROM board WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

    public function getallPosts(): mysqli_result
    {
        $query = "SELECT * FROM board ORDER BY CreatedDate DESC";
        $stmt = mysqli_prepare($this->con, $query);

        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }

    protected function setTextPost($TID, $Title, $Paragraph): bool
    {
        $query = "UPDATE board SET Title=?,Paragraph=? WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "ssi", $Title, $Paragraph, $TID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    protected function deleteTextPost(int $TID): bool
    {
        $reply = new Reply();
        $reply->deleteAllReplyAtPosts($TID);

        $query = "DELETE FROM board WHERE TID=?";
        $stmt = mysqli_stmt_init($this->con);
        mysqli_stmt_prepare($stmt, $query);
        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        return $exec;
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
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }
}
