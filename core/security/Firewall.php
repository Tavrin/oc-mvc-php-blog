<?php


namespace Core\security;


use Core\http\Request;
use Core\utils\JsonParser;
use Core\utils\StringUtils;

class Firewall
{
    public const SECURITY_CONFIG = ROOT_DIR . '/config/security.json';
    private array $firewalls;
    public function __construct()
    {
        $this->firewalls = JsonParser::parseFile(self::SECURITY_CONFIG);
    }

    public function checkFirewalls(Request $request)
    {
       $pathinfo = $request->getPathInfo();
       foreach ($this->firewalls['firewalls'] as $firewall)
       {
           [$pathinfo, $firewallPattern] = StringUtils::normalizeForComparison($pathinfo, $firewall['pattern']);
           if (false === strpos($pathinfo, $firewallPattern)) {
                continue;
           }

            dd('halt');
       }
    }
}