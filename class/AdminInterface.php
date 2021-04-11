<?php
interface AdminInterface
{
    function getallMembers();
    function getMemberID(string $id);
    function createMember(string $id, string $pw);
}
