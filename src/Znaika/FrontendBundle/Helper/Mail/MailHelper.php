<?
    namespace Znaika\FrontendBundle\Helper\Mail;

    class MailHelper
    {
        const NO_REPLY_EMAIL = 'no-reply@znaika.ru';
        const HTML_TYPE = 'text/html';
        protected $mailer;

        public function __construct(\Swift_Mailer $mailer)
        {
            $this->mailer = $mailer;
        }

        public function sendEmail($from, $to, $body, $subject = '')
        {
            $from = is_null($from) ? MailHelper::NO_REPLY_EMAIL : $from;

            $message = \Swift_Message::newInstance()
                                     ->setSubject($subject)
                                     ->setFrom($from)
                                     ->setTo($to)
                                     ->setBody($body, MailHelper::HTML_TYPE);

            $this->mailer->send($message);
        }
    }