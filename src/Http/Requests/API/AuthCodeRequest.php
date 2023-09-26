<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\AuthCodeRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class AuthCodeRequest extends AbstractRequest
{
    public function rules(): array
    {
        return AuthCodeRule::rules();
    }

    public function messages()
    {
        return AuthCodeRule::messages();
    }
}
