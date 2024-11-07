<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

readonly class AuthenticationSuccessListener
{
    public function __construct(
        //Service permettant de décoder un JWT (entre autres)
        private JWTTokenManagerInterface $jwtManager
    )
    {
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        //Insertion de données de l'utilisateur ici - À compléter
        $data['id'] = $user->getId();

        //Récupération des données contenues de le JWT - À compléter
        //On décode le jwt qui est déjà encodé, à ce stade, afin de récupérer les informations qui nous intéressent.
        $jwt = $this->jwtManager->parse($data['token']);
        $data['token_exp'] = $jwt['exp'];

        $event->setData($data);
    }

}