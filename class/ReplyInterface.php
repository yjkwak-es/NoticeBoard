<?php

interface ReplyInterface 
{
    function getReply(int $RID);
    function setReply(int $RID, string $Paragraph);
    function getallReplysAtPost(int $TID);
    function createReply(int $TID,string $ID,string $Paragraph);
    function deleteReply(int $RID);
    function deleteAllReplyAtPosts(int $TID);
}