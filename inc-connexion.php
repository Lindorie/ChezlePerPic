<?php

$dev = true;
$local = false;

if ($local) {

    // Connexion base de données locale
    $host = 'localhost';
    $login = 'root';
    $password = '';
    $base = 'perepic';

} else {

    // Connexion base de données en ligne
    $host = 'cl1-sql8';
    $login = 'lindorie16';
    $password = 'picdeluc26';
    $base = 'lindorie16';

}

if ($dev) { $prefix = 'dev_'; }
else { $prefix = ''; }

$link = mysqli_connect($host, $login, $password, $base);

if($link) {
    mysqli_query($link,"SET lc_time_names = 'fr_FR'");
    mysqli_query($link,"SET NAMES utf8");
} else {
    echo 'Erreur de connexion à la base de données';
}

?>
