<?php
include_once __DIR__ . "/TextBoardinterface.php";

interface FileBoardIneterface
{
    function createFilePost(string $ID, string $Title, string $Paragraph, array $file);
    function setFilePost(int $TID, string $Title, string $Paragraph, array $file);
    function deleteFilePost(int $TID);
    function getFile(string $FileID);
    function clearFile(int $TID);
    function downloadFile(string $FileID);
}
