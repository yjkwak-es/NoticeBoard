<?php
interface AdminInterface
{
    function getallMembers();
    function getMemberID($id);
    function createMember($id, $pw);
}