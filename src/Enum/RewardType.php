<?php

namespace App\Enum;

enum RewardType: string
{
    case CashPrize = 'cash_prize';

    case Trophy = 'trophy';

    case Medal = 'medal';

    case Gift = 'gift';

    case Other = 'other';
}