<?
    namespace Znaika\FrontendBundle\Repository\Profile\Ban;

    use Znaika\FrontendBundle\Entity\Profile\Ban\Info;

    class InfoRedisRepository implements IInfoRepository
    {
        public function save(Info $info)
        {
            return true;
        }
    }
