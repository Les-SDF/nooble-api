<?php

namespace App\Enum;

enum SaucisseType: string
{
    case Waiting = 'En Attente';

    case Yes = 'Accepter';

    case No = 'Refuser';
}
