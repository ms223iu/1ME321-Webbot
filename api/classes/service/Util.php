<?php

class Util
{
    public function startsWith($haystack, $needle)
    {
        return (substr(strtolower($haystack), 0, strlen($needle)) === $needle);
    }

    public function contains($haystack, $needle)
    {
        return strpos(strtolower($haystack), $needle) !== false;
    }
}
