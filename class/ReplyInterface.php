<?php

interface ReplyInterface 
{
    function getReply(int $RID);
    function setReply(int $RID, string $Paragraph);
    function createReply(int $TID,string $ID,string $Paragraph);
    function deleteReply(int $RID);
}