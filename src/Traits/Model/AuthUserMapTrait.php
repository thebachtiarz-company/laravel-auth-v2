<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Model;

use TheBachtiarz\Auth\Interfaces\Model\AuthUserInterface;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;

/**
 * Auth User Map Trait
 */
trait AuthUserMapTrait
{
    /**
     * Auth user simple list map
     *
     * @return array
     */
    public function simpleListMap(): array
    {
        /** @var AuthUser $this */

        $defaultAttributes = [
            authidentifiermethod(),
            'created',
        ];

        $this->setData(
            attribute: 'created',
            value: CarbonHelper::anyConvDateToTimestamp(datetime: $this->getCreatedAt()),
        );

        $this->makeHidden([
            AuthUserInterface::ATTRIBUTE_ID,
            AuthUserInterface::ATTRIBUTE_PASSWORD,
        ]);

        return $this->only(attributes: $defaultAttributes);
    }
}
