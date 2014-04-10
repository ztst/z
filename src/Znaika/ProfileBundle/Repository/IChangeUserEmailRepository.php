<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\ChangeUserEmail;

    interface IChangeUserEmailRepository
    {
        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function save(ChangeUserEmail $changeUserEmail);

        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function delete(ChangeUserEmail $changeUserEmail);

        /**
         * @param $key
         *
         * @return ChangeUserEmail
         */
        public function getOneByChangeKey($key);
    }