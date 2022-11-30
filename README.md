<!-- Démarrage -->
## Démarrage

Vous retrouverez ici la façon dont laquelle nous avons procéder pour la réalisation de notre projet.

###Installation

Vous trouverez ci-dessous un exemple de la façon dont vous pouvez expliquer à votre public comment installer et configurer votre application.

1. Cloner le dépôt (dans Symfony)
    ```sh
    git clone https://github.com/Allaneee/IQuestions.git
    ```
    
2. Modifier le fichier .env
    ```sh
    DATABASE_URL="mysql://user:password@127.0.0.1:3306/IQuestions?serverVersion=mariadb-10.4.24&charset=utf8mb4" (MariaDB)
    MAILER_DSN=gmail://user@gmail.com:password@default?verify_peer=0 (Gmail)
    ```

### Requis

Ceci est un exemple de la liste des choses dont vous avez besoin pour utiliser notre projet et comment les installer.

Upload d'images :
   ```sh
   composer require vich/uploader-bundle
   ```
   
   Interface administrateur :
   ```sh
   composer require admin
   composer require symfony/security-bundle
   ```
   
   Envoi de mail :
   ```sh
   composer require symfony/mailer
   ```

  En cas d'erreur, vous pouvez essayer la commande :
   ```sh
   composer install
   ```
### Informations diverse :

Le premier utilisateur créer est obligatoirement un administrateur.
