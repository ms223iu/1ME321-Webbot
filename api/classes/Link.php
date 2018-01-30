<?php
require_once(__DIR__ . '/service/HTTPTools.php');
require_once(__DIR__ . '/service/Util.php');

class Link
{
    private $absoluteUrl;
    private $httpCode;

    /**
     * Creates absolute URL based on the URL and username provided and
     * if possible gets the http-code
     * @param String $url
     * @param String $username Students username
     */
    public function __construct($url, $username)
    {
        $this->absoluteUrl = $this->setUrl($url, $username);

        if ($this->isPublic()) {
            $this->httpCode = $this->request($this->absoluteUrl);
        }
    }

    /**
     * Returns escaped URL
     * @return String url
     */
    public function getUrl()
    {
        return htmlspecialchars($this->absoluteUrl, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Returns true if the returned http-code is 404, else false
     * @return Boolean
     */
    public function isNotFound()
    {
        return $this->httpCode === 404;
    }

    /**
     * Checks if an URL is relative and converts it into an absolute URL if needed
     * @param String $url
     * @param String $username Students username
     * @return String Absolute URL
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
        return !Util::startsWith($this->absoluteUrl, 'dold/') && !Util::contains($this->absoluteUrl, '/dold/') ? true : false;
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
     * Checks if the provided URL is absolute
     * @param  String  $url
     * @return Boolean
     */
    private function isAbsolute($url)
    {
        return Util::startsWith($url, 'http://') || Util::startsWith($url, 'https://');
    }
}
