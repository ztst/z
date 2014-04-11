<?
    namespace Znaika\ProfileBundle\Helper\Encode;

    class RegisterKeyEncoder
    {
        public function encode($raw, $salt)
        {
            return hash('sha256', $salt . $raw);
        }

        public function isValid($encoded, $raw, $salt)
        {
            return $encoded === $this->encode($raw, $salt);
        }
    }