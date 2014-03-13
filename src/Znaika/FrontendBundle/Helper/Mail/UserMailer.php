<?
    namespace Znaika\FrontendBundle\Helper\Mail;

    use Znaika\FrontendBundle\Entity\Profile\ChangeUserEmail;
    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;
    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;

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
            $this->twig       = $twig;
            $this->mailHelper = $mailHelper;
        }

        public function sendRegisterConfirm(UserRegistration $userRegistration)
        {
            $templateFile    = "ZnaikaFrontendBundle:Email:userRegistration.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $body            = $templateContent->render(array("registerKey" => $userRegistration->getRegisterKey()));
            $subject         = $this->getEmailSubject($templateContent);

            $this->mailHelper->sendEmail(null, $userRegistration->getUser()->getEmail(), $body, $subject);
        }

        public function sendRegisterWithPasswordGenerateConfirm(UserRegistration $userRegistration, $password)
        {
            $sendTo          = $userRegistration->getUser()->getEmail();
            $templateFile    = "ZnaikaFrontendBundle:Email:userRegistrationWithGeneratePassword.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $body            = $templateContent->render(array(
                "registerKey" => $userRegistration->getRegisterKey(),
                "password"    => $password,
                "email"       => $sendTo
            ));
            $subject         = $this->getEmailSubject($templateContent);

            $this->mailHelper->sendEmail(null, $sendTo, $body, $subject);
        }

        public function sendPasswordRecoveryConfirm(PasswordRecovery $passwordRecovery)
        {
            $templateFile    = "ZnaikaFrontendBundle:Email:passwordRecovery.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $body            = $templateContent->render(
                                               array(
                                                   "recoveryKey" => $passwordRecovery->getRecoveryKey(),
                                                   "email"       => $passwordRecovery->getUser()->getEmail()
                                               ));
            $subject         = $this->getEmailSubject($templateContent);

            $this->mailHelper->sendEmail(null, $passwordRecovery->getUser()->getEmail(), $body, $subject);
        }

        public function sendChangeEmailConfirm(ChangeUserEmail $changeUserEmail)
        {
            $templateFile    = "ZnaikaFrontendBundle:Email:changeEmail.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $body            = $templateContent->render(
                                               array(
                                                   "changeKey" => $changeUserEmail->getChangeKey(),
                                               ));
            $subject         = $this->getEmailSubject($templateContent);

            $this->mailHelper->sendEmail(null, $changeUserEmail->getNewEmail(), $body, $subject);
        }

        /**
         * @param $templateContent
         *
         * @return string
         */
        private function getEmailSubject($templateContent)
        {
            $subject = ($templateContent->hasBlock("subject") ? $templateContent->renderBlock("subject", array()) : "");
            $subject = trim($subject);

            return $subject;
        }
    }