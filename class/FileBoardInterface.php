<?php
include_once "/ESGROUP/PHPSever/test/class/TextBoardinterface.php";

interface FileBoardIneterface extends TextBoardInterface
{
    // function createFilePost(string $ID, string $Title, string $Paragraph, array $file);
    // function setFilePost(int $TID, string $Title, string $Paragraph, string $FileID);
    // function deleteFilePost(int $TID);
    function deleteFile(int $TID);
    function downloadFile(string $FileID);
    function getFileName(string $FileID);
}