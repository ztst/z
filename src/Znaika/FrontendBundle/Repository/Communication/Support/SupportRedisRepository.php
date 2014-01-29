<?
    namespace Znaika\FrontendBundle\Repository\Communication\Support;

    use Znaika\FrontendBundle\Entity\Communication\Support;

    class SupportRedisRepository implements ISupportRepository
    {
        /**
         * @param Support $support
         *
         * @return bool
         */
        public function save(Support $support)
        {
            return true;
        }
    }