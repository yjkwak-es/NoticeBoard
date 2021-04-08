<?php
include_once __DIR__."/ReplyInterface.php";

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
        
        return mysqli_fetch_assoc($result);
    }

    public function setReply($RID, $Paragraph): bool
    {
        $query = "UPDATE Reply SET Paragraph = ? WHERE RID = ?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "si", $Paragraph, $RID);
        return mysqli_stmt_execute($stmt);
    }

    public function getallReplysAtPost($TID): mysqli_result
    {
        $query = "SELECT * FROM Reply WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function createReply($TID, $ID, $Paragraph): bool
    {
        $query = "INSERT INTO Reply(TID,ID,Paragraph) VALUES(?,?,?)";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "iss", $TID, $ID, $Paragraph);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteReply($RID): bool
    {
        $query = "DELETE FROM Reply WHERE RID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $RID);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteAllReplyAtPosts($TID): bool
    {
        $query = "DELETE FROM Reply WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        return mysqli_stmt_execute($stmt);
    }
}
