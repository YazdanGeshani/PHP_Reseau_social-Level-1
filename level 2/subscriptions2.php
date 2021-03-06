<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnements</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
 <?php
        $userId = $_GET['user_id']; 
    ?>
        <header>
            <a href='admin.php'><img src="resoc.jpg" alt="Logo de notre réseau social"/></a>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=<?php echo intval($userId)?>">Mur</a>
                <a href="feed.php?user_id=<?php echo intval($userId)?>">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
            </nav>
            <nav id="user">
                <a href="#">▾ Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo intval($userId)?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo intval($userId)?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo intval($userId)?>">Mes abonnements</a></li>
                </ul>
            </nav>
        </header>
        <div id="wrapper">
            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes dont
                        l'utilisatrice
                        n° <a href="http://localhost/pages/wall.php?user_id=<?php echo intval($userId)?>"><?php echo $userId ?></a>
                        suit les messages
                    </p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = $_GET['user_id'];
                // Etape 2: se connecter à la base de donnée
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "SELECT `users`.* "
                        . "FROM `followers` "
                        . "LEFT JOIN `users` ON `users`.`id`=`followers`.`followed_user_id` "
                        . "WHERE `followers`.`following_user_id`='" . intval($userId) . "'"
                        . "GROUP BY `users`.`id`"
                ;
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
                ?>
                <?php while ($user = $lesInformations->fetch_assoc())
                {
                    echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <article>
                    <img src="user.jpg" alt="blason"/>
                    <h3>
                        <a href="http://localhost/pages/wall.php?user_id=<?php echo $user["id"]?>">
                            <?php echo $user["alias"]?>
                        </a>
                    </h3>
                    <p>
                        <a href="http://localhost/pages/wall.php?user_id=<?php echo $user["id"]?>">
                            <?php echo $user["id"]?></a></p>                    
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>