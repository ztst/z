<?
    namespace Znaika\ProfileBundle\Repository\Ban;

    use Znaika\ProfileBundle\Entity\Ban\Info;

    interface IInfoRepository
    {
        /**
         * @param Info $info
         *
         * @return bool
         */
        public function save(Info $info);
    }
