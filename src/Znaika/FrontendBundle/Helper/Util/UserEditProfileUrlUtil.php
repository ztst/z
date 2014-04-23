<?
    namespace Znaika\FrontendBundle\Helper\Util;

    use Znaika\ProfileBundle\Helper\Util\UserRole;

    class UserEditProfileUrlUtil
    {
        const EDIT_USER_PROFILE = "edit_user_profile";
        const EDIT_PARENT_PROFILE = "edit_parent_profile";
        const EDIT_TEACHER_PROFILE = "edit_teacher_profile";

        public static function prepareUrlPatterByRole($role)
        {
            switch ($role)
            {
                case UserRole::ROLE_USER:
                    $urlPattern = self::EDIT_USER_PROFILE;
                    break;
                case UserRole::ROLE_PARENT:
                    $urlPattern = self::EDIT_PARENT_PROFILE;
                    break;
                case UserRole::ROLE_TEACHER:
                    $urlPattern = self::EDIT_TEACHER_PROFILE;
                    break;
                default:
                    $urlPattern = self::EDIT_PARENT_PROFILE;
            }

            return $urlPattern;
        }
    }