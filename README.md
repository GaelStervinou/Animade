# 3IW1-2022

Ce projet a été réalisé par Ilies SLIM, Thomas COOPER et Gaël STERVINOU dans le cadre du bachelor Igénierie du Web de l'ESGI.


Pour utiliser le CMS, il vous suffira de récupérer la branche `production` du projet.

Une fois la branche récupérée, vous pouvez la déployer sur votre serveur en lançant la commande `docker-compose up -d`. Docker-compose est requis pour ce projet.

S'ils manquent, il vous faudra créer les dossiers www/assets, www/assets/images et www/assets/images/administration.






**Design Patterns** :

- Singleton : www/Core/BaseSQL.php, ligne 20
  - Il est utilisé afin de ne pas avoir plusieurs connexions à la base de données.
- Builder : www/Core/BaseSQL.php, ligne 106
  - Il est utilisé afin de construire nos requêtes facilement mais surtout efficacement et de manière sécurisée.
- Observer : www/Core/Observer, les 2 fichiers dans le dossier. Implémenté dans www/Controller/Signalement.class.php, ligne 30
  - Il permet de notifier par mail automatiquement tous les administrateurs qu'un signalement a été effectué.
- Interface : www/Core/QueryBuilder.class.php
  - Nous l'avons mis en place dans le but de développer, à l'avenir, des builders pour chaque base de données ( pgsql notammment ). Nous n'avons évidemment pas eu le temps de le faire, mais l'idée était d'offrir la possibilité d'utiliser n'importe quelle base de données MySQL.

