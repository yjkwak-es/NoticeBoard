<?php
include_once "/ESGROUP/PHPSever/test/class/ReplyInterface.php";

class Reply implements ReplyInterface
{
    private $con;

    public function __construct()
    {
        $this->con = mysqli_connect("localhost", "root", "mysqlpassword", "guidb");

        if (mysqli_connect_errno()) :
            echo "DB con failed <br>";
            exit;
        endif;
    }

    public function getReply($RID): array
    {
        $query = "SELECT * FROM Reply WHERE RID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $RID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

    public function setReply($RID, $Paragraph): bool
    {
        $query = "UPDATE Reply SET Paragraph = ? WHERE RID = ?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "si", $Paragraph, $RID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    public function getallReplysAtPost($TID): mysqli_result
    {
        $query = "SELECT * FROM Reply WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }

    public function createReply($TID, $ID, $Paragraph): bool
    {
        $query = "INSERT INTO Reply(TID,ID,Paragraph) VALUES(?,?,?)";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "iss", $TID, $ID, $Paragraph);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    public function deleteReply($RID): bool
    {
        $query = "DELETE FROM Reply WHERE RID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $RID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }

    public function deleteAllReplyAtPosts($TID): bool
    {
        $query = "DELETE FROM Reply WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }
}
