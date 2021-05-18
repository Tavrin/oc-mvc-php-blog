<?php


namespace Core\email;

use Core\utils\JsonParser;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Email
{
    protected const TEMPLATES_DIR = ROOT_DIR . '/templates/';
    protected const ENV_REGEX = '#\$_(ENV|SERVER)\[(\'|\")(.*?)(\'|\")]#';
    protected const EMAIL_CONFIG = ROOT_DIR . '/config/email.json';

    protected PHPMailer $email;
    protected Environment $twig;


    /**
     * Email constructor.
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->configureEmail();
        } catch (Exception $e) {
            throw new Exception();
        }

        $loader = new FilesystemLoader(self::TEMPLATES_DIR);
        $this->twig = new Environment($loader, [
            'debug' => false
        ]);
    }

    /**
     * @throws Exception
     */
    protected function configureEmail()
    {
        $this->email = new PHPMailer(true);
        $emailConfig = JsonParser::parseFile(self::EMAIL_CONFIG);

        if (!isset($emailConfig)) {
            throw new Exception();
        }

        if (isset($emailConfig['default']['timezone'])) {
            date_default_timezone_set($emailConfig['default']['timezone']);
        }

        $this->email->isSMTP();

        $this->setDebug($emailConfig);
        $this->email->CharSet = PHPMailer::CHARSET_UTF8;
        if (isset($emailConfig['phpmailer']['SMTPSecure']) && $emailConfig['phpmailer']['SMTPSecure'] = "TLS") {
            $this->email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif (isset($emailConfig['phpmailer']['SMTPSecure']) && $emailConfig['phpmailer']['SMTPSecure'] = "SMTPS") {
            $this->email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $this->email->Host = $emailConfig['phpmailer']['host'];
        $this->email->Port = $emailConfig['phpmailer']['port'];
        $this->email->SMTPAuth = $emailConfig['phpmailer']['smtpAuthentication'];

        if ($this->email->SMTPAuth) {
            $this->configureAuth($emailConfig);
        }

        $this->setDefaultSender($emailConfig);
    }

    /**
     * @param array $emailConfig
     * @throws Exception
     */
    private function setDefaultSender(array $emailConfig)
    {
        isset($emailConfig['default']['name']) ? $senderName = $emailConfig['default']['name']:$senderName = '';

        if (isset($emailConfig['default']['email'])) {
            try {
                if (preg_match(self::ENV_REGEX, $emailConfig['default']['email'], $match)) {
                    isset($_ENV[$match[3]]) ? $this->email->setFrom($_ENV[$match[3]], $senderName) : null;
                } else {
                    $this->email->setFrom($emailConfig['default']['email'], $senderName);
                }
            } catch (Exception $e) {
                throw new Exception();
            }
        }
    }

    private function setDebug(array $emailConfig){
        if (isset($_ENV['ENV']) && 'dev' === $_ENV['ENV'] && isset($emailConfig['phpmailer']['debug']) && true === $emailConfig['phpmailer']['debug']) {
            $this->email->SMTPDebug = SMTP::DEBUG_SERVER;
        } else {
            $this->email->SMTPDebug = SMTP::DEBUG_OFF;
        }
    }

    /**
     * @param array $emailConfig
     */
    private function configureAuth(array $emailConfig)
    {
        if (isset($emailConfig['phpmailer']['AuthType'])) {
            $this->email->AuthType = $emailConfig['phpmailer']['AuthType'];
        }

        if (preg_match(self::ENV_REGEX, $emailConfig['phpmailer']['username'], $match)) {
            isset($_ENV[$match[3]]) ? $this->email->Username = $_ENV[$match[3]] : null;
        } else {
            $this->email->Username = $emailConfig['phpmailer']['username'];
        }

        if (preg_match(self::ENV_REGEX, $emailConfig['phpmailer']['password'], $match)) {
            isset($_ENV[$match[3]]) ? $this->email->Password = $_ENV[$match[3]] : null;
        } else {
            $this->email->Password = $emailConfig['phpmailer']['password'];
        }
    }

    /**
     * @param string $email
     * @param string $name
     * @throws Exception
     */
    public function sender(string $email, string $name)
    {
        try {
            $this->email->setFrom($email, $name);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    /**
     * @param string $receiver
     * @throws Exception
     */
    public function addReceiver(string $receiver)
    {
        $this->email->addAddress($receiver);
    }

    /**
     * @param string $address
     * @throws Exception
     */
    public function addReplyTo(string $address)
    {
        $this->email->addReplyTo($address);
    }

    /**
     * @param string $subject
     */
    public function subject(string $subject)
    {
        $this->email->Subject = $subject;
    }

    /**
     * @param $content
     * @throws Exception
     */
    public function setContent($content)
    {
        $this->email->msgHTML($content);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function send(): bool
    {
        //send the message, check for errors
        if (!$this->email->send()) {
            return $this->email->ErrorInfo;
        } else {
            return true;
        }
    }

    /**
     * @throws Exception
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function setRender($template, $parameters = [])
    {
        $this->setContent($this->twig->render($template, $parameters));
    }
}