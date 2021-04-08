<?php

interface TextBoardInterface
{
    function createTextPost(string $ID,string $Title, string $Paragraph);
    function searchPosts(string $keyword, string $type = "Title");
    function deleteTextPost(int $TID);
    function getPost(int $TID);
    function getallPosts();
}