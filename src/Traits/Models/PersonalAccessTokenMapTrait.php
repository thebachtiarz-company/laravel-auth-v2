<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Models;

use TheBachtiarz\Auth\Interfaces\Models\PersonalAccessTokenInterface;
use TheBachtiarz\Auth\Models\PersonalAccessToken;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;

use function array_merge;
use function array_unique;

/**
 * Personal Access Token Map Trait
 */
trait PersonalAccessTokenMapTrait
{
    /**
     * User token simple list map
     *
     * @return array
     */
    public function simpleListMap(array $attributes = []): array
    {
        /** @var PersonalAccessToken $this */

        $defaultAttributes = [
            PersonalAccessTokenInterface::ATTRIBUTE_NAME,
            'last_used',
            'expired',
            'created',
        ];

        $this->setData(
            attribute: 'last_used',
            value: CarbonHelper::anyConvDateToTimestamp(datetime: $this->getLastUsedAt()),
        )->setData(
            attribute: 'expired',
            value: @$this->getExpiresAt() ? CarbonHelper::anyConvDateToTimestamp(datetime: $this->getExpiresAt()) : 'Never',
        )->setData(
            attribute: 'created',
            value: CarbonHelper::anyConvDateToTimestamp(datetime: $this->getCreatedAt()),
        );

        $this->makeHidden([
            PersonalAccessTokenInterface::ATTRIBUTE_ID,
            PersonalAccessTokenInterface::ATTRIBUTE_TOKENABLETYPE,
            PersonalAccessTokenInterface::ATTRIBUTE_TOKENABLEID,
            PersonalAccessTokenInterface::ATTRIBUTE_LASTUSEDAT,
            PersonalAccessTokenInterface::ATTRIBUTE_EXPIRESAT,
            PersonalAccessTokenInterface::ATTRIBUTE_CREATEDAT,
            PersonalAccessTokenInterface::ATTRIBUTE_UPDATEDAT,
        ]);

        return $this->only(attributes: array_unique(array_merge($defaultAttributes, $attributes)));
    }
}
