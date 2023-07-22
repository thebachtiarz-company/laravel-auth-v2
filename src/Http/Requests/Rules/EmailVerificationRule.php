<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Base\App\Http\Requests\Rules\AbstractRule;

use function sprintf;

class EmailVerificationRule extends AbstractRule
{
    public const INPUT_EMAIL = 'email';

    public static function rules(): array
    {
        return [
            self::INPUT_EMAIL => [
                'required',
                'email',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_EMAIL) => 'Email is required',
            sprintf('%s.email', self::INPUT_EMAIL) => 'Email must be a valid email',
        ];
    }
}
