<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Base\App\Http\Requests\Rule\AbstractRule;

use function array_merge;
use function sprintf;

class PasswordUpdateRule extends AbstractRule
{
    public const INPUT_CURRENTPASSWORD = 'currentPassword';
    public const INPUT_NEWPASSWORD     = 'newPassword';

    public static function rules(): array
    {
        return array_merge(
            IdentifierRule::rules(),
            [
                self::INPUT_CURRENTPASSWORD => [
                    'required',
                    'regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=.*[^0-9a-zA-Z]).{7,})\S$/',
                ],
                self::INPUT_NEWPASSWORD => [
                    'required',
                    'regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=.*[^0-9a-zA-Z]).{7,})\S$/',
                ],
            ],
        );
    }

    public static function messages(): array
    {
        return array_merge(
            IdentifierRule::messages(),
            [
                sprintf('%s.required', self::INPUT_CURRENTPASSWORD) => 'Current password is required',
                sprintf('%s.regex', self::INPUT_CURRENTPASSWORD) => 'Current password format invalid',

                sprintf('%s.required', self::INPUT_NEWPASSWORD) => 'New Password is required',
                sprintf('%s.regex', self::INPUT_NEWPASSWORD) => 'New Password format invalid',
            ],
        );
    }
}
