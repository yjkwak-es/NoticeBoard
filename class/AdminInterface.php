<?php
include_once "/ESGROUP/PHPSever/test/class/MemberInterface.php";
interface AdminInterface
{
    function getallMembers();
    // function getMemberID($id);
    function createMember($id, $pw);
}