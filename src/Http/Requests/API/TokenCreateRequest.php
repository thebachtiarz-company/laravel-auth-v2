<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\TokenCreateRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class TokenCreateRequest extends AbstractRequest
{
    public function rules(): array
    {
        return TokenCreateRule::rules();
    }

    public function messages()
    {
        return TokenCreateRule::messages();
    }
}
