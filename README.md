## Installation du projet

### Copie du projet en local 
1. Cliquez sur le bouton "code", puis sur la section HTTPS qui affiche l'url
suivante : https://github.com/belarif/snowTricks.git copiez cette url à utiliser pour installer le projet en local.

2. Ouvrez le terminal de votre IDE. Si vous utilisez le server WampServer64, positionnez vous sur le chemin : `c:
/wamp64/www` grace à la commande: cd Comme suit: `cd c:/wamp64/www `Si vous utilisez un server autre que WampServer64,
positionnez vous sur le chemin qui permettra l'exécution du site.

3. Sur le meme chemin, tapez la commande suivante pour cloner le projet : git
clone https://github.com/belarif/snowTricks.git Après exécution de la commande, le projet sera copié sur votre
ordinateur

### Installation des dépendances
Toujours depuis votre terminal, exécutez la commande suivant : `composer install`

### Création de la base de données
1. Modifier le fichier `.env` pour adapater les accès de votre SGBD
2. Création de la BD: `php bin/console doctrine:schema:create --force`
3. Création du schema de la BD: `php bin/console doctrine:schema:update --force` (à remplacer par la commande `doctrine:migrations:migrate`)
4. Création des fixtures

### Installation des resources publiques
1. Installer yarn: `npm install --global yarn`
2. Installer encore: `yarn install`
3. Charger les fichiers public: `yarn run encore dev --watch`

### Loader les fixtures
`php bin/console doctrine:fixtures:load`

### Lancement de l'app
`php -S localhost:8000 -t public/` pour lancer le site.

Page d'accueil : http://localhost:8000/snow-tricks/accueil

Espace administration du site : http://localhost:8000/snow-tricks/admin/dashboard
