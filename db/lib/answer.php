<?php
class Answer
{
    function __construct()
    {
        $this->content = null;
        $this->status = "~Update";
    }

    function encode()
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }

    function setContent($data)
    {
        $this->content = $data;
    }

    function addData($key, $d)
    {
        $this->{$key} = $d;
    }
}
?>