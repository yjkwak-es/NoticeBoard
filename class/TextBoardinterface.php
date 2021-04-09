<?php

interface TextBoardInterface
{
    function createTextPost(string $ID,string $Title, string $Paragraph);
    function deleteTextPost(int $TID);
    function getPost(int $TID);
    function getallPosts();
}