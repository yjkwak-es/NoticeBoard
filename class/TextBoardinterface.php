<?php

interface TextBoardInterface
{
    // function createTextPost(string $ID,string $Title, string $Paragraph);
    function getPost(int $TID);
    function getallPosts();
    // function setTextPost(int $TID, string $Title, string $Paragraph);
    // function deleteTextPost(int $TID);
    function searchPosts(string $keyword, string $type = "Title");
}
