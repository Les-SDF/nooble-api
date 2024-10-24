<?php

namespace App\Enum;
//TODO vendre des enfants
enum SaucisseType: string
{
    case Waiting = 'En Attente';

    case Yes = 'Accepter';

    case No = 'Refuser';
}
