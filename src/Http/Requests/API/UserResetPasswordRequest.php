<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\UserResetPasswordRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class UserResetPasswordRequest extends AbstractRequest
{
    public function rules(): array
    {
        return UserResetPasswordRule::rules();
    }

    public function messages()
    {
        return UserResetPasswordRule::messages();
    }
}
