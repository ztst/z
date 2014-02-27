<?
    namespace Znaika\FrontendBundle\Form\DataTransformer;

    use Symfony\Component\Form\DataTransformerInterface;
    use Symfony\Component\Form\Exception\UnexpectedTypeException;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoUtil;

    class VideoUrlTransformer implements DataTransformerInterface
    {
        /**
         * @param string $value
         *
         * @return string
         *
         * @throws UnexpectedTypeException if the given value is not a string instance
         */
        public function reverseTransform($value)
        {
            if (null === $value)
            {
                return null;
            }

            if (!is_string($value))
            {
                throw new UnexpectedTypeException($value, 'string');
            }

            return VideoUtil::getVimeoIdByUrl($value);
        }

        /**
         * @param string $value
         *
         * @return string
         *
         * @throws UnexpectedTypeException
         */
        public function transform($value)
        {
            if (null === $value)
            {
                return null;
            }

            if (!is_string($value))
            {
                throw new UnexpectedTypeException($value, 'string');
            }

            return VideoUtil::getUrlByVimeoId($value);
        }
    }