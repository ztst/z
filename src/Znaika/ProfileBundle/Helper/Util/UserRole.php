<?
    namespace Znaika\ProfileBundle\Helper\Util;

    class UserRole
    {
        const ROLE_USER      = 0;
        const ROLE_MODERATOR = 1;
        const ROLE_ADMIN     = 2;
        const ROLE_TEACHER   = 3;
        const ROLE_PARENT    = 4;

        public static function getAvailableRoles()
        {
            $availableRoles = array(
                self::ROLE_USER,
                self::ROLE_MODERATOR,
                self::ROLE_ADMIN,
                self::ROLE_TEACHER,
                self::ROLE_PARENT,
            );

            return $availableRoles;
        }

        public static function getAvailableRolesSecurityTexts()
        {
            $availableRoles = array(
                self::ROLE_USER      => "ROLE_USER",
                self::ROLE_MODERATOR => "ROLE_MODERATOR",
                self::ROLE_ADMIN     => "ROLE_ADMIN",
                self::ROLE_TEACHER   => "ROLE_TEACHER",
                self::ROLE_PARENT    => "ROLE_PARENT",
            );

            return $availableRoles;
        }

        public static function getSecurityTextByRole($role)
        {
            $result = "";
            $texts  = self::getAvailableRolesSecurityTexts();
            if (array_key_exists($role, $texts))
            {
                $result = $texts[$role];
            }

            return $result;
        }

        public static function getRolesForRegisterFormSelect()
        {
            $availableRoles = array(
                self::ROLE_USER      => "Учеником",
                self::ROLE_PARENT    => "Родителем",
                self::ROLE_TEACHER   => "Учителем",
            );

            return $availableRoles;
        }
    }