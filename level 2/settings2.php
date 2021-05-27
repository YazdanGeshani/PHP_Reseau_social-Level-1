<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Paramètres</title> 
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
        <div id="wrapper" class='profile'>
        <?php $userId = $_GET['user_id']; ?>

            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les informations de l'utilisatrice n°
                       <a href="http://localhost/pages/wall.php?user_id=<?php echo intval($userId)?>"><?php echo $userId ?></a></p>

                </section>
            </aside>
            <main>
                <?php
                /**
                 * Etape 1: Les paramètres concernent une utilisatrice en particulier
                 * La première étape est donc de trouver quel est l'id de l'utilisatrice
                 * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
                 * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
                 * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
                 */
                

                /**
                 * Etape 2: se connecter à la base de donnée
                 */
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "SELECT `users`.*, "
                        . "count(DISTINCT `posts`.`id`) as totalpost, "
                        . "count(DISTINCT `given`.`post_id`) as totalgiven, "
                        . "count(DISTINCT `recieved`.`user_id`) as totalrecieved "
                        . "FROM `users` "
                        . "LEFT JOIN `posts` ON `posts`.`user_id`=`users`.`id` "
                        . "LEFT JOIN `likes` as `given` ON `given`.`user_id`=`users`.`id` "
                        . "LEFT JOIN `likes` as `recieved` ON `recieved`.`post_id`=`posts`.`id` "
                        . "WHERE `users`.`id`='" . intval($userId)."'"
                        . "GROUP BY `users`.`id`"
                        ;
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }
                $user = $lesInformations->fetch_assoc();

                /**
                 * Etape 4: à vous de jouer
                 */
                //@todo: afficher le résultat de la ligne ci dessous, remplacer les valeurs ci-après puis effacer la ligne ci-dessous
                echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>                
                <article class='parameters'>
                    <h3>Mes paramètres</h3>
                    <dl>
                        <dt>Pseudo</dt>
                        <dd> <a href="http://localhost/pages/wall.php?user_id=<?php echo intval($userId)?>"><?php echo $user ["alias"]?></a></dd>
                        <dt>Email</dt>
                        <dd> <?php echo $user ["email"]?></dd>
                        <dt>Nombre de message</dt>
                        <dd> <?php echo $user ["totalpost"]?></dd>
                        <dt>Nombre de "J'aime" donnés </dt>
                        <dd> <?php echo $user ["totalgiven"]?></dd>
                        <dt>Nombre de "J'aime" reçus</dt>
                        <dd> <?php echo $user ["totalrecieved"]?></dd>
                    </dl>

                </article>
            </main>
        </div>
    </body>
</html>
