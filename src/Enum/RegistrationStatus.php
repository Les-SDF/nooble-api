<?php

namespace App\Enum;

enum RegistrationStatus: string
{
    case Pending = 'pending';

    case Accepted = 'accepted';

    case Refused = 'refused';
}