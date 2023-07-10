<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Services;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\PersonalAccessToken;
use TheBachtiarz\Auth\Policies\AuthPolicy;
use TheBachtiarz\Auth\Repositories\PersonalAccessTokenRepository;
use TheBachtiarz\Base\App\Helpers\ResponseHelper;
use TheBachtiarz\Base\App\Libraries\Paginator\Params\PaginatorParam;
use TheBachtiarz\Base\App\Services\AbstractService;
use Throwable;

use function array_map;
use function assert;

class PersonalAccessTokenService extends AbstractService
{
    public function __construct(
        protected PersonalAccessTokenRepository $personalAccessTokenRepository,
        protected AuthPolicy $authPolicy,
    ) {
    }

    // ? Public Methods

    /**
     * Create auth user session
     *
     * @return array
     */
    public function createSession(string $identifier, string $password): array
    {
        try {
            $createSession = $this->authPolicy
                ->setIdentifier($identifier)
                ->setPassword($password)
                ->createSession();
            assert($createSession instanceof AbstractAuthUser);

            $result = $createSession->simpleListMap();

            $this->setResponseData(message: 'Successfully create new session', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully create new session', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Create auth user token
     *
     * @return array
     */
    public function createToken(string $identifier, string $password, array|null $abilities = null): array
    {
        try {
            $createToken = $this->authPolicy
                ->setIdentifier($identifier)
                ->setPassword($password)
                ->setTokenAbilities($abilities)
                ->createToken();
            assert($createToken instanceof NewAccessToken);

            $result = $this->authPolicy->tokenToArray($createToken);

            $this->setResponseData(message: 'Successfully create new token', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully create new token', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Get current user token list
     *
     * @return array
     */
    public function getTokenList(): array
    {
        try {
            /** @var Collection<PersonalAccessTokenInterface> $tokenList */
            $tokenList = $this->personalAccessTokenRepository->getByUser();

            if (! PaginatorParam::isAsPaginate()) {
                ResponseHelper::asPaginate(
                    perPage: PaginatorParam::setPerPage(10)->getPerPage(),
                    currentPage: PaginatorParam::setCurrentPage(1)->getCurrentPage(),
                    sortOptions: PaginatorParam::addResultSortOption(attributeName: 'created')->getResultSortOptions(asMultiple: true),
                );
            }

            $result = [
                ...array_map(
                    callback: static fn (PersonalAccessToken $personalAccessToken): array => $personalAccessToken->simpleListMap(),
                    array: $tokenList->all(),
                ),
            ];

            $this->setResponseData(message: 'Current user token list', data: $result, httpCode: 200);

            return $this->serviceResult(status: true, message: 'Current user token list', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Delete user session
     *
     * @return array
     */
    public function deleteSession(): array
    {
        try {
            $deleteSession = $this->authPolicy->deleteSession();

            $result = [];

            $this->setResponseData(message: 'Successfully delete user session', data: $result, httpCode: 201);

            return $this->serviceResult(status: $deleteSession, message: 'Successfully delete user session', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Delete user token
     *
     * @return array
     */
    public function deleteToken(string $tokenName): array
    {
        try {
            $deleteToken = $this->authPolicy->deleteTokenByName($tokenName);

            $result = [];

            $this->setResponseData(message: 'Successfully delete user token', data: $result, httpCode: 201);

            return $this->serviceResult(status: $deleteToken, message: 'Successfully delete user token', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Delete all user token
     *
     * @return array
     */
    public function revokeTokens(): array
    {
        try {
            $revokeTokens = $this->personalAccessTokenRepository->deleteByUser();

            $result = [];

            $this->setResponseData(message: 'Successfully revoke all user token', data: $result, httpCode: 201);

            return $this->serviceResult(status: $revokeTokens, message: 'Successfully revoke all user token', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
