<?php

include_once "/ESGROUP/PHPSever/test/class/Member.php";
include_once "/ESGROUP/PHPSever/test/class/AdminInterface.php";

class Admin extends Member implements AdminInterface
{
    public function __call($name, $args)
    {
        switch ($name) {
            case 'getMember':
                switch (count($args)) {
                    case 0:
                        return call_user_func_array(array($this, 'getMemberMy'), $args);
                    case 1:
                        return call_user_func_array(array($this, 'getMemberID'), $args);
                }
        }
    }

    public function __construct()
    {
        parent::__construct();

        $query = "SELECT ID FROM test_db WHERE admin=1";
        $stmt = mysqli_prepare($this->con, $query);

        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_row($result);

        $this->id = $row[0];
    }

    public function getallMembers(): mysqli_result
    {
        $query = "SELECT * FROM test_db";
        $stmt = mysqli_prepare($this->con, $query);

        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }

    public function getMemberID($id)
    {
        return $this->getMember($id);
    }

    public function createMember($id, $pw)
    {
        $query = "SELECT COUNT(*) FROM test_db WHERE ID = ?";
        $stmt = mysqli_prepare($this->con, $query);

        $bind = mysqli_stmt_bind_param($stmt, "s", $id);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result->fetch_row();
        if ($row[0] != 0) {
            return $result;
        }

        $query = "INSERT INTO test_db(ID,PW,admin) VALUES(?,?,0)";
        mysqli_stmt_prepare($stmt, $query);

        $bind = mysqli_stmt_bind_param($stmt, "ss", $id, $pw);
        $exec = mysqli_stmt_execute($stmt);

        return $exec;
    }
}
