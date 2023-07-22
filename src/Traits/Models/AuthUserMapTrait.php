<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Models;

use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;

use function array_merge;
use function array_unique;
use function authidentifiermethod;

/**
 * Auth User Map Trait
 */
trait AuthUserMapTrait
{
    /**
     * Auth user simple list map
     *
     * @param array $attributes
     *
     * @return array
     */
    public function simpleListMap(array $attributes = []): array
    {
        /** @var AuthUser $this */

        $defaultAttributes = [
            authidentifiermethod(),
            'created',
            'updated',
        ];

        $this->setData(
            attribute: 'created',
            value: CarbonHelper::anyConvDateToTimestamp(datetime: $this->getCreatedAt()),
        )->setData(
            attribute: 'updated',
            value: CarbonHelper::anyConvDateToTimestamp(datetime: $this->getUpdatedAt()),
        );

        $this->makeHidden([
            AuthUserInterface::ATTRIBUTE_ID,
            AuthUserInterface::ATTRIBUTE_PASSWORD,
        ]);

        return $this->only(attributes: array_unique(array_merge($defaultAttributes, $attributes)));
    }
}
