<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;

    class UserQuestionAnswerRedisRepository implements IUserQuestionAnswerRepository
    {
        /**
         * @param UserQuestionAnswer $userQuestionAnswer
         *
         * @return mixed
         */
        public function save(UserQuestionAnswer $userQuestionAnswer)
        {
            return true;
        }
    }
