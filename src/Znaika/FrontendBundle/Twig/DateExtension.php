<?php

    namespace Znaika\FrontendBundle\Twig;

    use Symfony\Bundle\FrameworkBundle\Translation\Translator;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class DateExtension extends \Twig_Extension
    {
        const EUROPE_MOSCOW = "Europe/Moscow";
        const USER_TIMEZONE_COOKIE = 'tz';
        /**
         * @var Translator
         */
        private $translator;

        /**
         * @var ContainerInterface
         */
        private $container;

        public function __construct(ContainerInterface $container, Translator $translator)
        {
            $this->translator = $translator;
            $this->container  = $container;
        }

        public function getFunctions()
        {
            return array(
                'comment_date' => new \Twig_Function_Method($this, 'renderCommentDate'),
            );
        }

        /**
         * @param \DateTime $createdDate
         *
         * @return string
         */
        public function renderCommentDate(\DateTime $createdDate)
        {
            $createdDate->setTimezone(new \DateTimeZone($this->getUserTimezone()));
            $result = $createdDate->format("j") . " %month% в " . $createdDate->format("H:i");

            $today     = new \DateTime("today");
            $yesterday = new \DateTime("yesterday");
            if ($createdDate > $today)
            {
                $result = "сегодня в " . $createdDate->format("H:i");;
            }
            elseif ($createdDate > $yesterday)
            {
                $result = "вчера в " . $createdDate->format("H:i");;
            }
            elseif ($createdDate->format("Y") < $today->format("Y"))
            {
                $result = $createdDate->format("j") . " %month% " . $createdDate->format("Y");
            }
            $result = str_replace('%month%', $this->translator->trans('month_' . $createdDate->format("M")), $result);

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_date_extension';
        }

        private function getUserTimezone()
        {
            /** @var Request $request */
            $request = $this->container->get("request");

            return $request->cookies->get(self::USER_TIMEZONE_COOKIE, self::EUROPE_MOSCOW);
        }

    }
