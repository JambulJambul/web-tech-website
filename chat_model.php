<?php

declare(strict_types=1);

class Chatroom {
    public $chatList;
    public $lastModified;
    private $userID1;
    private $userID2;

    public function __construct($jsonObject, $id1, $id2, $lastModified)
    {
        $this->userID1 = $id1;
        $this->userID2 = $id2;
        $this->lastModified = $lastModified;
        $this->chatList = array();

        $ls = $jsonObject["content"];
        foreach ($ls as $content) {
            $fl = (!isset($content["is_file"])) ? false : $content["is_file"];
            $path = ($fl === false) ? null : $content["path"];

            $chat = new Chat($content["sender"], $content["message"], $content["time"], $fl, $path);
            array_push($this->chatList, $chat);
        }
    }

    public function toJsonObject() {
        $content = array();

        foreach ($this->chatList as $chat) {
            $data["sender"] = $chat->senderID;
            $data["message"] = $chat->message;
            $data["time"] = $chat->timestamp;

            if ($chat->isFile) {
                $data["is_file"] = $chat->isFile;
                $data["path"] = $chat->path;
            }

            array_push($content, $data);

            if ($chat->isFile) {
                unset($data["is_file"]);
                unset($data["path"]);
            }
        }

        return [
            "content" => $content
        ];
    }
}

class Chat {
    public $senderID;
    public $message;
    public $timestamp;
    public $isFile;
    public $path;

    public function __construct($id, $message, $time, $file, $path)
    {
        $this->senderID = $id;
        $this->message = $message;
        $this->timestamp = $time;
        $this->isFile = $file;
        $this->path = $path;
    }
}