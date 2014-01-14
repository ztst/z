<?
    namespace Znaika\FrontendBundle\Helper\Mail;

    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;

    class UserMailer
    {
        /**
         * @var MailHelper
         */
        protected $mailHelper;

        /**
         * @var \Twig_Environment
         */
        protected $twig;

        public function __construct(MailHelper $mailHelper, \Twig_Environment $twig)
        {
            $this->twig = $twig;
            $this->mailHelper = $mailHelper;
        }

        public function sendRegisterConfirm(UserRegistration $userRegistration)
        {
            $templateFile = "ZnaikaFrontendBundle:Email:userRegistration.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $body = $templateContent->render(array("registerKey" => $userRegistration->getRegisterKey()));

            $subject = ($templateContent->hasBlock("subject") ? $templateContent->renderBlock("subject", array()) : "");
            $subject = trim($subject);

            $this->mailHelper->sendEmail(null, $userRegistration->getUser()->getEmail(), $body, $subject);
        }
    }