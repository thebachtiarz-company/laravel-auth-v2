<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\UserCreateRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class UserCreateRequest extends AbstractRequest
{
    public function rules(): array
    {
        return UserCreateRule::rules();
    }

    public function messages()
    {
        return UserCreateRule::messages();
    }
}
