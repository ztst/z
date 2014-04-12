<?
    namespace Znaika\LikesBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;

    class VideoCommentLike
    {
        /**
         * @var integer
         */
        private $videoCommentLikeId;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var VideoComment
         */
        private $videoComment;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $user;

        /**
         * @param \DateTime $createdTime
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;
        }

        /**
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }

        /**
         * @param \Znaika\ProfileBundle\Entity\User $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         */
        public function setVideoComment($videoComment)
        {
            $this->videoComment = $videoComment;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment
         */
        public function getVideoComment()
        {
            return $this->videoComment;
        }

        /**
         * @param int $videoCommentLikeId
         */
        public function setVideoCommentLikeId($videoCommentLikeId)
        {
            $this->videoCommentLikeId = $videoCommentLikeId;
        }

        /**
         * @return int
         */
        public function getVideoCommentLikeId()
        {
            return $this->videoCommentLikeId;
        }
    }