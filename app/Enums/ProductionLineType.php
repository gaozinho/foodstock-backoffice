<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductionLineType extends Enum
{
    const Cozinha =   1;
    const Montagem =   2;
    const Selagem =   3;
    const Expedicao =   4;
    const RoleProductionLine = "production-line";
}
