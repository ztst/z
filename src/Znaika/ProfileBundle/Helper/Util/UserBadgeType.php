<?
    namespace Znaika\ProfileBundle\Helper\Util;

    class UserBadgeType
    {
        const FILLED_OUT_PROFILE    = 1;
        const VIDEO_VIEWER          = 2;
        const LEARNER_BADGE         = 3;
        const COMMENT_WRITER        = 4;
        const REFERRAL_INVITER      = 5;
        const SOCIAL_NETWORK_POSTER = 6;
        const VIDEO_RATER           = 7;

        public static function getAvailableTypes()
        {
            $availableTypes = array(
                self::FILLED_OUT_PROFILE,
                self::VIDEO_VIEWER,
                self::LEARNER_BADGE,
                self::COMMENT_WRITER,
                self::REFERRAL_INVITER,
                self::SOCIAL_NETWORK_POSTER,
                self::VIDEO_RATER,
            );

            return $availableTypes;
        }

        public static function getAvailableTypesTexts()
        {
            $availableTypes = array(
                self::FILLED_OUT_PROFILE    => "Я такой!",
                self::VIDEO_VIEWER          => "Знайка",
                self::LEARNER_BADGE         => "Ученик",
                self::COMMENT_WRITER        => "Писатель",
                self::REFERRAL_INVITER      => "Друг",
                self::SOCIAL_NETWORK_POSTER => "Рассказчик",
                self::VIDEO_RATER           => "Ценитель",
            );

            return $availableTypes;
        }

        public static function getTextByType($type)
        {
            $text  = "";
            $texts = self::getAvailableTypesTexts();
            if (array_key_exists($type, $texts))
            {
                $text = $texts[$type];
            }

            return $text;
        }
    }