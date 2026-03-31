<?php
    function creerSlug($titre)
    {
        // Remplace les caractères accentués par leur équivalent
        $result = htmlentities($titre, ENT_NOQUOTES, 'utf-8');
        $result = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $result);
        $result = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $result); // Pour les ligatures comme œ
        $result = preg_replace('#&[^;]+;#', '', $result); // Supprime les autres entités HTML restantes
        $result = strtolower($result);
        $result = preg_replace('/[^a-z0-9]+/i', '-', $result);
        $result = trim($result, '-');
        
        return $result;
    }
?>