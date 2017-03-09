# API-Agricool

GET -> http://localhost:9001/api/v1/containers 

GET -> http://localhost:9001/api/v1/containers/{id} 

POST -> http://localhost:9001/api/v1/containers 

PUT -> http://localhost:9001/api/v1/containers/{id} 

DELETE -> http://localhost:9001/api/v1/containers/{id}

Authentification rudimentaire (voir plus bas) et trois utilisateurs: pauline (User), admin et container. Tous les mdp sont 'encodedpassword'.

La table containers comporte un id, un nom (regroupement), une plante (qui pousse), et quelques données de monitoring utiles : temperature, humidité, temps depuis la plantaison (en ms) et la geoloc.

Les utilisateurs Admin peuvent ajouter et supprimer des containers, et les utilisateurs Containers peuvent s'updater. Les utilisateurs normaux peuvent voir la liste des containers et le detail d'un containers.

#Exemples curl:

GET (voir tous): curl http://localhost:9001/api/v1/containers -H 'Content-Type: application/json' -H "X-AUTH-TOKEN: pauline:encodedpassword" -w "\n" 
GET (voir les details un container par son id): curl http://localhost:9001/api/v1/containers/1 -H 'Content-Type: application/json' -H "X-AUTH-TOKEN: pauline:encodedpassword" -w "\n" 
POST (ajouter un container): curl -X POST http://localhost:9001/api/v1/containers -d '{"name":"Whatever", "plant":"Carrot", "temperature":"0","humidity":"0", "timeOfPlantation":"1", "lat":"0","lng":"0"}' -H 'Content-Type: application/json' -H "X-AUTH-TOKEN: admin:encodedpassword" -w "\n" 
PUT (updater le container #1): curl -X PUT http://localhost:9001/api/v1/containers/1 -d '{"temperature":"35", "humidity":"60"}' -H 'Content-Type: application/json' -H "X-AUTH-TOKEN: container:encodedpassword"  -w "\n" 
DELETE: curl -X DELETE http://localhost:9001/ap'i/v1/containers/1 -H 'Content-Type: application/json' -H "X-AUTH-TOKEN: admin:encodedpassword" -w "\n"

#Limitations:

Evidement ce n'est qu'un poc, avec plein de limitations. Entre autre :  balancer les mdp en clair sur le network c'est pas top du tout (captain obv), les users doivent être dans leur propre db au lieu d'être codés en dur (je suis en train, je vous ferai une update si je m'en sors), les updates des containers pourraient être stockés dans leur propre database histoire de pouvoir visualiser un historique... Sans parler des features à rajouter.

Tout ça saupoudré d'un gros "Je code en PHP depuis moins de 48h" XD. J'ai bricolé a partir de divers boilerplates.

#Pour faire tourner :

composer install (pour installer les dépendances)
sqlite3 app.db < resources/sql/schema.sql (pour init la base de données avec quelques containers)
php -S 0:9001 -t web/ (pour lancer l'appli)
