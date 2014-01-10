<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt;

    interface IUserAttemptRepository
    {
        /**
         * @param UserAttempt $userAttempt
         *
         * @return mixed
         */
        public function save(UserAttempt $userAttempt);

    }