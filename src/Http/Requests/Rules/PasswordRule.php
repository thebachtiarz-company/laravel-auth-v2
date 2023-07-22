<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Base\App\Http\Requests\Rules\AbstractRule;

use function sprintf;

class PasswordRule extends AbstractRule
{
    public const INPUT_PASSWORD = 'password';

    public static function rules(): array
    {
        return [
            self::INPUT_PASSWORD => [
                'required',
                'regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=.*[^0-9a-zA-Z]).{7,})\S$/',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_PASSWORD) => 'Password is required',
            sprintf('%s.regex', self::INPUT_PASSWORD) => 'Password format invalid',
        ];
    }
}
