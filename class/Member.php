<?php
include_once __DIR__ . "/MemberInterface.php";

class Member implements MemberInterface
{
    protected string $id;
    protected $con;

    public function __construct()
    {
        $this->id = "";
        $this->con = mysqli_connect("localhost", "root", "mysqlpassword", "guidb");

        if (mysqli_connect_errno()) :
            echo "DB con failed <br>";
            exit;
        endif;
    }

    public function getMemberMy()
    {
        return $this->getMember($this->id);
    }

    public function setMember(string $name, int $age, string $gender)
    {
        $query = "UPDATE test_db SET name=?, age=?, gender=? WHERE ID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "siss", $name, $age, $gender, $this->id);
        $exec = mysqli_stmt_execute($stmt);
        return $exec;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID(string $id)
    {
        $this->id = $id;
    }

    protected function getMember($id)
    {
        $query = "SELECT * FROM test_db WHERE ID=?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "s", $id);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        return $row;
    }
}
