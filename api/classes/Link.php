<?php
require_once(__DIR__ . '/service/HTTPTools.php');

class Link
{
    private $url;
    private $type;
    private $public;
    private $httpCode;

    public function __construct($url, $username)
    {
        $this->url = $this->setUrl($url, $username);
        $this->type = $this->setType($url);
        $this->public = $this->isPublic($url);
        $this->httpCode = $this->scan($this->url);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getType()
    {
        return $this->type;
    }

    public function toString()
    {
        return 'URL: ' . $this->url . ' | Code: ' . $this->httpCode . ' | Public: ' . $this->public;
    }

    private function setUrl($url, $username)
    {
        if ($this->isAbsolute($url)) {
            return $url;
        } elseif (strpos($url, '/') === 0) {
            return 'https://fc.lnu.se/~' . $username . $url;
        } else {
            return 'https://fc.lnu.se/~' . $username . '/' . $url;
        }
    }

    private function setType($url)
    {
        return $this->isAbsolute($url) ? true : false;
    }

    public function public()
    {
        return $this->public;
    }

    private function isPublic($url)
    {
        return strpos(strtolower($url), 'dold/') !== 0 && strpos($url, '/dold/') === false ? true : false;
    }

    private function scan($url)
    {
        if ($this->public) {
            return HTTPTools::getHttpCodeWithRedirect($url);
        }
    }

    private function isAbsolute($url)
    {
        return strpos(strtolower($url), 'http://') === 0 || strpos(strtolower($url), 'https://') === 0;
    }
}
