<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BrokerType extends Enum
{
    const Ifood =   1;
    const Rappi =   2;
    const Neemo =   3;
}
