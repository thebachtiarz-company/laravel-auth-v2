<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\TokenNameRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class TokenNameRequest extends AbstractRequest
{
    public function rules(): array
    {
        return TokenNameRule::rules();
    }

    public function messages()
    {
        return TokenNameRule::messages();
    }
}
