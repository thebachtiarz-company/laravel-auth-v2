<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Auth\Interfaces\Model\AuthUserInterface;
use TheBachtiarz\Base\App\Http\Requests\Rule\AbstractRule;

use function array_merge;
use function sprintf;

class TokenCreateRule extends AbstractRule
{
    public const INPUT_IDENTIFIER = 'identifier';
    public const INPUT_PASSWORD   = 'password';

    public static function rules(): array
    {
        $identifierRule = match (authidentifiermethod()) {
            AuthUserInterface::ATTRIBUTE_USERNAME => ['alpha_dash:ascii', 'min:8'],
            AuthUserInterface::ATTRIBUTE_EMAIL => ['email'],
        };

        return [
            self::INPUT_IDENTIFIER => array_merge(
                ['required'],
                $identifierRule,
            ),
            self::INPUT_PASSWORD => [
                'required',
                'regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=.*[^0-9a-zA-Z]).{7,})\S$/',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_IDENTIFIER) => 'Identifier is required',
            sprintf('%s.email', self::INPUT_IDENTIFIER) => 'Identifier must be a valid email',
            sprintf('%s.min', self::INPUT_IDENTIFIER) => 'Identifier must be more than :min characters',
            sprintf('%s.*', self::INPUT_IDENTIFIER) => 'Identifier format invalid',

            sprintf('%s.required', self::INPUT_PASSWORD) => 'Password is required',
            sprintf('%s.*', self::INPUT_PASSWORD) => 'Password format invalid',
        ];
    }
}
