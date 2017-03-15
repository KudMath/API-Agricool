# API-Agricool

GET -> http://localhost:9001/api/v1/containers 

GET -> http://localhost:9001/api/v1/containers/{id} 

POST -> http://localhost:9001/api/v1/containers 

PUT -> http://localhost:9001/api/v1/containers/{id} 

DELETE -> http://localhost:9001/api/v1/containers/{id}

composer install (pour installer les dépendances)
sqlite3 app.db < resources/sql/schema.sql (pour init la base de données avec quelques containers)
php -S 0:9001 -t web/ (pour lancer l'appli)
