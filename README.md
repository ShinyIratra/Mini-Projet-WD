# Mini-Projet-WD : Gestionnaire d'Articles

## À propos de ce projet
Ce projet est un CMS léger (Front-office et Back-office) permettant la publication, la gestion et la consultation d'articles. Il a été conçu de A à Z avec un back-office pour l'administration et un front-office pour les lecteurs.

**Auteurs :** 
- ETU003264
- ETU003332

## Stack Technique
Les technologies clés utilisées dans ce projet sont :
- **Backend :** PHP pur 
- **Base de données :** PostgreSQL
- **Frontend :** HTML5, CSS3, JavaScript
- **Éditeur de texte riche :** [TinyMCE](https://www.tiny.cloud/) (Intégré pour la rédaction formatée des articles dans le back-office)
- **Environnement & Déploiement :** Docker, Docker Compose

## Comment lancer le projet

1. **Prérequis :**
   Assurez-vous d'avoir installé [Docker](https://www.docker.com/) et [Docker Compose](https://docs.docker.com/compose/) sur votre machine.

2. **Démarrer l'environnement :**
   Ouvrez un terminal à la racine du projet et lancez la commande suivante pour construire et démarrer les conteneurs en arrière-plan :
   ```bash
   docker-compose up -d --build
   ```

3. **Accéder à l'application :**
   Une fois les conteneurs démarrés, vous pouvez y accéder depuis votre navigateur :
   - **Front-office (Publique) :** `http://localhost:8080/`
   - **Back-office (Administration) :** `http://localhost:8080/pages/backoffice/login.php`
