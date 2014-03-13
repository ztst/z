<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;

    class UserPhotoUploader
    {
        const UPLOAD_PATH = "/user_photo";
        const PHOTO_SIZE  = 200;

        /**
         * @var ContainerInterface
         */
        private $container;

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function upload(User $user)
        {
            $photo = $user->getPhoto();
            if (null === $photo)
            {
                return;
            }

            $fileDir = $this->getFileDir($user);
            UnixSystemUtils::createDirectory($fileDir, 0755, true);

            $image = new \Imagick($photo->getRealPath());
            $width  = $image->getimagewidth();
            $height = $image->getimageheight();
            if ($width > $height)
            {
                $newHeight = self::PHOTO_SIZE;
                $newWidth  = self::PHOTO_SIZE * $width / $height;
            }
            else
            {
                $newWidth  = self::PHOTO_SIZE;
                $newHeight = self::PHOTO_SIZE * $height / $width;
            }
            $image->scaleimage($newWidth, $newHeight);
            $image->cropimage(self::PHOTO_SIZE, self::PHOTO_SIZE, ($newWidth - self::PHOTO_SIZE) / 2, ($newHeight - self::PHOTO_SIZE) / 2);
            UnixSystemUtils::setFileContents($this->getFilePath($user), $image);

            $user->setHasPhoto(true);
        }

        public function getFilePath(User $user)
        {
            return $this->getFileDir($user) . "photo";
        }

        private function getFileDir(User $user)
        {
            $root    = $this->container->getParameter('upload_file_dir');
            $fileDir = $root . self::UPLOAD_PATH . "/" . $user->getUserId() . "/";

            return $fileDir;
        }
    }