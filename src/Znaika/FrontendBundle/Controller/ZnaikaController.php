<?php
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Znaika\ProfileBundle\Helper\Util\UserBan;
    use Znaika\ProfileBundle\Helper\Util\UserStatus;
    use Znaika\ProfileBundle\Entity\User;

    /**
     * @author Matt Drollette <matt@drollette.com>
     */
    class ZnaikaController extends Controller
    {
        /**
         * @var User
         */
        private $user;

        /**
         * @var ContainerInterface
         */
        private $context;

        public function initialize(Request $request, ContainerInterface $context)
        {
            $this->context = $context;

            /** @var SecurityContextInterface $securityContext */
            $securityContext = $context->get("security.context");
            $this->user = $securityContext->getToken()->getUser();
            if (!$this->user instanceof User)
            {
                return null;
            }

            $isCurrentUserBanned = UserBan::isBanned($this->user);
            $this->updateCommentBanStatus();

            if ($isCurrentUserBanned && !$request->isXmlHttpRequest())
            {
                $session = $request->getSession();
                $bannedId = $session->get('bannedId');
                $userId = $this->user->getUserId();
                $session->set('bannedId', $userId);
                if ($bannedId != $userId)
                {
                    return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $this->user->getUserId())));
                }
            }

            return null;
        }

        private function updateCommentBanStatus()
        {
            $banTime = $this->user->getUpdatedTime();
            $banTime->add(new \DateInterval(UserBan::COMMENT_BAN_TIME));
            $currentTime = new \DateTime();
            if ($this->user->getBanReason() == UserBan::COMMENT && $banTime < $currentTime)
            {
                $this->user->setStatus(UserStatus::ACTIVE);

                $this->context->get("znaika.user_repository")->save($this->user);
            }
        }
    }