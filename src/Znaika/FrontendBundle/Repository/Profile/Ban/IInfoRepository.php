<?
    namespace Znaika\FrontendBundle\Repository\Profile\Ban;

    use Znaika\FrontendBundle\Entity\Profile\Ban\Info;

    interface IInfoRepository
    {
        /**
         * @param Info $info
         *
         * @return bool
         */
        public function save(Info $info);
    }
