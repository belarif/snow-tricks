Installation du projet :

Copie du projet en local :
1.1- Cliquez sur le bouton "code", puis sur la section HTTPS qui affiche l'url
suivante : https://github.com/belarif/snowTricks.git copiez cette url à utiliser pour installer le projet en local.

1.2- Ouvrez le terminal de votre IDE. Si vous utilisez le server WampServer64, positionnez vous sur le chemin : c:
/wamp64/www grace à la commande: cd Comme suit: cd c:/wamp64/www Si vous utilisez un server autre que WampServer64,
positionnez vous sur le chemin qui permettra l'exécution du site.

1.3- Sur le meme chemin, tapez la commande suivante pour cloner le projet : git
clone https://github.com/belarif/snowTricks.git Après exécution de la commande, le projet sera copié sur votre
ordinateur

Installation des dépendances : Toujours depuis votre terminal, exécutez la commande suivant : composer install

Base de données : Depuis votre SGBD, importez le ficher .sql fourni, qui contient la base de données du projet.

Connexion à la base de données : dans le fichier : .env, qui se trouve à la racine du projet, configurez la connexion

à la base de données sur la ligne DATABASE_URL suivante:

DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/DBNAME?serverVersion=5.7"

Remplacez les valeurs des variables `USER` et `PASSWORD` par les identifiants que vous utilisez pour vous connecter à
votre SGBD ,la valeur de la variable `DBNAME` par le nom du fichier .sql fourni.

Accès au site :

Depuis la ligne de commande, exécutez la commande suivante: `yarn run encore dev --watch` pour charger les fichiers JS,
CSS et les images dans le repertoire public

Depuis la ligne de commande, exécutez la commande suivante: `php -S localhost:8000 -t public/` pour lancer le site.

Page d'accueil : http://localhost:8000/snow-tricks/accueil

Espace administration du site : http://localhost:8000/snow-tricks/admin/dashboard