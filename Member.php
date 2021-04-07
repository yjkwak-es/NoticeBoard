<?php
// class Member
// {
//     private $con;

//     public function __construct()
//     {
//         $this->con = mysqli_connect("localhost", "root", "mysqlpassword", "guidb");

//         if (mysqli_connect_errno()) :
//             echo "DB con failed <br>";
//             exit;
//         endif;
//     }

//     public function getMember($id): array 
//     {
//         $query = "SELECT * FROM test_db WHERE ID=?";
//         $stmt = mysqli_prepare($this->con, $query);

//         $bind = mysqli_stmt_bind_param($stmt, "s", $id);
//         $exec = mysqli_stmt_execute($stmt);
//         $result = mysqli_stmt_get_result($stmt);
//         $row = mysqli_fetch_assoc($result);

//         return $row;
//     }

//     public function getallMembers(): mysqli_result
//     {
//         $query = "SELECT * FROM test_db";
//         $stmt = mysqli_prepare($this->con, $query);

//         $exec = mysqli_stmt_execute($stmt);
//         $result = mysqli_stmt_get_result($stmt);

//         return $result;
//     }

//     public function createMember($id, $pw)
//     {
//         $query = "SELECT COUNT(*) FROM test_db WHERE ID = ?";
//         $stmt = mysqli_prepare($this->con, $query);

//         $bind = mysqli_stmt_bind_param($stmt, "s", $id);
//         $exec = mysqli_stmt_execute($stmt);
//         $result = mysqli_stmt_get_result($stmt);
//         $row = $result->fetch_row();
//         if ($row[0] != 0) {
//             return $result;
//         }

//         $query = "INSERT INTO test_db(ID,PW,admin) VALUES(?,?,0)";
//         mysqli_stmt_prepare($stmt,$query);

//         $bind = mysqli_stmt_bind_param($stmt, "ss", $id,$pw);
//         $exec = mysqli_stmt_execute($stmt);

//         return $exec;
//     }

//     public function setMember($id, $pw): bool
//     {
//         $query = "UPDATE test_db SET PW=? WHERE ID=?";
//         $stmt = mysqli_prepare($this->con, $query);

//         $bind = mysqli_stmt_bind_param($stmt, "ss", $id, $pw);
//         $exec = mysqli_stmt_execute($stmt);

//         return $exec;
//     }
// }