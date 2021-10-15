<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class IfoodApi extends Enum
{
    const MerchantProducts = '/catalog/v1.0/merchants/%s/products?limit=%s&page=%s';
    const MerchantCatalogs = '/catalog/v1.0/merchants/%s/catalogs';
    const MerchantCategories = '/catalog/v1.0/merchants/%s/catalogs/%s/categories';
}
