# silex_openC

Source OpenClassrooms

Une itération est une phase de travail volontairement courte permettant de produire une version intermédiaire mais livrable d'un produit.
Certaines itérations seront consacrées à l'ajout de nouvelles fonctionnalités à l'application. Les autres permettront d'améliorer son architecture en pratiquant ce qu'on appelle la refactorisation ou remaniement du code(refactoring).


Bonnes pratiques PHP
__________________________________________________________________
http://www.php-fig.org/psr/psr-1/ 

https://secure.php.net/manual/fr/language.basic-syntax.phptags.php 
__________________________________________________________________

Présentation
Le modèle MVC décrit une manière d'architecturer une application informatique en la décomposant en trois sous-parties :
la partie Modèle ;
la partie Vue ;
la partie Contrôleur.
Ce patron de conception(design pattern) a été imaginé à la fin des années 1970 pour le langage Smalltalk afin de bien séparer le code de l'interface graphique de la logique applicative. Il est utilisé dans de très nombreux langages : bibliothèques Swing et Model 2 (JSP) de Java, frameworks PHP, ASP.NET MVC, etc.
Rôles des composants
La partie Modèle d'une architecture MVC encapsule la logique métier ("business logic") ainsi que l'accès aux données. Il peut s'agir d'un ensemble de fonctions (Modèle procédural) ou de classes (Modèle orienté objet).
La partie Vue s'occupe des interactions avec l'utilisateur : présentation, saisie et validation des données.
La partie Contrôleur gère la dynamique de l'application. Elle fait le lien entre l'utilisateur et le reste de l'application.
Interactions entre les composants
Le diagramme ci-dessous résume les relations entre les composants d'une architecture MVC.
Extrait de la documentation du framework Symfony2
La demande de l'utilisateur (exemple : une requête HTTP) est reçue et interprétée par le Contrôleur. Celui-ci utilise les services du Modèle afin de préparer les données à afficher. Ensuite, le Contrôleur fournit ces données à la Vue, qui les présente à l'utilisateur (par exemple sous la forme d'une page HTML).

Itération 3 : intégration du framework PHP Silex
Le but de cette itération est d'intégrer un framework à notre application.
Avantages apportés par un framework
En informatique comme ailleurs, il est rarement utile de réinventer la roue. La plupart des applications web ont les mêmes besoins de base : accès au contenu de la requête HTTP reçue, création et renvoi de la réponse, gestions des sessions... Il existe une catégorie de logiciels dont le rôle est de gérer ces besoins communs : ce sont les frameworks.
Un framework fournit un ensemble de services de base, généralement sous la forme de classes en interaction. À condition de respecter l'architecture qu'il préconise (souvent une déclinaison du modèle MVC), un framework PHP libère le développeur de nombreuses tâches techniques comme le routage des requêtes, la sécurité, la gestion du cache, etc. Cela lui permet de se concentrer sur l'essentiel, c'est-à-dire ses tâches métier. Il existe une grande quantité de frameworks PHP. Parmi les plus connus, citons Symfony, Zend Framework ou encore Laravel.

https://silex.symfony.com/doc/2.0/ 

Installation de Silex
L'installation de Silex peut s'effectuer de deux manières :
En téléchargeant une archive du code source, disponible ici.
En utilisant le gestionnaire de dépendances Composer.
Nous allons utiliser la seconde solution, plus formatrice : Composer est un outil essentiel que tout développeur PHP sérieux se doit de connaître.
Introduction à Composer
Composer est un outil qui permet de définir des dépendances entre des projets PHP (exemple : "mon projet a besoin de Silex") et de récupérer automatiquement les fichiers associés.
Pour fonctionner, Composer doit être installé sur la machine de développement. Vous trouverez plus de détails, ainsi que la procédure d'installation, en consultant sa documentation.
Si vous êtes sous Windows, la solution la plus simple consiste à utiliser l'assistant d'installation de Composer, téléchargeable à cette adresse, puis à vous laisser guider. Au cours de l'installation, vous devrez indiquer le chemin du fichier exécutable php.exe. Son emplacement dépend de la façon dont PHP est installé votre machine. Avec XAMPP, il se trouve dans le répertoire c:\xampp\php.
Si vous êtes sous Mac OS X ou Linux, je vous conseille d'effectuer une installation globale afin que la commande composer soit disponible en ligne de commande.
Une fois l'installation terminée, vérifiez que Composer est utilisable sur votre machine en ouvrant une fenêtre de terminal puis en tapant simplement :
composer
Si l'installation de Composer a réussi, vous obtenez la description des options utilisables.
Création du fichier de dépendances
Une fois Composer installé, il faut créer un fichier de dépendances pour exprimer le fait que notre projet a besoin de Silex pour fonctionner.
Dans un premier temps, nous allons travailler directement dans le répertoire servi par votre serveur Web local. Son emplacement dépend de votre installation. Voici quelques emplacements possibles :
c:\xampp\htdocs avec XAMPP pour Windows.
/var/www ou /var/www/html sous Linux.
/Applications/MAMP/htdocs avec MAMP pour Mac.


C:\xampp>composer
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 1.5.2 2017-09-11 16:59:25

Usage:
  command [options] [arguments]

Options:
  -h, --help                     Display this help message
  -q, --quiet                    Do not output any message
  -V, --version                  Display this application version
      --ansi                     Force ANSI output
      --no-ansi                  Disable ANSI output
  -n, --no-interaction           Do not ask any interactive question
      --profile                  Display timing and memory usage information
      --no-plugins               Whether to disable plugins.
  -d, --working-dir=WORKING-DIR  If specified, use the given directory as working directory.
  -v|vv|vvv, --verbose           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  about           Shows the short information about Composer.
  archive         Creates an archive of this composer package.
  browse          Opens the package's repository URL or homepage in your browser.
  clear-cache     Clears composer's internal package cache.
  clearcache      Clears composer's internal package cache.
  config          Sets config options.
  create-project  Creates new project from a package into given directory.
  depends         Shows which packages cause the given package to be installed.
  diagnose        Diagnoses the system to identify common errors.
  dump-autoload   Dumps the autoloader.
  dumpautoload    Dumps the autoloader.
  exec            Executes a vendored binary/script.
  global          Allows running commands in the global composer dir ($COMPOSER_HOME).
  help            Displays help for a command
  home            Opens the package's repository URL or homepage in your browser.
  info            Shows information about packages.
  init            Creates a basic composer.json file in current directory.
  install         Installs the project dependencies from the composer.lock file if present, or falls back on the composer.json.
  licenses        Shows information about licenses of dependencies.
  list            Lists commands
  outdated        Shows a list of installed packages that have updates available, including their latest version.
  prohibits       Shows which packages prevent the given package from being installed.
  remove          Removes a package from the require or require-dev.
  require         Adds required packages to your composer.json and installs them.
  run-script      Runs the scripts defined in composer.json.
  search          Searches for packages.
  self-update     Updates composer.phar to the latest version.
  selfupdate      Updates composer.phar to the latest version.
  show            Shows information about packages.
  status          Shows a list of locally modified packages.
  suggests        Shows package suggestions.
  update          Upgrades your dependencies to the latest version according to composer.json, and updates the composer.lock file.
  upgrade         Upgrades your dependencies to the latest version according to composer.json, and updates the composer.lock file.
  validate        Validates a composer.json and composer.lock.
  why             Shows which packages cause the given package to be installed.
  why-not         Shows which packages prevent the given package from being installed.




1 - Composer

1- Télécharger composer https://getcomposer.org/Composer-Setup.exe 
2- Sur le terminal dans C/ xampp taper composer
3- Récupérer le code d’installation ensuite ici (il change à chaque version de l’installeur) 
https://getcomposer.org/download/ 

Run this in your terminal to get the latest Composer version:
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
This installer script will simply check some php.ini settings, warn you if they are set incorrectly, and then download the latest composer.phar in the current directory. The 4 lines above will, in order:
Download the installer to the current directory
Verify the installer SHA-384 which you can also cross-check here
Run the installer
Remove the installer

4- Dans le répertoire de l’appli créer un fichier composer.json contenant :
{
    "require": {
        "silex/silex": "~2.0"
    }
}

5- Puis dans le répertoire du projet :
C:\xampp\htdocs\silex_openC>composer install
6- Si vous utilisez Git, créez dans le répertoire de votre site un fichier nommé .gitignore ayant le contenu suivant.
vendor/
Cela permet d'exclure ce répertoire du contrôle de code source. 
2 - Mise à jour de l'arborescence
Créez dans MicroCMS  les sous-répertoires suivants :
app, qui contiendra la configuration de l'application Silex ;
db, qui contiendra les scripts SQL de création de la base de données ;
src, qui contiendra les fichiers source PHP ;
views, qui contiendra les vues de l'application ;
web, qui contiendra les fichiers accessibles aux clients web.
Déplacez le fichier view.php dans le sous-répertoire views, puis déplacez le fichier model.php dans le sous-répertoire src. 
Déplacez ensuite les fichiers index.php et microcms.css dans le sous-répertoire web. 
Enfin, créez ou déplacez dans le sous-répertoire db les scripts SQL database.sql, structure.sql et content.sql.
Dans le sous-répertoire app, créez un fichier routes.php avec le contenu ci-dessous.
<?php

// Home page
$app->get('/', function () {
    require '../src/model.php';
    $articles = getArticles();

    ob_start();             // start buffering HTML output
    require '../views/view.php';
    $view = ob_get_clean(); // assign HTML output to $view
    return $view;
});


Silex permet de définir des routes, c'est-à-dire des points d'entrée dans l'application. À chaque route est associée une réponse construite par notre code. La route ci-dessus correspond à l'URL racine de l'application (/). La fonction anonyme associée à cette route utilise la fonctiongetArticles définie dans le fichier model.php pour récupérer la liste des articles.
Une fonction qui gère une route est appelée un contrôleur. 
Chaque contrôleur Silex doit renvoyer explicitement une réponse. Les fonctions PHP ob_start et ob_get_clean permettent de récupérer le résultat de l'appel à require '../views/view.php' (autrement dit la vue HTML générée) dans une variable nommée$view. Cette variable est renvoyée par le contrôleur.
Éditez index.php et remplacez son contenu par le code source ci-dessous.
<?php


require_once __DIR__.'/../vendor/autoload.php';


$app = new Silex\Application();


// enable the debug mode
$app['debug'] = true;


require __DIR__.'/../app/routes.php';


$app->run();


Ce fichier constitue le contrôleur frontal de notre application web. Il centralise la gestion des requêtes HTTP entrantes. Dans ce fichier, on instancie l'objet Silex principal$app, on active les informations de débogage puis on inclut la définition des routes de l'application (fichier routes.php).‌

Toujours dans web, créez un nouveau fichier texte nommé .htaccess contenant le texte ci-dessous. Ce fichier permet de configurer le serveur web Apache pour rediriger toutes les requêtes entrantes vers index.php.

# Redirect incoming URLs to index.php


<IfModule mod_rewrite.c>


   Options -MultiViews


   RewriteEngine On


   RewriteCond %{REQUEST_FILENAME} !-f


   RewriteRule ^ index.php [QSA,L]


</IfModule>


3 - Définition d'un hôte virtuel
Enfin, il nous reste à configurer un hôte virtuel afin que l'application puisse répondre à une URL de la forme http://monsite. En suivant l'exemple donné dans le cours Premiers pas avec le framework PHP Silex, éditez le fichier de configuration Apache httpd-vhosts.conf et ajoutez le contenu ci-après en adaptant les lignes commençant par DocumentRoot et Directory à votre configuration locale. Voici un exemple de configuration avec XAMPP sous Windows.

<VirtualHost *:80>
    DocumentRoot "C:\xampp\htdocs\MicroCMS\web"
    ServerName microcms
    <Directory "C:\xampp\htdocs\MicroCMS\web">
        AllowOverride All
    </Directory>
</VirtualHost>

Vous aurez noté que les URL de notre application Web commencent toutes par /web/index.php , ce qui est peu pratique. Plutôt que devoir écrire http://localhost/hello-world-silex/web/index.php/hello/Bob, on aimerait pouvoir écrire http://localhost/hello-world-silex/hello/Bob.
Nous allons aller plus loin et faire en sorte que l'application réponde à une URL de la forme http://hello-world-silex/hello/Bob. Cela nécessite de modifier la configuration du serveur Web local, qui sera Apache dans notre exemple.
Définition d'un hôte virtuel
La première étape est de définir un hôte virtuel (virtual host). Le principe des hôtes virtuels est de définir plusieurs serveurs logiques sur un même serveur physique. Vous trouverez tous les détails sur les hôtes virtuels dans la documentation Apache.
Sous Windows et Mac OS X
La configuration d'un hôte virtuel  Apache sous Windows et Mac OS X nécessite l'édition du fichier de configuration httpd-vhosts.conf . Son emplacement dépend de l'installation d'Apache :
c:\xampp\apache\conf\extra  avec XAMPP sous Windows.
/Applications/MAMP/conf/apache/extras  avec MAMP sous Mac OS X.
Ouvrez ce fichier avec un éditeur de texte puis ajoutez le contenu ci-dessous à la fin en adaptant les lignes commençant par DocumentRoot et Directory  à votre configuration locale.
<VirtualHost *:80>
   DocumentRoot "C:\xampp\htdocs"
   ServerName localhost
</VirtualHost>


<VirtualHost *:80>
   DocumentRoot "C:\xampp\htdocs\hello-world-silex\web"
   ServerName hello-world-silex
   <Directory "C:\xampp\htdocs\hello-world-silex\web">
      AllowOverride all
   </Directory>
</VirtualHost>


Le premier hôte virtuel redéfinit localhost, ce qui est nécessaire quand on rajoute des hôtes virtuels sous Apache. Le second crée un hôte virtuel associé au nom hello-world-silex  et dont la racine est le répertoire hello-world-silex/web .
Ensuite, éditez le fichier principal de configuration apache. Il se nomme httpd.conf  et se trouve dans le répertoire de configuration d'Apache (exemples :  c:\xampp\apache\conf avec XAMPP sous Windows, /Applications/MAMP/conf/apache avec MAMP sous Mac OS X).
Dans ce long fichier, vérifiez que la ligne incluant le fichier httpd-vhosts.conf  est bien décommentée (absence de '#' au début de la ligne), et décommentez-là au besoin.
 
Voici le résultat à obtenir avec XAMPP sous Windows :
# Virtual hosts
Include conf/extra/httpd-vhosts.conf


Et voici le résultat à obtenir avec MAMP sous Mac OS X.
# Virtual hosts
Include /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf


Pour que la nouvelle configuration soit prise en compte, il faut redémarrer le serveur Web Apache. Faites-le maintenant. 
Sous Linux
La configuration d'un hôte virtuel Apache sous Linux se fait en ajoutant un fichier de configuration dans le répertoire /etc/apache2/sites-available . La procédure est décrite en détail dans la documentation Ubuntu. Le fichier à ajouter se nommera ici hello-world-silex.conf et pourra avoir le contenu suivant (dans cet exemple, le dossier servi par Apache est le répertoire /var/www/html).
<VirtualHost *:80>
   ServerName hello-world-silex
   DocumentRoot /var/www/html/hello-world-silex/web
   <Directory "/var/www/html/hello-world-silex/web">
      AllowOverride all
      Require all granted
   </Directory>
</VirtualHost>


Il faut ensuite activer le nouvel hôte virtuel en lançant la commande suivante.
sudo a2ensite hello-world-silex.conf
Elle a pour effet de créer dans le répertoire /etc/apache2/sites-enabled un lien symbolique  qui pointe vers le fichier/etc/apache2/sites-available/hello-world-silex.conf .
Pour que la nouvelle configuration soit prise en compte, il faut redémarrer le serveur Web Apache. Faites-le maintenant à l'aide de la commande ci-dessous.
sudo service apache2 restart
Modification des informations DNS
Une fois l'hôte virtuel créé et quel que soit votre système d'exploitation, il faut ajouter une information au fichier hosts local pour que la résolution DNS pointe sur la machine locale (127.0.0.1). Là encore, l'emplacement de ce fichier dépend de votre système :
C:\Windows\System32\drivers\etc\hosts  sous Windows.
/etc/hosts  sous Mac OS X et Linux.
La modification de ce fichier nécessite des droits d'administrateur. Si vous êtes sous Windows, lisez cet article pour savoir comment les obtenir. 
Editez ce fichier afin d'y ajouter la ligne ci-dessous.
127.0.0.1   hello-world-silex


Une fois le fichier hosts  modifié, l'application doit répondre à l'URL http://hello-world-silex avec le message "Hello world".



