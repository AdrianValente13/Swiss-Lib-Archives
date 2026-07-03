<!DOCTYPE html>
<!-- saved from url=(0021)https://swisslib.org/ -->
<html dir="ltr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Swiss-Lib</title>
        <link href="css/style.css" rel="stylesheet" />
    </head>

    <body class="no-touch">
    <!-- Logo -->
    <div id="logo">
        <img src="images/swiss-lib-logo-couleur.svg" />
    </div>
 
<?php

//Variables
$cacheFile = __DIR__ . "/cache/archive2.html"; /* Emplacement du fichier de cache pour l'archive */
$cacheTime = 120; /* En secondes, 2 minutes avant d'aller reprendre les données sur l'URL d'archive*/
$lockFile = $cacheFile . '.lock'; // petit lock pour éviter un rafraichissement de la page trop rapide
$archiveUrl = "https://listserv.linguistlist.org/pipermail/swiss-lib/Week-of-Mon-20260601/";


// créer dossier cache
if (!is_dir(__DIR__ . "/cache")) {
    mkdir(__DIR__ . "/cache", 0777, true);
}

// récupérer page
$html = file_get_contents($archiveUrl);


// 1. UTILISER CACHE SI VALIDE
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    $html = file_get_contents($cacheFile);

} 
//  2. SINON récupérer en ligne
else {

    // éviter que plusieurs requêtes rafraîchissent en même temps
    if (!file_exists($lockFile)) {
        file_put_contents($lockFile, '1');

        // Récupérer en ligne
        $freshHtml = file_get_contents($archiveUrl);

        // 3. Vérifier que c’est bien la bonne page
        if ($freshHtml && strpos($freshHtml, "The Week Of") !== false) {

            file_put_contents($cacheFile, $freshHtml);
            $html = $freshHtml;

        } else {

            // fallback sur cache existant
            if (file_exists($cacheFile)) {
                $html = file_get_contents($cacheFile);
            } else {
                $html = null;
            }
        }
        unlink($lockFile); // supprimer lock
    }
    else {
        // quelqu’un rafraîchit déjà → fallback cache
        $html = file_exists($cacheFile) ? file_get_contents($cacheFile) : null;
    }
}

// Affichage des données dans la page actuelle
echo "<h1>Archives</h1>";

if ($html) {

    // Charger le HTML dans DOMDocument
    libxml_use_internal_errors(true); // éviter warnings HTML mal formé
    $dom = new DOMDocument();
    $dom->loadHTML($html);

    // Récupérer le body
    $body = $dom->getElementsByTagName('body')->item(0);

    // Convertir le body en HTML
    $bodyHtml = '';
    if ($body) {
        foreach ($body->childNodes as $child) {
            $bodyHtml .= $dom->saveHTML($child);
        }
    }


    //Traitement des liens
    $bodyHtml = preg_replace_callback(
        '/href="([^"]*)"/i',
        function ($matches) use ($archiveUrl) {

            $link = $matches[1];
            $nom_domaine = "https://linguistlist.org";

            //  si lien déjà absolu
            if (strpos($link, "http") === 0) {
                // remplacer domaine
                $link = str_replace($nom_domaine, $archiveUrl, $link);
            } else {
                // lien relatif → ajouter base
                $link = $archiveUrl . '/' . ltrim($link, '/');
            }

            return 'href="' . $link . '"';
    },
    $bodyHtml
);


    echo " <div id='archives' style='border:1px solid #ccc;padding:10px;margin-bottom:20px;'>";
    echo $bodyHtml;
    echo "</div>";
}

?>

</body>
</html>
