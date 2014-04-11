<?
    namespace Znaika\ProfileBundle\Repository\Ban;

    use Znaika\ProfileBundle\Entity\Ban\Info;

    class InfoRedisRepository implements IInfoRepository
    {
        public function save(Info $info)
        {
            return true;
        }
    }
