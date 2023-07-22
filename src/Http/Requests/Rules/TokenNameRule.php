<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Base\App\Http\Requests\Rules\AbstractRule;

use function sprintf;

class TokenNameRule extends AbstractRule
{
    public const INPUT_NAME = 'name';

    public static function rules(): array
    {
        return [
            self::INPUT_NAME => [
                'required',
                'alpha_dash:ascii',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_NAME) => 'Token name is required',
            sprintf('%s.*', self::INPUT_NAME) => 'Token name format invalid',
        ];
    }
}
