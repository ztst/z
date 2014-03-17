<?
    namespace Znaika\FrontendBundle\Helper\Util\Lesson;

    class VideoUtil
    {
        const VIMEO_COM = "http//vimeo.com/";

        public static function getVimeoIdByUrl($url)
        {
            $pattern = '/([^\d]*)(\d+)/i';
            $replacement = '${2}';
            return preg_replace($pattern, $replacement, $url);
        }

        public static function getUrlByVimeoId($id)
        {
            return self::VIMEO_COM . $id;
        }
    }