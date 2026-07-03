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
                <!-- Bloc FR -->
                <div class="card">
                    <div class="flag">
                        <img src="https://flagcdn.com/w40/fr.png" alt="FR">
                    </div>
                    <div class="content">
                        <p><strong>Pour accéder aux messages de la plateforme, référez-vous à la section <a href="#title_archive">Archives</a> !</strong></p>
                        <p>La documentation HEG est disponible en suivant ce lien : <a href="https://www.hesge.ch/heg/formations/bachelors/information-science#apercu-de-la-formation?swiss-lib">https://www.hesge.ch/heg/formations/bachelors/information-science#apercu-de-la-formation?swiss-lib</a>.</p>
                        <p>En cas de problèmes, vous pouvez nous écrire à l'adresse suivante :  <a href="mailto:info@swisslib.org">info@swisslib.org</a></p>
                    </div>
                </div>
                <!-- Bloc DE -->
                <div class="card">
                    <div class="flag">
                        <img src="https://flagcdn.com/w40/de.png" alt="DE">
                    </div>
                    <div class="content">
                        <p><strong>Um auf die Nachrichten der Plattform zuzugreifen, siehe den Bereich <a href="#title_archive">Archiv</a> !</strong></p>
                        <p>Die Sss ist verfügbar unter folgendem Link: <a href="https://www.hesge.ch/heg/formations/bachelors/information-science#apercu-de-la-formation?swiss-lib">https://www.hesge.ch/heg/formations/bachelors/information-science#apercu-de-la-formation?swiss-lib</a></p>
                        <p>Wenn Sie Probleme beim Einloggen haben, kontaktieren Sie den Support : <a href="mailto:info@swisslib.org">info@swisslib.org</a></p>
                    </div>
                </div>
                <!-- Bloc IT -->
                <div class="card">
                    <div class="flag">
                        <img src="https://flagcdn.com/w40/it.png" alt="IT">
                    </div>
                    <div class="content">
                        <p><strong>Per accedere ai messaggi della piattaforma, fare riferimento alla sezione <a href="#title_archive">Archivi</a>!</strong></p>
                        <p>La documentazione è disponibile qui: <a href="https://www.hesge.ch/heg/formations/bachelors/information-science#apercu-de-la-formation?swiss-lib">https://www.hesge.ch/heg/formations/bachelors/information-science#apercu-de-la-formation?swiss-lib</a></p>
                        <p>In caso di problemi, contattare il supporto : <a href="mailto:info@swisslib.org">info@swisslib.org</a></p>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <h1>Using Swiss-Lib</h1>
            <p>In order to using the list, you can post a message by sending email to <a href="mailto:swiss-lib@listserv.linguistlist.org" >swiss-lib@listserv.linguistlist.org.</a></p>
            <p>If you would like to subscribe to the list and receive all messages by email, <a href="https://listserv.linguistlist.org/cgi-bin/mailman/listinfo/swiss-lib">please find all the information below.</a> </p>
        
<?php

//
// 1 - Récupérer la liste des archives
//

//Variables
$cacheFile = __DIR__ . "/cache/archive.html"; // Emplacement du fichier de cache pour l'archive 
$cacheTime = 120;  // En secondes, 2 minutes avant d'aller reprendre les données sur l'URL d'archive
$lockFile = $cacheFile . '.lock'; // petit lock pour éviter un rafraichissement de la page trop rapide
$archiveUrl = "https://listserv.linguistlist.org/pipermail/swiss-lib/"; //lien des archives


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
echo "<h1 id='title_archive'>Archives</h1>";
?>
<div class="filters">
    <button  onclick="filterPosts('all', this)">Tous</button>
    <button  onclick="filterPosts('emploi', this)">Emploi</button>
    <button  onclick="filterPosts('evenements', this)">Événements</button>
    <button  onclick="filterPosts('communaute', this)">Communauté</button>
    <button  onclick="filterPosts('formations', this)">Formations</button>
    <button  onclick="filterPosts('ressources', this)">Ressources</button>
    <button  onclick="filterPosts('recherches', this)">Recherches et contributions</button>
    <button  onclick="filterPosts('newsletter', this)">Newsletter</button>
</div>

<!-- Recherche -->
<div class="search-box">
    <svg class="search-icon" viewBox="0 0 24 24" fill="none">
        <path
            d="M21 21L15.8 15.8M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
        />
    </svg>

    <input
        type="search"
        id="search-category"
        placeholder="Rechercher une catégorie..."
        autocomplete="off"
    />
</div>

<?php

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

    $dom = new DOMDocument();
    $dom->loadHTML($bodyHtml);

    $xpath = new DOMXPath($dom);

    // récupérer toutes les lignes du tableau sauf l'entête (and position()<=4 pour avoir une limite)
    $rows = $xpath->query("//table/tr[position()>1]");

    $result = [];

    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');

        if ($cols->length >= 2) {

            // ✅ 1. Récupérer le texte (colonne 1)
            $title = trim($cols->item(0)->textContent);

            // ✅ 2. Récupérer un lien dans la colonne 2
            $link = $cols->item(1)->getElementsByTagName('a')->item(0);

            if ($link) {
                $href = $link->getAttribute('href');

                // ✅ 3. Extraire juste la base (sans /thread.html etc.)
                preg_match('#(.*/Week-of-Mon-\d{8})#', $href, $matches);

                $baseUrl = $matches[1] ?? $href;
                $baseUrl = dirname($href);

                $result[] = [
                    'title' => $title,
                    'url'   => $baseUrl
                ];
            }
        }
    }

        //  Affichage
    $nom_domaine = "https://listserv.linguistlist.org/pipermail/swiss-lib/";


    //
    // 2 - Récupérer la liste des posts par rapport aux archives
    //


foreach ($result as $entry) {

    $title = $entry['title'];

    // URL
    $archiveUrl_cate = $nom_domaine . $entry['url'] . "/thread.html";


    // Cache
    $cacheFile = __DIR__ . "/cache/archives/" . md5($archiveUrl_cate) . ".html";

    $cacheTime = 120;
    $lockFile = $cacheFile . '.lock';

    if (!is_dir(__DIR__ . "/cache/archives")) {
        mkdir(__DIR__ . "/cache/archives", 0777, true);
    }

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $html = file_get_contents($cacheFile);
    } else {

        if (!file_exists($lockFile)) {
            file_put_contents($lockFile, '1');

            $freshHtml = file_get_contents($archiveUrl_cate);

            // ✅ FIX 3 : <li> au lieu de <LI>
            if ($freshHtml && stripos($freshHtml, "<li>") !== false) {

                file_put_contents($cacheFile, $freshHtml);
                $html = $freshHtml;

            } else {
                $html = file_exists($cacheFile) ? file_get_contents($cacheFile) : null;
            }

            unlink($lockFile);
        } else {
            $html = file_exists($cacheFile) ? file_get_contents($cacheFile) : null;
        }
    }

    // parsing
    if ($html) {

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $nodes = $xpath->query("//a");

echo "<h2>" . htmlspecialchars($title) . "</h2>";
echo "<div class='cards-container-archives'>";

foreach ($nodes as $node) {

    $msgTitle = trim($node->textContent);
    $msgLink  = trim($node->getAttribute('href'));

    // filtre simple
    if (
        strpos($msgLink, '.html') !== false &&
        strpos($msgLink, 'subject') === false &&
        strpos($msgLink, 'author') === false &&
        strpos($msgLink, 'date') === false
    ) {
        
    
        //  récupérer le <li> parent
        $li = $node->parentNode;

        //  récupérer l'auteur (balise <i>)
        $authorNode = $li->getElementsByTagName('i')->item(0);
        $author = $authorNode ? trim($authorNode->textContent) : 'Inconnu';


        /*$url = $nom_domaine . $entry['url'] . "/" . $msgLink;*/
        $url = $entry['url'] . "/" . $msgLink;
        echo "<div class='card-archives'  data-category='". htmlspecialchars($msgTitle) . " " . htmlspecialchars($author) . "'>";
        /*echo "<a href='post_archives.php?id=" . $url . "' target='_blank'>";*/
        echo "<a href='post_archives.php?id=" . $url . "'>";
        echo "<span class='title'>" . htmlspecialchars($msgTitle) . "</span>";
        echo "<span class='author'>" . htmlspecialchars($author) . "</span>";
        echo "</a>";
        echo "</div>";        

    }
}

    echo "</div>";
    }
}


    }
?>

        </main>
        <script>
    function filterPosts(category, button_object) {

    const posts = document.querySelectorAll('.card-archives');

    const button = document.getElementById(category);

    
    // Retire l'état actif de tous les boutons
    document.querySelectorAll('.filters button').forEach(btn => {
        btn.classList.remove('active');
    });

    // Active uniquement le bouton cliqué
    button_object.classList.add('active');


    posts.forEach(post => {

        const categories = post.dataset.category.toLowerCase();

        if (
            category === 'all' ||
            categories.includes(category.toLowerCase())
        ) {
            post.style.display = '';
        } else {
            post.style.display = 'none';
        }

    });
}


document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('search-category');
    const cards = document.querySelectorAll('.card-archives');

    searchInput.addEventListener('input', () => {

        const search = searchInput.value.trim().toLowerCase();

        cards.forEach(card => {

            const category = (card.dataset.category || '').toLowerCase();

            if (category.includes(search)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }

        });

    });

});
</script>
    </body>
</html>
