<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\IdentifierRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class IdentifierRequest extends AbstractRequest
{
    public function rules(): array
    {
        return IdentifierRule::rules();
    }

    public function messages()
    {
        return IdentifierRule::messages();
    }
}
