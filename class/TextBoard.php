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

    public function createTextPost(string $ID, string $Title, string $Paragraph): bool
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
        $stmt = mysqli_prepare($this->con, $query);
        $bind = mysqli_stmt_bind_param($stmt, "i", $TID);
        return mysqli_stmt_execute($stmt);
    }

    public function searchPosts(int $start, int $offset, string $keyword = '', string $Type = '') : array
    {
        if (isset($keyword)) {
            $keyword = "%" . $keyword . "%";
        }

        switch ($Type) {
            case 'all':
                $query = "SELECT board.*,c.* FROM board,(
                    SELECT COUNT(*) AS count FROM board WHERE Title LIKE ? OR Paragraph like ? 
                ) AS c 
                WHERE Title LIKE ? OR Paragraph like ? ORDER BY CreatedDate DESC LIMIT ?,?";
                $stmt = mysqli_prepare($this->con, $query);
                $bind = mysqli_stmt_bind_param($stmt, "ssssii", $keyword, $keyword, $keyword, $keyword, $start, $offset);
                break;

            case 'Paragraph':
                $query = "SELECT board.*,c.* FROM board,(
                    SELECT COUNT(*) AS count FROM board WHERE Paragraph LIKE ? 
                ) AS c
                WHERE Paragraph LIKE ? ORDER BY CreatedDate DESC LIMIT ?,?";
                $stmt = mysqli_prepare($this->con, $query);
                $bind = mysqli_stmt_bind_param($stmt, "ssii", $keyword, $keyword, $start, $offset);
                break;

            case 'Title':
                $query = "SELECT board.*,c.* FROM board,(
                    SELECT COUNT(*) AS count FROM board WHERE Title LIKE ? 
                ) AS c
                 WHERE Title LIKE ? ORDER BY CreatedDate DESC LIMIT ?,?";
                $stmt = mysqli_prepare($this->con, $query);
                $bind = mysqli_stmt_bind_param($stmt, "ssii", $keyword, $keyword,$start, $offset);
                break;

            default:
                $query = "SELECT board.*,c.* FROM board,(
                    SELECT COUNT(*) AS count FROM board
                ) AS c
                ORDER BY CreatedDate DESC LIMIT ?,?";
                $stmt = mysqli_prepare($this->con, $query);
                $bind = mysqli_stmt_bind_param($stmt, "ii", $start, $offset);
        }

        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $cnt = mysqli_num_rows($result);

        return [
            'posts' => $result,
            'totalCount' => $cnt       // TODO: 전체행 갯수 가져오기
        ];
    }


    protected function createPost(string $ID, string $Title, string $Paragraph, $FileID)
    {
        $query = "INSERT INTO board(ID,Title,Paragraph,FileID) VALUES(?,?,?,?)";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "ssss", $ID, $Title, $Paragraph, $FileID);
        return mysqli_stmt_execute($stmt);
    }

    protected function setPost(int $TID, string $Title, string $Paragraph, string $FileID)
    {
        if (empty($FileID)) {
            $FileID = null;
        }

        $query = "UPDATE board SET Title=?,Paragraph=?,FileID=? WHERE TID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "sssi", $Title, $Paragraph, $FileID, $TID);
        return mysqli_stmt_execute($stmt);
    }
}
