<?php


namespace Core\http;


class Response
{
    /**
     * @var string
     */
    protected string $content;

    /**
     * @var int
     */
    protected int $statusCode;

    /**
     * @var string
     */
    protected string $statusText;

    public function __construct(?string $content = '', int $status = 200)
    {
        $this->setContent($content);
        $this->setStatusCode($status);
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

    /**
     * @param string $content
     * @return $this
     */
    public function setJsonContent(array $content, int $status = 200): Response
    {
        $this->setStatusCode($status);
        $this->content = json_encode($content);

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param int $code
     * @param null $statusText
     * @return object
     */
    public function setStatusCode(int $code, $statusText = null): object
    {
        $this->statusCode = $code;

        if (!empty($statusText)) {
            $this->statusText = $statusText;
        } elseif (isset(StatustTexts::TEXTS[$code])) {
            $this->statusText = StatustTexts::TEXTS[$code];
        } else {
            $this->statusText = 'Unknown Status';
        }

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function send(): Response
    {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }

    public function sendContent(): Response
    {
        echo $this->content;

        return $this;
    }

    /**
     * @return $this
     */
    public function sendHeaders(): Response
    {
        if (headers_sent()) {
            return $this;
        }
        header(sprintf('HTTP/1.1  %s %s', $this->statusCode, $this->statusText), true, $this->statusCode);
        return $this;
    }
}
