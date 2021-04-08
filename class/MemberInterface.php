<?php
interface MemberInterface
{
    function getMemberMy();
    function setMember(string $name, int $age, string $gender);
    function getID();
    function setID(string $id);
}
