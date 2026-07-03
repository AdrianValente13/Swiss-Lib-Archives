<!DOCTYPE html>
<!-- saved from url=(0021)https://swisslib.org/ -->
<html dir="ltr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Swiss-Lib</title>
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="images/favicon-szinwnhl.png">
    </head>

    <body class="no-touch">
        <header>
            <!-- Logo -->
            <div id="logo">
                <img src="images/swiss-lib-logo-couleur.svg" />
            </div>
            <h1 class="title">
                    Bienvenue sur Swiss-Lib / Willkommen Swiss-Lib/ Benvenuti in Swiss-Lib
            </h1>
            <div class="container">
            </div>
        </header>
        <main>
  
       
<?php

// 1 - Récupération des paramètres
$id = $_GET['id'] ?? '';

if (empty($id) || strpos($id, '/') === false) {
    die('ID invalide');
}

list($dossier, $fichier) = explode('/', $id, 2);

// URL de l'archive
$nom_domaine = "https://listserv.linguistlist.org/pipermail/swiss-lib/";
$url = $nom_domaine . $id;

// Répertoire de cache
$cacheDir = __DIR__ . '/cache/archives/' . $dossier;

// Création du répertoire si nécessaire
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

// Fichiers de cache
$cacheFile = $cacheDir . '/' . md5($fichier) . '.html';
$lockFile  = $cacheFile . '.lock';

// Durée du cache (2 minutes)
$cacheTime = 120;

// -----------------------------------------------------
// Lecture du cache si encore valide
// -----------------------------------------------------
if (
    file_exists($cacheFile) &&
    (time() - filemtime($cacheFile)) < $cacheTime
) {
    $html = file_get_contents($cacheFile);
} else {

    $html = null;

    // Évite plusieurs rafraîchissements simultanés
    if (!file_exists($lockFile)) {

        file_put_contents($lockFile, time());

        try {

            $freshHtml = @file_get_contents($url);

            if ($freshHtml !== false) {

                // Sauvegarde du nouveau cache
                file_put_contents($cacheFile, $freshHtml);

                $html = $freshHtml;

            } elseif (file_exists($cacheFile)) {

                // En cas d'erreur réseau on garde l'ancien cache
                $html = file_get_contents($cacheFile);
            }

        } finally {

            if (file_exists($lockFile)) {
                unlink($lockFile);
            }
        }

    } elseif (file_exists($cacheFile)) {

        // Si un autre processus recharge déjà la page
        $html = file_get_contents($cacheFile);
    }
}

// -----------------------------------------------------
// Extraction de la balise <pre>
// -----------------------------------------------------
if ($html) {

    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    //Header
    $titre = $dom->getElementsByTagName('h1')->item(0)?->textContent ?? '';
    $adresse = $dom->getElementsByTagName('b')->item(0)?->textContent ?? '';
    $email = $dom->getElementsByTagName('a')->item(0)?->textContent ?? '';
    $date = $dom->getElementsByTagName('i')->item(0)?->textContent ?? '';


   
$dateString = "Thu Jul 2 12:13:16 UTC 2026";

$date = new DateTime($dateString);

$jours = [
    'Monday'    => 'Lundi',
    'Tuesday'   => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday'  => 'Jeudi',
    'Friday'    => 'Vendredi',
    'Saturday'  => 'Samedi',
    'Sunday'    => 'Dimanche'
];

$mois = [
    'January'   => 'janvier',
    'February'  => 'février',
    'March'     => 'mars',
    'April'     => 'avril',
    'May'       => 'mai',
    'June'      => 'juin',
    'July'      => 'juillet',
    'August'    => 'août',
    'September' => 'septembre',
    'October'   => 'octobre',
    'November'  => 'novembre',
    'December'  => 'décembre'
];

$date_final =
    $jours[$date->format('l')] . ' ' .
    $date->format('j') . ' ' .
    $mois[$date->format('F')] . ' ' .
    $date->format('Y') . ' - ' .
    $date->format('H:i');



        echo "
        <div class='message-meta'>
            <div><span class='label'>📍 Adresse :</span> $adresse</div>
            <div><span class='label'>👤 Auteur :</span> $email</div>
            <div><span class='label'>📅 Date :</span> $date_final</div>
        </div>
        ";


    $pres = $dom->getElementsByTagName('pre');

    if ($pres->length > 0) {
        echo '<div class="card-message"';
        echo $dom->saveHTML($pres->item(0));
        echo '</div">';
    } else {
        echo 'Aucune balise &lt;pre&gt; trouvée.';
    }

} else {

    echo 'Impossible de récupérer le contenu.';
}
        ?>
        </main>
                        </body>
                        </html>

                        