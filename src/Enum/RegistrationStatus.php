<?php

namespace App\Enum;

enum RegistrationStatus: string
{
    case Waiting = 'waiting';

    case Accepted = 'accepted';

    case Regused = 'refused';
}