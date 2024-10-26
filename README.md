# Dépôt du projet : [GitHub](https://github.com/qriosserra/nooble)
#### HTTPS : `https://github.com/qriosserra/nooble.git`
#### SSH : `git@github.com:qriosserra/nooble.git`

# Mettre en place le projet

- S'assurer que le `.env` est bien configuré
- Exécuter les commandes suivantes :

```shell
docker exec -it but3-web-container-server-1 bash
```

```shell
cd nooble/
```

```shell
composer install
```

```shell
php bin/console doctrine:database:create
```

```shell
php bin/console doctrine:schema:update --force
```

```shell
php bin/console doctrine:fixtures:load --append
```

```shell
php bin/console lexik:jwt:generate-keypair
```

# Investissement de chacun

- #### Peter POIRRIER : 33%
    - Ajout du système de connexion par JWT
- #### Nikhil RAM : 33%
    - Conception de la structure de données
    - Ecriture des règles de sécurité pour les entités
    - Conversion des ManyToMany en ManyToOne/OneToMany
- #### Quentin RIOS-SERRA : 33%
    - Initialisation du projet
    - Création des entités
    - Voters

# Routes de l'application

| Méthode | Route                                                                 | Description                               |
|---------|-----------------------------------------------------------------------|-------------------------------------------|
| POST    | [`/api/auth`](http://localhost:80/nooble/public/api/auth)             | Connexion                                 |
| GET     | [`/api/users`](http://localhost:80/nooble/public/api/users)           | Récupération de la liste des utilisateurs |
| GET     | [`/api/users/{id}`](http://localhost:80/nooble/public/api/users/{id}) | Récupération des données utilisateur      |
| POST    | [`/api/users`](http://localhost:80/nooble/public/api/users)           | Création d'un utilisateur                 |
| PATCH   | [`/api/users/{id}`](http://localhost:80/nooble/public/api/users/{id}) | Modification d'un utilisateur             |
| DELETE  | [`/api/users/{id}`](http://localhost:80/nooble/public/api/users/{id}) | Supression d'un utilisateur               |