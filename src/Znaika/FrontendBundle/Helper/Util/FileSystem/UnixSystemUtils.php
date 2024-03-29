<?
    namespace Znaika\FrontendBundle\Helper\Util\FileSystem;

    class UnixSystemUtils
    {
        public static function renameFile($srcFilePath ,$destFilePath)
        {
            return rename($srcFilePath, $destFilePath);
        }
        /**
         * Creates directory.
         *
         * @param string $dirPath
         * @param int $mode
         * @param bool $recursive
         *
         * @return bool
         */
        public static function createDirectory( $dirPath, $mode = 0770, $recursive = false )
        {
            if (file_exists($dirPath) && is_dir($dirPath))
                return true;
            umask(0);
            return @mkdir($dirPath, $mode, $recursive);
        }

        /**
         * Returns file contents.
         * If file doesn't exists return null.
         *
         * @param string $filePath
         * @return string
         */
        public static function getFileContents($filePath)
        {
            if (file_exists($filePath))
            {
                return file_get_contents($filePath);
            }

            return null;
        }

        public static function setFileContents($filePath, $content)
        {
            return file_put_contents($filePath, $content);
        }

        /**
         * Removes resource in specified path.
         *
         * @param string $path
         * @return bool
         */
        public static function remove($path)
        {
            if (!file_exists($path))
            {
                return false;
            }

            if (is_dir($path))
            {
                $files = self::getDirectoryFiles($path);
                foreach ($files as $file)
                {
                    self::remove($path . "/" . $file);
                }

                rmdir($path);
            }
            else
            {
                unlink($path);
            }
            return true;
        }

        /**
         * Returns list of files located in directory.
         *
         * @param string $dirPath
         * @param string $mask regex pattern
         * @return array
         */
        public static function getDirectoryFiles($dirPath, $mask = null)
        {
            if ( !( file_exists($dirPath) && is_dir($dirPath) ) )
            {
                return array();
            }

            if ( is_null($mask) )
            {
                $mask = "/.*/";
            }

            $dirIterator = new \DirectoryIterator($dirPath);
            $regexIterator = new \RegexIterator($dirIterator, $mask, \RegexIterator::GET_MATCH);

            $files = array();
            foreach ($regexIterator as $iterator)
            {
                $fileName = $iterator[0];
                if ($fileName != "." && $fileName != "..")
                {
                    $files[] = $fileName;
                }
            }

            return $files;
        }

        /**
         * Remove all files from directory
         *
         * @static
         * @param $dirPath
         * @param array $exclude
         */
        public static function clearDirectory($dirPath, $exclude = array())
        {
            if (!is_dir($dirPath))
            {
                return;
            }
            $files = self::getDirectoryFiles($dirPath);
            if (empty($files))
            {
                return;
            }

            foreach ($files as $file)
            {
                $filePath = "$dirPath/$file";
                if (!in_array($filePath, $exclude))
                {
                    self::remove($filePath);
                }
            }
        }

        public static function getFileExtension($filePath)
        {
            return pathinfo($filePath, PATHINFO_EXTENSION);
        }

        public static function getPathInfoFileName($filePath)
        {
            return pathinfo($filePath, PATHINFO_FILENAME);
        }

        public static function getPathInfoBaseName($filePath)
        {
            return pathinfo($filePath, PATHINFO_BASENAME);
        }

        public static function getFilePathWithNewExtension($filePath, $newExtension)
        {
            $pathParts = pathinfo($filePath);
            return $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $newExtension;
        }
    }