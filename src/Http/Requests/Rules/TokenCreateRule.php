<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Base\App\Http\Requests\Rule\AbstractRule;

use function array_merge;

class TokenCreateRule extends AbstractRule
{
    public static function rules(): array
    {
        return array_merge(
            IdentifierRule::rules(),
            PasswordRule::rules(),
        );
    }

    public static function messages(): array
    {
        return array_merge(
            IdentifierRule::messages(),
            PasswordRule::messages(),
        );
    }
}
