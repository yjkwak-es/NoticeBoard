<?php
include_once __DIR__ . "/ReplyInterface.php";

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

    public function getReply(int $RID): array
    {
        $query = "SELECT * FROM Reply WHERE RID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $RID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }

    public function setReply(int $RID, string $Paragraph): bool
    {
        $query = "UPDATE Reply SET Paragraph = ? WHERE RID = ?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "si", $Paragraph, $RID);
        return mysqli_stmt_execute($stmt);
    }

    public function getallReplysAtPost(int $TID): array
    {
        $query = "SELECT * FROM Reply WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return [
            'result' => $result,
            'totalCount' => mysqli_num_rows($result)
        ];
    }

    public function createReply(int $TID, string $ID, string $Paragraph): bool
    {
        $query = "INSERT INTO Reply(TID,ID,Paragraph) VALUES(?,?,?)";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "iss", $TID, $ID, $Paragraph);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteReply(int $RID): bool
    {
        $query = "DELETE FROM Reply WHERE RID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $RID);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteAllReplyAtPosts(int $TID): bool
    {
        $query = "DELETE FROM Reply WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        return mysqli_stmt_execute($stmt);
    }
}
