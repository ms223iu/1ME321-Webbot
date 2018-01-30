<?php
require_once(__DIR__ . '/service/HTTPTools.php');
require_once(__DIR__ . '/service/Util.php');

class Link
{
    private $url;
    private $fullUrl;
    private $httpCode;

    /**
     * Creates absolute URL based on the URL and username provided and
     * if possible gets the http status code
     * @param String $url
     * @param String $username Students username
     */
    public function __construct($url, $username)
    {
        $this->url = $url;
        $this->fullUrl = $this->setUrl($url, $username);

        if ($this->isPublic()) {
            $this->httpCode = $this->request($this->fullUrl);
        }
    }

    /**
     * Returns escaped URL
     * @return String url
     */
    public function getUrl()
    {
        return htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Returns true if the returned http status code is 404, else false
     * @return Boolean
     */
    public function isNotFound()
    {
        return $this->httpCode === 404;
    }

    /**
     * Converts the url into an absolute URL
     * @param String $url
     * @param String $username Students username
     * @return String Full URL
     */
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

    /**
     * Returns true if URL is public and can be accesed without logging in into FirstClass
     * @return Boolean
     */
    public function isPublic()
    {
        return !Util::startsWith($this->fullUrl, 'dold/') && !Util::contains($this->fullUrl, '/dold/') ? true : false;
    }

    /**
     * Requests a file to get its http-code and return it. Follows redirects
     * @param  String $url
     * @return Boolean
     */
    private function request($url)
    {
        return HTTPTools::getHttpCodeWithRedirect($url);
    }

    /**
     * Checks if an URL is absolute by checking its beginning
     * @param  String  $url
     * @return Boolean
     */
    private function isAbsolute($url)
    {
        return Util::startsWith($url, 'http://') || Util::startsWith($url, 'https://');
    }
}
