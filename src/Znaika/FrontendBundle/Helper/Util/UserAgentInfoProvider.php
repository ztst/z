<?
    namespace Znaika\FrontendBundle\Helper\Util;

    use UAParser\Parser;
    use UAParser\Result\Client;

    class UserAgentInfoProvider
    {
        /**
         * @var Parser
         */
        private $parser = null;

        /**
         * @var Client
         */
        private $result = null;

        public function __construct()
        {
            $this->parser = Parser::create();
        }

        /**
         * @param string $userAgentString
         */
        public function parse($userAgentString)
        {
            $this->result = $this->parser->parse($userAgentString);
        }

        /**
         * @return string
         */
        public function getUaFamily()
        {
            return $this->result->ua->family;
        }

        /**
         * @return string
         */
        public function getUaMajorVersion()
        {
            return $this->result->ua->major;
        }

        /**
         * @return string
         */
        public function getOsFamily()
        {
            return $this->result->os->family;
        }

        /**
         * @return string
         */
        public function getDevice()
        {
            return $this->result->device->family;
        }
    }