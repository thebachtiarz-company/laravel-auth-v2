<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Repositories;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Auth\Traits\Attributes\UserModelAttributeTrait;
use TheBachtiarz\Base\App\Repositories\AbstractRepository;

use function app;
use function assert;
use function sprintf;

class AuthUserRepository extends AbstractRepository
{
    use UserModelAttributeTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (! $this->getUserModel()) {
            $this->setUserModel(app(AuthUser::class));
        }

        $this->modelEntity = $this->getUserModel();

        parent::__construct();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->setUserModel(null);
    }

    // ? Public Methods

    /**
     * Get by identifier
     */
    public function getByIdentifier(string $identifier): AbstractAuthUser
    {
        $authUser = $this->getUserModel()::getByIdentifier($identifier)->first();
        assert($authUser instanceof AbstractAuthUser || $authUser === null);

        if (! $authUser) {
            throw new ModelNotFoundException(sprintf("User with identifier '%s' not found", $identifier));
        }

        return $authUser;
    }

    /**
     * Create newuser
     */
    public function create(AbstractAuthUser $abstractAuthUser): AbstractAuthUser
    {
        $create = $this->createFromModel($abstractAuthUser);

        if (! $create) {
            throw new Exception('Failed to create new user');
        }

        return $create;
    }

    /**
     * Save current user
     */
    public function save(AbstractAuthUser $abstractAuthUser): AbstractAuthUser
    {
        $save = $abstractAuthUser->save();

        if (! $save) {
            throw new Exception('Failed to save current user');
        }

        return $abstractAuthUser->fresh();
    }

    /**
     * Delete by identifier
     */
    public function deleteByIdentifier(string $identifier): bool
    {
        $authUser = $this->getByIdentifier($identifier);

        return $this->deleteById($authUser->getId());
    }

    // ? Protected Methods

    protected function getByIdErrorMessage(): string|null
    {
        return "User with id '%s' not found";
    }

    protected function createOrUpdateErrorMessage(): string|null
    {
        return 'Failed to %s user';
    }

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
