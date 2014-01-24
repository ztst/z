<?
    namespace Znaika\FrontendBundle\Helper\Util\Profile;

    class UserRole
    {
        const ROLE_USER      = 0;
        const ROLE_MODERATOR = 1;
        const ROLE_ADMIN     = 2;

        public static function getAvailableRoles()
        {
            $availableRoles = array(
                self::ROLE_USER,
                self::ROLE_MODERATOR,
                self::ROLE_ADMIN
            );

            return $availableRoles;
        }

        public static function getAvailableRolesSecurityTexts()
        {
            $availableRoles = array(
                self::ROLE_USER      => "ROLE_USER",
                self::ROLE_MODERATOR => "ROLE_MODERATOR",
                self::ROLE_ADMIN     => "ROLE_ADMIN",
            );

            return $availableRoles;
        }

        public static function getSecurityTextByRole($role)
        {
            $result = "";
            $texts = self::getAvailableRolesSecurityTexts();
            if (array_key_exists($role, $texts))
            {
                $result = $texts[$role];
            }
            return $result;
        }
    }