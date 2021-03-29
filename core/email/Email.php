<?php


namespace Core\email;

use Core\utils\JsonParser;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    const EMAIL_CONFIG = ROOT_DIR . '/config/email.json';
   protected PHPMailer $email;

    public function __construct()
    {
        try {
            $this->configureEmail();
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    protected function configureEmail()
    {
        $this->email = new PHPMailer(true);
        $emailConfig = JsonParser::parseFile(self::EMAIL_CONFIG);
        if (isset($emailConfig['default']['timezone'])) {
            date_default_timezone_set($emailConfig['default']['timezone']);
        }

        $this->email->isSMTP();
        isset($emailConfig['default']['name']) ? $senderName = $emailConfig['default']['name']:$senderName = '';
        if (isset($_ENV['ENV']) && 'dev' === $_ENV['ENV'] && isset($emailConfig['phpmailer']['debug']) && true === $emailConfig['phpmailer']['debug']) {
            $this->email->SMTPDebug = SMTP::DEBUG_SERVER;
        } else {
            $this->email->SMTPDebug = SMTP::DEBUG_OFF;
        }
        $this->email->CharSet = PHPMailer::CHARSET_UTF8;
        if (isset($emailConfig['phpmailer']['SMTPSecure']) && $emailConfig['phpmailer']['SMTPSecure'] = "TLS") {
            $this->email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif (isset($emailConfig['phpmailer']['SMTPSecure']) && $emailConfig['phpmailer']['SMTPSecure'] = "SMTPS") {
            $this->email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $this->email->Host = $emailConfig['phpmailer']['host'];
        $this->email->Port = $emailConfig['phpmailer']['port'];
        $this->email->SMTPAuth = $emailConfig['phpmailer']['smtpAuthentication'];
        if (isset($emailConfig['phpmailer']['AuthType'])) {
            $this->email->AuthType = $emailConfig['phpmailer']['AuthType'];
        }

        if ($this->email->SMTPAuth) {
            if (preg_match('#\$_(ENV|SERVER)\[(\'|\")(.*?)(\'|\")]#', $emailConfig['phpmailer']['username'], $match)) {
                isset($_ENV[$match[3]]) ? $this->email->Username = $_ENV[$match[3]] : $parsedUrl = null;
            } else {
                $this->email->Username = $emailConfig['phpmailer']['username'];
            }

            if (preg_match('#\$_(ENV|SERVER)\[(\'|\")(.*?)(\'|\")]#', $emailConfig['phpmailer']['password'], $match)) {
                isset($_ENV[$match[3]]) ? $this->email->Password = $_ENV[$match[3]] : $parsedUrl = null;
            } else {
                $this->email->Password = $emailConfig['phpmailer']['password'];
            }
        }

        if (isset($emailConfig['default']['email'])) {
            try {
                if (preg_match('#\$_(ENV|SERVER)\[(\'|\")(.*?)(\'|\")]#', $emailConfig['default']['email'], $match)) {
                    isset($_ENV[$match[3]]) ? $this->email->setFrom($_ENV[$match[3]]) : $parsedUrl = null;
                } else {
                    $this->email->setFrom($emailConfig['default']['email'], $senderName);
                }
            } catch (Exception $e) {
                throw new Exception();
            }
        }
    }

    public function sender(string $email, string $name)
    {
        try {
            $this->email->setFrom($email, $name);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    public function addReceiver(string $receiver)
    {
        $this->email->addAddress($receiver);
    }

    public function addReplyTo(string $address)
    {
        $this->email->addReplyTo($address);
    }

    public function subject(string $subject)
    {
        $this->email->Subject = $subject;
    }

    public function setContent($content)
    {
        $this->email->msgHTML($content);
    }

    public function send(): bool
    {
        //send the message, check for errors
        if (!$this->email->send()) {
            return $this->email->ErrorInfo;
        } else {
            return true;
        }
    }
}