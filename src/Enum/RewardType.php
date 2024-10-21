<?php

namespace App\Enum;

enum RewardType: string
{
    case Cashprize = 'cashprize';

    case Trophy = 'trophy';

    case Medal = 'medal';

    case Gift = 'gift';

    case Other = 'other';
}