<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\ChangeUserEmail;

    class ChangeUserEmailRedisRepository implements IChangeUserEmailRepository
    {
        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function save(ChangeUserEmail $changeUserEmail)
        {
            return true;
        }

        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function delete(ChangeUserEmail $changeUserEmail)
        {
            return true;
        }

        /**
         * @param $key
         *
         * @return ChangeUserEmail
         */
        public function getOneByChangeKey($key)
        {
            return null;
        }
    }