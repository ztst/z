<?php

    namespace Znaika\FrontendBundle\Twig;

    use Symfony\Bundle\FrameworkBundle\Translation\Translator;

    class DateExtension extends \Twig_Extension
    {
        /**
         * @var Translator
         */
        private $translator;

        public function __construct(Translator $translator)
        {
            $this->translator = $translator;
        }

        public function getFunctions()
        {
            return array(
                'comment_date' => new \Twig_Function_Method($this, 'renderCommentDate'),
            );
        }

        /**
         * @param User $user
         *
         * @return string
         */
        public function renderCommentDate(\DateTime $createdDate)
        {
            $result = $createdDate->format("j") . " %month% в " . $createdDate->format("H:i");

            $today = new \DateTime("today");
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
            $result = str_replace('%month%', $this->translator->trans('month_' . $createdDate->format("M")), $result );

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_date_extension';
        }
    }
