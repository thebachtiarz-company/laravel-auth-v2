<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Base\App\Http\Requests\Rules\AbstractRule;

use function sprintf;

class UserResetPasswordRule extends AbstractRule
{
    public const INPUT_TOKEN       = 'token';
    public const INPUT_NEWPASSWORD = 'newPassword';

    public static function rules(): array
    {
        return [
            self::INPUT_TOKEN => [
                'required',
                'alpha_dash:ascii',
            ],
            self::INPUT_NEWPASSWORD => [
                'required',
                'regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=.*[^0-9a-zA-Z]).{7,})\S$/',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_TOKEN) => 'Token reset is required',
            sprintf('%s.*', self::INPUT_TOKEN) => 'Token reset format invalid',

            sprintf('%s.required', self::INPUT_NEWPASSWORD) => 'New Password is required',
            sprintf('%s.regex', self::INPUT_NEWPASSWORD) => 'New Password format invalid',
        ];
    }
}
