<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AclEnum extends Enum
{
    const Read = 'Read';
    const Create = 'Create';
    const Update = 'Update';
    const Delete = 'Delete';
    const Preview = 'Preview';
    const Download = 'Download';
}
