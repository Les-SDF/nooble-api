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