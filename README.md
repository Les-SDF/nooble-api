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
php bin/console doctrine:database:drop --force
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
    - Conception de la structure de données
    - Ajout du système de connexion par JWT
    - Création des getCollection
    - ReadMe
- #### Nikhil RAM : 33%
    - Conception de la structure de données
    - Ecriture des règles de sécurité pour les entités
    - Conversion des ManyToMany en ManyToOne/OneToMany
- #### Quentin RIOS-SERRA : 33%
    - Conception de la structure de données
    - Initialisation du projet
    - Création des entités
    - Voters

# Choix d'implementation

Dans notre implémentation, l'inscription à un événement se fait de la manière suivante :

- **Inscription par l'équipe** : Ce sont les équipes qui s'inscrivent aux événements par le biais de l'entité coordinatrice `TeamEvent`. Chaque membre d'une équipe peut inscrire son équipe à un événement.

- **Désinscription** : Un membre de l'équipe ne peut pas désinscrire l'équipe de l'événement. La désinscription doit être effectuée par l'organisateur de l'événement. Si un membre ne souhaite plus participer à un événement, il doit quitter l'équipe.

# Routes de l'application

### Authentification

| Méthode | Route                                                                 | Description       |
|---------|-----------------------------------------------------------------------|-------------------|
| POST    | [`/api/auth`](http://localhost:80/nooble/public/api/auth)             | Connexion         |

### Confrontation

La classe **Confrontation** représente une entité coordinatrice entre un **événement (Event)** et une **participation (Participation)**, indiquant les matchs ou les affrontements qui se déroulent dans le cadre d'un événement donné.

| Méthode | Route                                                                                               | Description                                                                          |
|---------|-----------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------|
| GET     | [`/api/event/{id}/confrontations`](http://localhost:80/nooble/public/api/event/{id}/confrontations) | Récupération des données d'une ressource Confrontation pour un événement spécifique. |
| GET     | [`/api/game/{id}/confrontations`](http://localhost:80/nooble/public/api/game/{id}/confrontations)   | Récupération des données d'une ressource Confrontation pour un jeu spécifique.       |
| PATCH   | [`/api/confrontations/{id}`](http://localhost:80/nooble/public/api/confrontations/{id})             | Modification d'une ressource Confrontation                                           |

### Event

| Méthode | Route                                                                   | Description                                         |
|---------|-------------------------------------------------------------------------|:----------------------------------------------------|
| GET     | [`/api/events/`](http://localhost:80/nooble/public/api/events/{id})     | Récupération de la liste des événements             |
| GET     | [`/api/events/{id}`](http://localhost:80/nooble/public/api/events/{id}) | Récupération des données d'une ressource événements |
| POST    | [`/api/events`](http://localhost:80/nooble/public/api/events)           | Création d'un événement.                            |
| PATCH   | [`/api/events/{id}`](http://localhost:80/nooble/public/api/events/{id}) | Modification d'un événement                         |
| DELETE  | [`/api/events/{id}`](http://localhost:80/nooble/public/api/events/{id}) | Suppression d'un événement                          |

### EventReward

La classe **EventReward** représente une entité coordinatrice entre un **événement (Event)** et ses **récompenses (PrizePacks)**. Elle permet de gérer les différents prix attribués lors d'un événement.

| Méthode | Route                                                                                           | Description                                                                      |
|---------|-------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------|
| GET     | [`/api/event/{id}/eventRewards`](http://localhost:80/nooble/public/api/event/{id}/eventRewards) | Récupération de la liste de ressources EventReward pour un événement spécifique. |
| POST    | [`/api/eventRewards`](http://localhost:80/nooble/public/api/eventRewards)                       | Création d'un EventReward.                                                       |

### EventSponsor

La classe **EventSponsor** représente une entité coordinatrice entre un **événement (Event)** et ses **sponsors (Sponsor)**. Elle permet de gérer les différentes entreprises ou entités qui soutiennent un événement.

| Méthode | Route                                                                                                 | Description                                                                       |
|---------|-------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------|
| GET     | [`/api/event_sponsors/{id}`](http://localhost:80/nooble/public/api/event_sponsors/{id})               | Récupération des données d'une ressource EventSponsor.                            |
| GET     | [`/api/event/{id}/eventSponsors`](http://localhost:80/nooble/public/api/event/{id}/eventSponsors)     | Récupération de la liste de ressources EventSponsor pour un événement spécifique. |
| GET     | [`/api/sponsor/{id}/eventSponsors`](http://localhost:80/nooble/public/api/sponsor/{id}/eventSponsors) | Récupération de la liste de ressources EventSponsor pour un sponsor spécifique.   |
| POST    | [`/api/event_sponsors`](http://localhost:80/nooble/public/api/event_sponsors)                         | Création d'une ressource EventSponsor.                                            |
| DELETE  | [`/api/event_sponsors/{id}`](http://localhost:80/nooble/public/api/event_sponsors/{id})               | Suppression d'une ressource EventSponsor.                                         |

### Game

| Méthode | Route                                                                 | Description                       |
|---------|-----------------------------------------------------------------------|-----------------------------------|
| GET     | [`/api/games`](http://localhost:80/nooble/public/api/games)           | Récupération de la liste des jeux |
| GET     | [`/api/games/{id}`](http://localhost:80/nooble/public/api/games/{id}) | Récupération des données d'un jeu |
| POST    | [`/api/games`](http://localhost:80/nooble/public/api/games)           | Création d'un jeu                 |
| PATCH   | [`/api/games/{id}`](http://localhost:80/nooble/public/api/games/{id}) | Modification d'un jeu             |
| DELETE  | [`/api/games/{id}`](http://localhost:80/nooble/public/api/games/{id}) | Suppression d'un événement        |

### Manager

La classe **Manager** représente une entité coordinatrice entre un **utilisateur (User)** et un **événement (Event)**. Elle désigne des personnes ayant un rôle particulier qui leur permet de modérer l'événement et d'apporter des modifications. Contrairement aux créateurs de l'événement, les managers agissent en tant que membres du personnel ou modérateurs, assurant la gestion et le bon déroulement de l'événement.

| Méthode | Route                                                                       | Description                                       |
|---------|-----------------------------------------------------------------------------|---------------------------------------------------|
| GET     | [`/api/managers`](http://localhost:80/nooble/public/api/managers)           | Récupération de la liste de ressources Manager.   |
| GET     | [`/api/managers/{id}`](http://localhost:80/nooble/public/api/managers/{id}) | Récupération des données d'une ressource Manager. |
| POST    | [`/api/managers`](http://localhost:80/nooble/public/api/managers)           | Création d'une ressource Manager.                 |
| PATCH   | [`/api/managers/{id}`](http://localhost:80/nooble/public/api/managers/{id}) | Modification d'une ressource Manager.             |
| DELETE  | [`/api/managers/{id}`](http://localhost:80/nooble/public/api/managers/{id}) | Suppression d'une ressource Manager.              |

### Member

La classe **Member** représente une entité coordinatrice entre un **utilisateur (User)** et une **équipe (Team)**, indiquant que le joueur appartient à une équipe.

| Méthode | Route                                                                                 | Description                                                               |
|---------|---------------------------------------------------------------------------------------|---------------------------------------------------------------------------|
| GET     | [`/api/members/{id}`](http://localhost:80/nooble/public/api/members/{id})             | Récupération des données d'une ressource Membre                           |
| GET     | [`/api/teams/{id}/members`](http://localhost:80/nooble/public/api/teams/{id}/members) | Récupération d'une liste de ressources Member pour une équipe spécifique. |
| POST    | [`/api/members`](http://localhost:80/nooble/public/api/members)                       | Création d'une ressource Membre                                           |
| DELETE  | [`/api/members/{id}`](http://localhost:80/nooble/public/api/members/{id})             | Suppression d'une ressource Membre.                                       |

### Participation

La classe **Participation** représente une entité coordinatrice entre une **confrontation (Confrontation)** et une **équipe (Team)**. Elle permet de suivre les performances et les résultats de l'équipe dans divers événements.

| Méthode | Route                                                                                                                 | Description                                                                              |
|---------|-----------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------|
| GET     | [`/api/participations/{id}`](http://localhost:80/nooble/public/api/participations/{id})                               | Récupération des données d'une ressource Participation.                                  |
| GET     | [`/api/confrontations/{id}/participations`](http://localhost:80/nooble/public/api/confrontations/{id}/participations) | Récupération de la liste de ressources Participation pour une confrontations spécifique. |
| GET     | [`/api/teams/{id}/participations`](http://localhost:80/nooble/public/api/teams/{id}/participations)                   | Récupération de la liste de ressources Participation pour une équipe spécifique.         |
| POST    | [`/api/participations`](http://localhost:80/nooble/public/api/participations)                                         | Création d'une ressource Participation                                                   |
| DELETE  | [`/api/participations/{id}`](http://localhost:80/nooble/public/api/participations/{id})                               | Suppression d'une ressource Participation.                                               |

### PrizePack

La classe **PrizePack** représente une entité coordinatrice entre une **récompense (Reward)** et un lot de **récompense (EventReward)**. Chaque PrizePack peut contenir une ou plusieurs récompenses associées à un événement.

| Méthode | Route                                                                                                   | Description                                                                      |
|---------|---------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------|
| GET     | [`/api/eventReward/{id}/prizePacks`](http://localhost:80/nooble/public/api/eventReward/{id}/prizePacks) | Récupération de la liste de ressources PrizePack pour une recompense d'événement |
| GET     | [`/api/reward/{id}/prizePacks`](http://localhost:80/nooble/public/api/reward/{id}/prizePacks)           | Récupération de la liste de ressources PrizePack pour une récompense             |
| PATCH   | [`/api/prize_packs/{id}`](http://localhost:80/nooble/public/api/prize_packs/{id})                       | Modification d'une ressource PrizePack.                                          |
| DELETE  | [`/api/prize_packs/{id}`](http://localhost:80/nooble/public/api/prize_packs/{id})                       | Suppression d'une ressource PrizePack.                                           |

### Recipient

La classe **Recipient** représente une entité coordinatrice entre une **équipe (Team)** et une **récompense d'événement (EventReward)**. Cela reflète les récompenses qu'une équipe a gagnées durant un tournoi ou un événement spécifique.

| Méthode | Route                                                                                                   | Description                                                                      |
|---------|---------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------|
| GET     | [`/api/recipients/{id}`](http://localhost:80/nooble/public/api/recipients/{id})                         | Récupération des données d'une ressource Recipient.                              |
| GET     | [`/api/eventReward/{id}/recipients`](http://localhost:80/nooble/public/api/eventReward/{id}/recipients) | Récupération de la liste de ressources Recipient pour une récompense d'événement |
| GET     | [`/api/teams/{id}/recipients`](http://localhost:80/nooble/public/api/teams/{id}/recipients)             | Récupération de la liste de ressources Recipient pour une équipe                 |
| POST    | [`/api/recipients`](http://localhost:80/nooble/public/api/recipients)                                   | Création d'une ressource Recipient                                               |
| DELETE  | [`/api/recipients/{id}`](http://localhost:80/nooble/public/api/recipients/{id})                         | Suppression d'une ressource Recipient.                                           |

### Register

La classe **Register** représente une entité coordinatrice entre un **utilisateur (User)** et un **événement (Event)**. Elle permet de suivre les utilisateurs qui se sont inscrits pour assister à un événement spécifique. Ils sont donc des spectateurs et non des participants.

| Méthode | Route                                                                                     | Description                                                         |
|---------|-------------------------------------------------------------------------------------------|---------------------------------------------------------------------|
| GET     | [`/api/event/{id}/registers`](http://localhost:80/nooble/public/api/event/{id}/registers) | Récupération de la liste de ressources Register pour un événement   |
| GET     | [`/api/users/{id}/registers`](http://localhost:80/nooble/public/api/users/{id}/registers) | Récupération de la liste de ressources Register pour un utilisateur |
| PATCH   | [`/api/registers/{id}`](http://localhost:80/nooble/public/api/registers/{id})             | Modification d'une ressource Register.                              |

### Reward

| Méthode | Route                                                                     | Description                               |
|---------|---------------------------------------------------------------------------|-------------------------------------------|
| GET     | [`/api/rewards/{id}`](http://localhost:80/nooble/public/api/rewards/{id}) | Récupération des données d'une récompense |
| POST    | [`/api/rewards`](http://localhost:80/nooble/public/api/rewards)           | Création d'une récompense                 |
| PATCH   | [`/api/rewards/{id}`](http://localhost:80/nooble/public/api/rewards/{id}) | Modification d'une récompense             |
| DELETE  | [`/api/rewards/{id}`](http://localhost:80/nooble/public/api/rewards/{id}) | Suppression d'une récompense              |

### Sponsor

| Méthode | Route                                                                       | Description                           |
|---------|-----------------------------------------------------------------------------|---------------------------------------|
| GET     | [`/api/sponsors/{id}`](http://localhost:80/nooble/public/api/sponsors/{id}) | Récupération des données d'un sponsor |
| POST    | [`/api/sponsors`](http://localhost:80/nooble/public/api/sponsors)           | Création d'un sponsor                 |
| PATCH   | [`/api/sponsors/{id}`](http://localhost:80/nooble/public/api/sponsors/{id}) | Modification d'un sponsor             |
| DELETE  | [`/api/sponsors/{id}`](http://localhost:80/nooble/public/api/sponsors/{id}) | Suppression d'un sponsor              |

### Team

| Méthode | Route                                                                 | Description                           |
|---------|-----------------------------------------------------------------------|---------------------------------------|
| GET     | [`/api/teams/{id}`](http://localhost:80/nooble/public/api/teams/{id}) | Récupération des données d'une équipe |
| POST    | [`/api/teams`](http://localhost:80/nooble/public/api/teams)           | Création d'une équipe                 |
| PATCH   | [`/api/teams/{id}`](http://localhost:80/nooble/public/api/teams/{id}) | Modification d'une équipe             |
| DELETE  | [`/api/teams/{id}`](http://localhost:80/nooble/public/api/teams/{id}) | Suppression d'une équipe              |

### TeamEvent

La classe **TeamEvent** représente une entité coordinatrice entre une **équipe (Team)** et un **événement (Event)**. Elle permet de gérer les équipes qui participent à un événement spécifique.

| Méthode | Route                                                                             | Description                                         |
|---------|-----------------------------------------------------------------------------------|-----------------------------------------------------|
| GET     | [`/api/team_events`](http://localhost:80/nooble/public/api/team_events)           | Récupération de la liste de ressources TeamEvent    |
| GET     | [`/api/team_events/{id}`](http://localhost:80/nooble/public/api/team_events/{id}) | Récupération des données d'une ressource TeamEvent. |
| POST    | [`/api/team_events`](http://localhost:80/nooble/public/api/team_events)           | Création d'une ressource TeamEvent                  |
| PATCH   | [`/api/team_events/{id}`](http://localhost:80/nooble/public/api/team_events/{id}) | Modification d'une ressource TeamEvent              |
| DELETE  | [`/api/team_events/{id}`](http://localhost:80/nooble/public/api/team_events/{id}) | Suppression d'une ressource TeamEvent               |

### TeamSponsor

La classe **TeamSponsor** représente une entité coordinatrice entre une **équipe (Team)** et un **sponsor (Sponsor)**. Cette relation permet de gérer les sponsors associés à des équipes spécifiques.

| Méthode | Route                                                                                               | Description                                                         |
|---------|-----------------------------------------------------------------------------------------------------|---------------------------------------------------------------------|
| GET     | [`/api/team_sponsors/{id}`](http://localhost:80/nooble/public/api/team_sponsors/{id})               | Récupération des données d'une ressource TeamSponsor.               |
| GET     | [`/api/sponsor/{id}/teamSponsors`](http://localhost:80/nooble/public/api/sponsor/{id}/teamSponsors) | Récupération de la liste de ressources TeamSponsor pour un sponsor  |
| GET     | [`/api/teams/{id}/teamSponsors`](http://localhost:80/nooble/public/api/teams/{id}/sponsors)             | Récupération de la liste de ressources TeamSponsor pour une équipes |
| POST    | [`/api/team_sponsors`](http://localhost:80/nooble/public/api/team_sponsors)                         | Création d'une ressource TeamSponsor                                |
| DELETE  | [`/api/team_sponsors/{id}`](http://localhost:80/nooble/public/api/team_sponsors/{id})               | Suppression d'une ressource TeamSponsor                             |


### User

| Méthode | Route                                                                 | Description                               |
|---------|-----------------------------------------------------------------------|-------------------------------------------|
| GET     | [`/api/users`](http://localhost:80/nooble/public/api/users)           | Récupération de la liste des utilisateurs |
| GET     | [`/api/users/{id}`](http://localhost:80/nooble/public/api/users/{id}) | Récupération des données utilisateur      |
| POST    | [`/api/users`](http://localhost:80/nooble/public/api/users)           | Création d'un utilisateur                 |
| PATCH   | [`/api/users/{id}`](http://localhost:80/nooble/public/api/users/{id}) | Modification d'un utilisateur             |
| DELETE  | [`/api/users/{id}`](http://localhost:80/nooble/public/api/users/{id}) | Suppression d'un utilisateur              |
