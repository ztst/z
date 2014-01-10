<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;

    interface IUserQuestionAnswerRepository
    {
        /**
         * @param UserQuestionAnswer $userQuestionAnswer
         *
         * @return mixed
         */
        public function save(UserQuestionAnswer $userQuestionAnswer);

    }