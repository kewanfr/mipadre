<?php
class Conf
{

    static $debug = 0; // 0 = Debug désactivé, 1 = Débug activé, 2 = Débug avancé

    static $siteName = 'Mi Padre'; // Nom du site Affiché dans le titre de la page
    static $navBarName = "Mi Padre"; // Nom du site affiché dans la barre de navigation

    static $dbName = 'dev'; // Nom de la base de données à utiliser
    
    static $QRTokenLength = 3; // Longueur du token pour les QRCode (à changer)

    static $majorVersion = 1; // Version majeure du site
    static $minorVersion = 2; // Version mineure du site
<<<<<<< HEAD
    static $patchVersion = 2; // Version de patch du site
=======
    static $patchVersion = 1; // Version de patch du site
>>>>>>> ClientEditPage
    static $versionName = ""; // Nom de la version
    static $versionDate = "17 Fev"; // Date de la version 
    static $version;

    static $copyright = "&copy;2023 Mi Padre"; // Texte du copyRight
    static $author = "Kewan B"; // Auteur du site

    public function __construct()
    {
        self::$version = self::$majorVersion.".".self::$minorVersion.".".self::$patchVersion;
        if(self::$versionName) self::$version .= "-".self::$versionName;
        if(SecureConf::$env == "dev") self::$version .= "-dev";
    }

}
new Conf();

/**
 * Prefiexes
 */
Router::prefix('console', 'admin'); // Préfix pour les pages admin

/**
 * Admin Routes
 */
Router::connect('admin', 'admin/clients/index');

/**
 * Guest Routes
 */
Router::connect('qr/:id/:token', 'client/qrlogin/id:([0-9]+)/token:([a-zA-Z0-9\-]+)');

?>
