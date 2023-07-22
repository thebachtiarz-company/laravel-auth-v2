<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Base\App\Http\Requests\Rules\AbstractRule;

use function array_merge;
use function authidentifiermethod;
use function sprintf;

class IdentifierRule extends AbstractRule
{
    public const INPUT_IDENTIFIER = 'identifier';

    public static function rules(): array
    {
        return [
            self::INPUT_IDENTIFIER => array_merge(
                ['required'],
                match (authidentifiermethod()) {
                    AuthUserInterface::ATTRIBUTE_USERNAME => ['alpha_dash:ascii', 'min:8'],
                    AuthUserInterface::ATTRIBUTE_EMAIL => ['email'],
                    default => ['email'],
                },
            ),
        ];
    }

    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_IDENTIFIER) => 'Identifier is required',
            sprintf('%s.email', self::INPUT_IDENTIFIER) => 'Identifier must be a valid email',
            sprintf('%s.min', self::INPUT_IDENTIFIER) => 'Identifier must be more than :min characters',
            sprintf('%s.*', self::INPUT_IDENTIFIER) => 'Identifier format invalid',
        ];
    }
}
