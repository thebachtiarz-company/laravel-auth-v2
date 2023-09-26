<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\Rules;

use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Base\App\Http\Requests\Rules\AbstractRule;

use function sprintf;

class AuthCodeRule extends AbstractRule
{
    public const INPUT_CODE = 'code';

    /**
     * {@inheritDoc}
     */
    public static function rules(): array
    {
        return [
            self::INPUT_CODE => [
                'required',
                'string',
                'alpha_dash:ascii',
                'starts_with:' . AuthUserInterface::USER_CODE_PREFIX,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::INPUT_CODE) => 'Auth code required!',
            sprintf('%s.*', self::INPUT_CODE) => 'Auth code invalid!',
        ];
    }
}
