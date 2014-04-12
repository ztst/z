<?

    namespace Znaika\LikesBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\LikesBundle\Entity\VideoCommentLike;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\LikesBundle\Repository\VideoCommentLikeRepository;
    use Znaika\LikesBundle\Repository\VideoLikeRepository;

    class DefaultController extends Controller
    {
        public function addVideoLikeAction(Request $request)
        {
            $user = $this->getUser();

            $videoRepository = $this->getVideoRepository();
            $videoId = $request->get("videoId");
            $video = $videoRepository->getOneByVideoId($videoId);
            if (!$video)
            {
                return $this->createNotFoundException("video({$videoId}) for like not found");
            }

            $videoLikeRepository = $this->getVideoLikeRepository();
            $videoLike = $videoLikeRepository->getUserVideoLike($user, $video);
            $hasLike = !is_null($videoLike);
            if (!$hasLike)
            {
                $videoLike = new VideoLike();
                $videoLike->setUser($user);
                $videoLike->setVideo($video);
                $videoLikeRepository->save($videoLike);
                $video->setLikesCount($video->getLikesCount() + 1);
            }
            else
            {
                $videoLikeRepository->delete($videoLike);
                $video->setLikesCount($video->getLikesCount() - 1);
            }
            $videoRepository->save($video);

            $data = array(
                "success" => true,
                "liked"   => !$hasLike
            );

            return new JsonResponse($data);
        }

        public function addVideoCommentLikeAction(Request $request)
        {
            $user = $this->getUser();

            $videoCommentRepository = $this->getVideoCommentRepository();
            $videoCommentId = $request->get("videoCommentId");
            $videoComment = $videoCommentRepository->getOneByVideoCommentId($videoCommentId);
            if (!$videoComment)
            {
                return $this->createNotFoundException("video comment({$videoCommentId}) for like not found");
            }

            $videoCommentLikeRepository = $this->getVideoCommentLikeRepository();
            $like = $videoCommentLikeRepository->getUserCommentLike($user, $videoComment);
            $hasLike = !is_null($like);
            if (!$hasLike)
            {
                $like = new VideoCommentLike();
                $like->setUser($user);
                $like->setVideoComment($videoComment);
                $videoCommentLikeRepository->save($like);
                $videoComment->setLikesCount($videoComment->getLikesCount() + 1);
            }
            else
            {
                $videoCommentLikeRepository->delete($like);
                $videoComment->setLikesCount($videoComment->getLikesCount() - 1);
            }
            $videoCommentRepository->save($videoComment);

            $data = array(
                "success" => true,
                "liked"   => !$hasLike
            );

            return new JsonResponse($data);
        }

        /**
         * @return VideoLikeRepository
         */
        private function  getVideoLikeRepository()
        {
            return $this->get("znaika.video_like_repository");
        }


        /**
         * @return VideoCommentLikeRepository
         */
        private function  getVideoCommentLikeRepository()
        {
            return $this->get("znaika.video_comment_like_repository");
        }

        /**
         * @return VideoCommentRepository
         */
        private function  getVideoCommentRepository()
        {
            return $this->get("znaika.video_comment_repository");
        }

        /**
         * @return VideoRepository
         */
        private function  getVideoRepository()
        {
            return $this->get("znaika.video_repository");
        }
    }
