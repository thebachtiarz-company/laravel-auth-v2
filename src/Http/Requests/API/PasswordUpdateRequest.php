<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\PasswordUpdateRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class PasswordUpdateRequest extends AbstractRequest
{
    public function rules(): array
    {
        return PasswordUpdateRule::rules();
    }

    public function messages()
    {
        return PasswordUpdateRule::messages();
    }
}
