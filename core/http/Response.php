<?php


namespace App\Core\Http;


class Response
{
    protected $content;

    public function setContent(string $content)
    {
        $this->content = $content ?? '';
    }

    public function send()
    {
        $this->sendContent();

        return $this;
    }

    public function sendContent()
    {
        echo $this->content;

        return $this;
    }
}
