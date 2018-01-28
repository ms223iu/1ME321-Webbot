<?php
require_once(__DIR__ . '/service/HTTPTools.php');

class Link
{
    private $url;
    private $httpCode;

    public function __construct($url, $username)
    {
        $this->url = $this->setUrl($url, $username);

        if ($this->isPublic()) {
            $this->httpCode = $this->request($this->url);
        }
    }

    public function getUrl()
    {
        return htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
    }

    public function isBroken()
    {
        return $this->httpCode === 404;
    }

    private function setUrl($url, $username)
    {
        $url = str_replace(' ', '%20', $url);

        if ($this->isAbsolute($url)) {
            return $url;
        }

        if (strpos($url, '/') === 0) {
            return 'https://fc.lnu.se/~' . $username . $url;
        }

        return 'https://fc.lnu.se/~' . $username . '/' . $url;
    }

    public function isPublic()
    {
        return strpos(strtolower($this->url), 'dold/') !== 0 && strpos(strtolower($this->url), '/dold/') === false ? true : false;
    }

    private function request($url)
    {
        return HTTPTools::getHttpCodeWithRedirect($url);
    }

    private function isAbsolute($url)
    {
        return strpos(strtolower($url), 'http://') === 0 || strpos(strtolower($url), 'https://') === 0;
    }
}
