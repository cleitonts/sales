<?php

namespace Panthir\Domain\User\ValueObject;

class UserRoles
{
    /**
     * - Clientes/accounts
     *      - projetos
     *          -documentos.
     */
    public const ROLE_DOCUMENTS_VIEW = 'ROLE_DOCUMENTS_VIEW';
    public const ROLE_DOCUMENTS_CREATE = 'ROLE_DOCUMENTS_CREATE';
    public const ROLE_DOCUMENTS_DELETE = 'ROLE_DOCUMENTS_DELETE';
    public const ROLE_DOCUMENTS_EDIT = 'ROLE_DOCUMENTS_EDIT';

    public const ROLE_PROJECT_VIEW = 'ROLE_PROJECT_VIEW';
    public const ROLE_PROJECT_CREATE = 'ROLE_PROJECT_CREATE';
    public const ROLE_PROJECT_DELETE = 'ROLE_PROJECT_DELETE';
    public const ROLE_PROJECT_EDIT = 'ROLE_PROJECT_EDIT';

    public const ROLE_ACCOUNT_VIEW = 'ROLE_ACCOUNT_VIEW';
    public const ROLE_ACCOUNT_CREATE = 'ROLE_ACCOUNT_CREATE';
    public const ROLE_ACCOUNT_DELETE = 'ROLE_ACCOUNT_DELETE';
    public const ROLE_ACCOUNT_EDIT = 'ROLE_ACCOUNT_EDIT';

    public const PROFILE_VIEWER = [
        self::ROLE_DOCUMENTS_VIEW,
    ];

    public const PROFILE_WRITER = [
        self::ROLE_DOCUMENTS_CREATE,
        self::ROLE_DOCUMENTS_DELETE,
        self::ROLE_DOCUMENTS_EDIT,
        self::ROLE_DOCUMENTS_VIEW,
    ];

    public const PROFILE_ACCOUNT_MANAGER = [
        self::ROLE_DOCUMENTS_CREATE,
        self::ROLE_DOCUMENTS_DELETE,
        self::ROLE_DOCUMENTS_EDIT,
        self::ROLE_DOCUMENTS_VIEW,

        self::ROLE_PROJECT_CREATE,
        self::ROLE_PROJECT_DELETE,
        self::ROLE_PROJECT_EDIT,
        self::ROLE_PROJECT_VIEW,
    ];

    public const PROFILE_ADMIN = [
        self::ROLE_DOCUMENTS_CREATE,
        self::ROLE_DOCUMENTS_DELETE,
        self::ROLE_DOCUMENTS_EDIT,
        self::ROLE_DOCUMENTS_VIEW,

        self::ROLE_PROJECT_CREATE,
        self::ROLE_PROJECT_DELETE,
        self::ROLE_PROJECT_EDIT,
        self::ROLE_PROJECT_VIEW,

        self::ROLE_ACCOUNT_CREATE,
        self::ROLE_ACCOUNT_DELETE,
        self::ROLE_ACCOUNT_EDIT,
        self::ROLE_ACCOUNT_VIEW,
    ];

    public const LIST_PROFILES = [
        'PROFILE_VIEWER',
        'PROFILE_WRITER',
        'PROFILE_ACCOUNT_MANAGER',
        'PROFILE_ADMIN',
    ];

    public static function canSetProfile(array $currentRoles, array $concededRoles): bool
    {
        $currentProfile = self::getProfileByRoles($currentRoles);
        $targetProfile = self::getProfileByRoles($concededRoles);
        if (array_search($currentProfile, self::LIST_PROFILES) > array_search($targetProfile, self::LIST_PROFILES)) {
            return true;
        }

        return false;
    }

    public static function getProfileByRoles(array $roles): string
    {
        if (self::PROFILE_VIEWER == $roles) {
            return 'PROFILE_VIEWER';
        }
        if (self::PROFILE_WRITER == $roles) {
            return 'PROFILE_WRITER';
        }
        if (self::PROFILE_ACCOUNT_MANAGER == $roles) {
            return 'PROFILE_ACCOUNT_MANAGER';
        }
        if (self::PROFILE_ADMIN == $roles) {
            return 'PROFILE_ADMIN';
        }

        return 'PROFILE_VIEWER';
    }
}
