<?php
require_once(__DIR__ . '../../libs/simple_html_dom.php');
require_once(__DIR__ . '/../classes/service/HTTPTools.php');

class L2Parser
{
    private $html;
    private $htmlObject;
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->student->setHasIndex($this->checkIndexExists());

        if ($this->student->hasIndex()) {
            $this->html = HTTPTools::getHtml($this->student->getHomeUrl() . '/');
            $this->htmlObject = str_get_html($this->html);
        }
    }

    /**
     * Retrrns true if Students indexpage could be found
     * @return boolean
     */
    public function hasIndex()
    {
        return $this->student->hasIndex();
    }

    /**
     * Returns HTML title as string or false if no title was found
     * @return String Titles string
     * @return Boolean False
     */
    public function getTitle()
    {
        $title = $this->htmlObject->find('head title', 0)->plaintext;
        return empty($title) ? false : $title;
    }

    /**
     * Checks the amount of total heading tags used in students homepage
     * @return Integer Total number of used header-tags
     */
    public function getHeaderTags()
    {
        return count($this->htmlObject->find('h1, h2, h3, h4, h5, h6'));
    }

    /**
     * Checks if students HTML-code contains any kind of lists (ol or ul)
     * @return Boolean
     */
    public function hasLists()
    {
        return count($this->htmlObject->find('ul li, ol li')) > 0;
    }

    /**
     * Checks if students HTML-code does contain an image with relative address
     * @return boolean
     */
    public function hasRelativeImgAddress()
    {
        foreach ($this->htmlObject->find('img') as $e) {
            if (strpos(strtolower($e->src), 'http') !== 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks Students HTML-code for absolute URLs
     * @return boolean
     */
    public function hasAbsoluteUrl()
    {
        foreach ($this->htmlObject->find('a') as $e) {
            if (strpos(strtolower($e->href), 'http') === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks Students HTML-code for an relative URL to the dold-folder
     * @return boolean
     */
    public function hasRelativeDoldUrl()
    {
        foreach ($this->htmlObject->find('a') as $e) {
            if (strpos(strtolower($e->href), 'dold') === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if students HTML-code contains an entity
     * @return boolean
     */
    public function hasEntity()
    {
        return $this->html === html_entity_decode($this->html, ENT_QUOTES | ENT_HTML5, 'UTF-8') ? false : true;
    }

    /**
     * Checks if HTML contains any CSS-code.
     * @return boolean
     */
    public function hasCss()
    {
        return count($this->htmlObject->find('*[style], style, link')) > 0;
    }

    /**
     * Returns anassociative array with the amount of p-tags used.
     * @return Integer Amount of p-tags found
     */
    public function getParagraphTags()
    {
        return count($this->htmlObject->find('p'));
    }

    /**
     * Checks if scripts are used
     * @return boolean
     */
    public function hasScripts()
    {
        return count($this->htmlObject->find('script')) > 0;
    }

    /**
     * Checks if students FirstClass account contains an index-page (index.html or index.htm)
     * @return Boolean
     */
    private function checkIndexExists()
    {
        $pagesToCheck = ['index.html', 'index.htm'];

        foreach ($pagesToCheck as $page) {
            if (HTTPTools::getHttpStatusCode($this->student->getHomeUrl() . '/' . $page) == 200) {
                return true;
            }
        }
        return false;
    }
}
