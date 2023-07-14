<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Requests\API;

use TheBachtiarz\Auth\Http\Requests\Rules\EmailVerificationRule;
use TheBachtiarz\Base\App\Http\Requests\AbstractRequest;

class EmailVerificationRequest extends AbstractRequest
{
    public function rules(): array
    {
        return EmailVerificationRule::rules();
    }

    public function messages()
    {
        return EmailVerificationRule::messages();
    }
}
