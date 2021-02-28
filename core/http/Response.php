<?php


namespace App\Core\Http;


class Response
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $statusCode;

    public function __construct(?string $content = '', int $status = 200)
    {
        $this->setContent($content);
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): Response
    {
        $this->content = $content ?? '';

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setStatusCode(int $code): object
    {
        $this->statusCode = $code;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function send(): Response
    {
        $this->sendContent();

        return $this;
    }

    public function sendContent(): Response
    {
        echo $this->content;

        return $this;
    }
}
