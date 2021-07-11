<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderType extends Enum
{
    const DELIVERY = "DELIVERY";
    const TAKEOUT = "TAKEOUT";
    const INDOOR = "INDOOR";
}
