<?php
require_once(__DIR__ . '/service/HTTPTools.php');
require_once(__DIR__ . '/service/Util.php');

class Link
{
    private $url;
    private $fullUrl;
    private $httpCode;

    public function __construct($url, $username)
    {
        $this->url = $url;
        $this->fullUrl = $this->setUrl($url, $username);

        if ($this->isPublic()) {
            $this->httpCode = $this->request($this->fullUrl);
        }
    }

    public function getUrl()
    {
        return htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
    }

    public function isNotFound()
    {
        return $this->httpCode === 404;
    }

    private function setUrl($url, $username)
    {
        $url = str_replace(' ', '%20', trim($url));

        if ($this->isAbsolute($url)) {
            return $url;
        }

        if (Util::startsWith($url, '/')) {
            return 'https://fc.lnu.se/~' . $username . $url;
        }

        return 'https://fc.lnu.se/~' . $username . '/' . $url;
    }

    public function isPublic()
    {
        return !Util::startsWith($this->fullUrl, 'dold/') && !Util::contains($this->fullUrl, '/dold/') ? true : false;
    }

    private function request($url)
    {
        return HTTPTools::getHttpCodeWithRedirect($url);
    }

    private function isAbsolute($url)
    {
        return Util::startsWith($url, 'http://') || Util::startsWith($url, 'https://');
    }
}
