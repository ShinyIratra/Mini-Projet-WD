<?php
    function creerSlug($titre)
    {
        $result = strtolower($titre);
        $result = preg_replace('/[^a-z0-9]+/i', '-', $result);
        $result = trim($result, '-');
        return $result;
    }
?>