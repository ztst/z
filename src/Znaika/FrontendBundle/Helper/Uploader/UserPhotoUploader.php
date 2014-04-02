<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Security\Core\Util\SecureRandom;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;

    class UserPhotoUploader
    {
        const RANDOM_FILE_NAME_LENGTH = 27;
        const UPLOAD_PATH             = "/user_photo";
        const PHOTO_SIZE              = 200;
        const SMALL_PHOTO_SIZE        = 50;

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
            $user->setPhotoFileName($this->generateFileName());

            $fileDir = $this->getFileDir($user);
            UnixSystemUtils::createDirectory($fileDir, 0755, true);

            $this->saveUserPhoto($user, $photo);

        }

        public function deletePhoto(User $user)
        {
            $filePath = $this->getBigPhotoFilePath($user);
            UnixSystemUtils::remove($filePath);
            $filePath = $this->getSmallPhotoFilePath($user);
            UnixSystemUtils::remove($filePath);
        }

        public function getSmallPhotoFilePath(User $user)
        {
            return $this->getFileDir($user) . $user->getPhotoFileName() . "_small";
        }

        public function getBigPhotoFilePath(User $user)
        {
            return $this->getFileDir($user) . $user->getPhotoFileName() . "_big";
        }

        private function getFileDir(User $user)
        {
            $root    = $this->container->getParameter('upload_file_dir');
            $fileDir = $root . self::UPLOAD_PATH . "/" . $user->getUserId() . "/";

            return $fileDir;
        }

        /**
         * @param User $user
         * @param $photo
         */
        private function saveUserPhoto(User $user, UploadedFile $photo)
        {
            $image  = new \Imagick($photo->getRealPath());
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
            $image->cropimage(self::PHOTO_SIZE, self::PHOTO_SIZE, ($newWidth - self::PHOTO_SIZE) / 2,
                ($newHeight - self::PHOTO_SIZE) / 2);
            UnixSystemUtils::setFileContents($this->getBigPhotoFilePath($user), $image);
            $image->scaleimage(self::SMALL_PHOTO_SIZE, self::SMALL_PHOTO_SIZE);
            UnixSystemUtils::setFileContents($this->getSmallPhotoFilePath($user), $image);
        }

        private function generateFileName()
        {
            $generator = new SecureRandom();

            return bin2hex($generator->nextBytes(self::RANDOM_FILE_NAME_LENGTH));
        }
    }