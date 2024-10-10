# TP Laravel - Installation et Configuration

Bienvenue dans le guide d'installation et de configuration pour le projet **tp-laravel**. Ce guide vous aidera à configurer votre environnement de développement avec **Homestead** et à lancer l'application rapidement.

## Prérequis

Assurez-vous d'avoir installé les éléments suivants :
- [Vagrant](https://developer.hashicorp.com/vagrant/install?product_intent=vagrant)
- [VirtualBox](https://www.virtualbox.org/)
- Git

## Installation de Homestead

### Étape 1 : Cloner le dépôt Homestead

Utilisez les commandes suivantes en fonction de votre système d'exploitation :

```bash
# Windows
git clone https://github.com/laravel/homestead.git /Homestead

# macOS / Linux
git clone https://github.com/laravel/homestead.git ~/Homestead
```

### Étape 2 : Initialiser Homestead

Toujours dans le terminal, initialisez Homestead avec la commande appropriée :

```bash
# macOS / Linux
bash init.sh

# Windows
init.bat
```

## Configuration de Homestead

Après l'initialisation, un fichier `Homestead.yaml` sera créé dans le dossier Homestead. Modifiez-le comme suit :

### Exemple de configuration

```yaml
authorize: ~/.ssh/id_rsa.pub

keys:
  - ~/.ssh/id_rsa

folders:
  - map: ~/code
    to: /home/vagrant/code

sites:
  - map: tp-laravel.test
    to: /home/vagrant/code/tp-laravel/public

databases:
  - tp-laravel
  - tp-laravel_test
```

> **Note** : Sur Windows, utilisez `.ssh/id_rsa.pub`, `.ssh/id_rsa` et `code` comme chemins.

### Modifier le fichier hosts

Ajoutez la ligne suivante à votre fichier hosts :

```bash
# macOS / Linux
/etc/hosts

# Windows
C:\Windows\System32\drivers\etc\hosts
```

```plaintext
192.168.56.56 tp-laravel.test
```

### Création de dossiers

Dans le dossier Homestead, créez les dossiers suivants si ce n’est pas déjà fait :
- **code** : contiendra mon repository, qu'il faudra cloner.
- **.ssh** : contiendra vos clés SSH (id_rsa et id_rsa.pub), qu'il faut créer à la main, si elle ne le sont pas déjà.

## Lancer Homestead

> **Attention** : Ne suivez cette étape que si votre VM n'existe pas encore.

1. Déplacez-vous dans le dossier Homestead via le terminal.
2. Exécutez la commande suivante :

```bash
vagrant up
```

## Ajouter le Projet à Homestead (Si déjà configuré)

Clonez le dépôt de votre projet dans le dossier **code**, puis redémarrez votre VM :

```bash
vagrant reload
```

Accédez à votre VM :

```bash
vagrant ssh
```

## Configuration du Projet Laravel

### Installer les dépendances

Dans le dossier de votre projet :

```bash
composer require laravel/homestead --dev
composer update
```

### Fichier .env

Créez un fichier `.env` s'il n'existe pas et configurez-le en utilisant les bonnes variables comme suit :

```env
APP_NAME="Cours Laravel"
APP_ENV=local
APP_URL=http://tp-laravel.test
DB_DATABASE=tp-laravel
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Vérifiez les autres paramètres en fonction de votre configuration.

### Migrer et Seeding de la Base de Données

```bash
php artisan migrate
php artisan db:seed
```

### Problèmes avec Vite

Si des erreurs liées à Vite apparaissent lors du chargement d'une page, executez ces commandes dans le dossier Homestead dans le terminal en ssh :

```bash
rm -rf node_modules
npm install
npm run build
```

> **Note** : Si vous avez des erreurs avec ces lignes de commandes, il faut installer Node.js sur Windows, consultez [ce guide](https://kinsta.com/fr/blog/comment-installer-node-js/) pour plus de détails. Une fois intallé, dans le terminal en administrateur, déplacez-vous au dossier Homestead et executer les deux dernières commandes indiqué au-dessus. 

## Compte Admin

Un compte administrateur est déjà configuré au moment de la migration :

- **Email** : admin@gmail.com
- **Mot de passe** : password

---

Ce README offre un aperçu complet de l'installation et la configuration de votre projet Laravel avec Homestead. N'hésitez pas à l'adapter ou à y ajouter des sections supplémentaires si nécessaire.
