<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Helpers;

use Illuminate\Database\Eloquent\Builder;
use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Auth\Traits\Attributes\UserModelAttributeTrait;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;
use TheBachtiarz\Base\App\Helpers\StringHelper;

use function assert;
use function sprintf;

class AuthUserHelper
{
    use UserModelAttributeTrait;

    // ? Public Methods

    /**
     * Generate user code
     */
    public function generateNewCode(int $suffixLength = 12, string|null $result = null): string
    {
        do {
            $result = sprintf(
                '%s-%s-%s',
                AuthUserInterface::USER_CODE_PREFIX,
                CarbonHelper::anyConvDateToTimestamp(withMilli: true),
                StringHelper::shuffleBoth($suffixLength),
            );
        } while ($this->isUserWithCodeExist($result));

        return $result;
    }

    // ? Protected Methods

    /**
     * Check user with proposed code
     */
    protected function isUserWithCodeExist(string $code): bool
    {
        $builder = $this->getUserModel()::getByCode($code);
        assert($builder instanceof Builder);

        return ! ! $builder->first();
    }

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
