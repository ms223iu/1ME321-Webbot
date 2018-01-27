<?php

class L2Responder
{
    private $parser;
    private $result = [];

    public function __construct(L2Parser $parser)
    {
        $this->parser = $parser;

        // Index page was found
        if ($parser->hasIndex()) {
            $this->addResult($this->getBrokenLinksResponse());
            $this->addResult($this->getIndexResponse());
            $this->addResult($this->getTitleResponse());
            $this->addResult($this->getHeaderTagsResponse());
            $this->addResult($this->getListsResponse());
            $this->addResult($this->getRelativeImgResponse());
            $this->addResult($this->getHasAbsoluteUrlResponse());
            $this->addResult($this->getDoldUrlResponse());
            $this->addResult($this->getEntityResponse());
            $this->addResult($this->getParagraphTagsResponse());

            if ($parser->hasCss()) {
                $this->addResult($this->getHasCssResponse());
            }

            if ($parser->hasScripts()) {
                $this->addResult($this->getHasScriptsResponse());
            }
        }

        // Index page wasn't found
        elseif (!$parser->hasIndex()) {
            $this->addResult($this->getIndexResponse());
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    private function getIndexResponse()
    {
        $reqPass = 'Indexfil hittades';
        $reqFail = 'Ingen Indexfil hittades';
        $comFail = 'Ingen indexfil hittades. Kontrollera filens placering, namn och filtyp.';

        return $this->parser->hasIndex() ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getTitleResponse()
    {
        $title = $this->parser->getTitle();

        $reqPass = 'Titel hittades';
        $comPass = "Din titel på sidan är \"$title\". Kontrollera att titeln är relevant.";

        $reqFail = 'Ingen titel hittades';
        $comFail = 'Lägg till en titel på din hemsida.';

        return $title ? $this->respond($reqPass, true, $comPass) : $this->respond($reqFail, false, $comFail);
    }

    private function getHeaderTagsResponse()
    {
        $reqPass = 'Rubrik hittades';
        $reqFail = 'Ingen rubrik hittades';
        $comFail = 'Lägg till minst en rubrik på din ingångssida.';

        return ($this->parser->getHeaderTags() > 0) ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getListsResponse()
    {
        $reqPass = 'Lista hittades';
        $reqFail = 'Ingen lista hittades';
        $comFail = 'Lägg till minst en lista på din ingångssida.';

        return $this->parser->hasLists() ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getRelativeImgResponse()
    {
        $reqPass = 'Bild med en relativ länk hittades';
        $reqFail = 'Ingen bild med en relativ länk hittades';
        $comFail = 'Lägg till minst en bild med en relativ adress.';

        return $this->parser->hasRelativeImgAddress() ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getHasAbsoluteUrlResponse()
    {
        $reqPass = 'Absolut länk hittades';
        $reqFail = 'Ingen absolut länk hittades';
        $comFail = 'Lägg till minst en länk med en absolut adress.';

        return $this->parser->hasAbsoluteUrl() ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getDoldUrlResponse()
    {
        $reqPass = 'Relativ länk till dold-mappen hittades';
        $reqFail = 'Ingen relativ länk till dold-mappen hittades';
        $comFail = 'Lägg till en länk till dold-mappen med en relativ adress.';

        return $this->parser->hasRelativeDoldUrl() ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getEntityResponse()
    {
        $reqPass = 'Entitet hittades';
        $reqFail = 'Ingen entitet hittades';
        $comFail = 'Lägg till minst en entitet på din ingångssida.';

        return $this->parser->hasEntity() ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function getHasCssResponse()
    {
        $req = 'CSS hittades';
        $com = 'CSS får inte användas. Ta bort all CSS-kod.';

        return $this->respond($req, false, $com);
    }

    private function getParagraphTagsResponse()
    {
        $numberOfTags = $this->parser->getParagraphTags();
        $req = 'P-taggar används för styckeindelning';
        $com = "Du använder dig av $numberOfTags st p-taggar. Kontrollera så att du använder dessa för att göra styckeindelning då detta kravet för tillfället inte kontrolleras automatiskt.";

        if ($numberOfTags == 0) {
            return $this->respond('Inga p-taggar hittades', false, 'Lägg till några p-taggar på din sida och använd dessa för att göra styckeindelning.');
        }

        return ($numberOfTags >= 3) ? $this->respond($req, true, $com) : $this->respond($req, false, $com);
    }

    private function getHasScriptsResponse()
    {
        $req = 'JavaScript hittades';
        $com = 'JavaScript får inte användas. Ta bort all JavaScript-kod.';

        return $this->respond($req, false, $com);
    }

    // @TODO Fix this mess
    public function getBrokenLinksResponse()
    {
        $result = $this->parser->getBrokenLinks();
        $broken = $result['BROKEN'];
        $skipped = $result['SKIPPED'];

        $reqPass = 'Inga brutna länkas hittades';

        $reqFail = 'Brutna länkar hittades';
        $comFail = "Följande länkar är brutna på ingångssidan ";

        if ($skipped > 0) {
            $comPass = "$skipped länkar hoppades över eftersom dessa pekar till filer inuti dold-mappen.";
            $comFail .= "($skipped länkar hoppades över eftersom dessa pekar till filer inuti dold-mappen):";
        } else {
            $comPass = null;
        }

        $comFail .= '<ul>';

        foreach ($broken as $link) {
            $comFail .= "<li>$link</li>";
        }

        $comFail .= '</ul>';

        if ($skipped > 0) {
            return count($broken) == 0 ? $this->respond($reqPass, true, $comPass) : $this->respond($reqFail, false, $comFail);
        }

        return count($broken) == 0 ? $this->respond($reqPass, true) : $this->respond($reqFail, false, $comFail);
    }

    private function respond($requirement, $status, $comment=null)
    {
        if ($comment) {
            return array('requirement' => $requirement, 'status' => $status, 'comment' => $comment);
        }

        return array('requirement' => $requirement, 'status' => $status);
    }

    private function addResult($resultArray)
    {
        array_push($this->result, $resultArray);
    }
}
