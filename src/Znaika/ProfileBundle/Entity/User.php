<?
    namespace Znaika\ProfileBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Security\Core\User\AdvancedUserInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\LikesBundle\Entity\VideoCommentLike;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\ProfileBundle\Entity\Ban\Info;
    use Znaika\ProfileBundle\Helper\Util\UserBan;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\ProfileBundle\Helper\Util\UserStatus;
    use FOS\MessageBundle\Model\ParticipantInterface;

    class User implements AdvancedUserInterface, ParticipantInterface, \Serializable
    {
        const SALT = "iHc26#r8AQ6@Vyo6^23!hMm";

        /**
         * @var integer
         */
        private $userId;
        /**
         * @var string
         */
        private $firstName;

        /**
         * @var string
         */
        private $lastName;

        /**
         * @var string
         */
        private $middleName;

        /**
         * @var string
         */
        private $email;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var string
         */
        private $password;

        /**
         * @var integer
         */
        private $status;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoComments;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $userRegistrations;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $changeUserEmails;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $passwordRecoveries;

        /**
         * @var integer
         */
        private $sex = 0;

        /**
         * @var integer
         */
        private $points = 0;

        /**
         * @var \DateTime
         */
        private $birthDate;

        /**
         * @var integer
         */
        private $role = UserRole::ROLE_USER;

        /**
         * @var integer
         */
        private $vkId;

        /**
         * @var integer
         */
        private $facebookId;

        /**
         * @var integer
         */
        private $odnoklassnikiId;

        /**
         * @var string
         */
        private $nickname;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $supervisedVideos;

        /**
         * @var integer
         */
        private $grade;

        /**
         * @var Region
         */
        private $region;

        /**
         * @var string
         */
        private $city;

        /**
         * @var UploadedFile
         */
        private $photo;

        /**
         * @var integer
         */
        private $banReason = UserBan::NO_REASON;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $banInfos;

        /**
         * @var \DateTime
         */
        private $updatedTime;

        /**
         * @var string
         */
        private $photoFileName = null;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoCommentLikes;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoLikes;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $parentRelations;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $childRelations;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $teacherSubjects;

        /**
         * @var string
         */
        private $teacherAchievement;

        /**
         * @var int
         */
        private $teacherExperience;

        /**
         * @var UserSettings
         */
        private $userSettings;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videoComments     = new ArrayCollection();
            $this->userRegistrations = new ArrayCollection();
            $this->changeUserEmails  = new ArrayCollection();
            $this->supervisedVideos  = new ArrayCollection();
            $this->banInfos          = new ArrayCollection();
            $this->videoCommentLikes = new ArrayCollection();
            $this->videoLikes        = new ArrayCollection();
            $this->parentRelations   = new ArrayCollection();
            $this->childRelations    = new ArrayCollection();
            $this->teacherSubjects   = new ArrayCollection();
        }

        /**
         * Get userId
         *
         * @return integer
         */
        public function getUserId()
        {
            return $this->userId;
        }

        /**
         * Set firstName
         *
         * @param string $firstName
         *
         * @return User
         */
        public function setFirstName($firstName)
        {
            $this->firstName = $firstName;

            return $this;
        }

        /**
         * Get firstName
         *
         * @return string
         */
        public function getFirstName()
        {
            return $this->firstName;
        }

        /**
         * Set lastName
         *
         * @param string $lastName
         *
         * @return User
         */
        public function setLastName($lastName)
        {
            $this->lastName = $lastName;

            return $this;
        }

        /**
         * Get lastName
         *
         * @return string
         */
        public function getLastName()
        {
            return $this->lastName;
        }

        /**
         * @param string $middleName
         */
        public function setMiddleName($middleName)
        {
            $this->middleName = $middleName;
        }

        /**
         * @return string
         */
        public function getMiddleName()
        {
            return $this->middleName;
        }

        /**
         * Set email
         *
         * @param string $email
         *
         * @return User
         */
        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get email
         *
         * @return string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return User
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;

            return $this;
        }

        /**
         * Get createdTime
         *
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }

        /**
         * @inheritDoc
         */
        public function getRoles()
        {
            return array(UserRole::getSecurityTextByRole($this->getRole()));
        }

        /**
         * @inheritDoc
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * Set password
         *
         * @param string $password
         *
         * @return User
         */
        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function getSalt()
        {
            return $this->getCreatedTime()->getTimestamp() . User::SALT;
        }

        /**
         * @inheritDoc
         */
        public function getUsername()
        {
            return $this->email;
        }

        /**
         * @inheritDoc
         */
        public function eraseCredentials()
        {
        }

        /**
         * Add videoComments
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         *
         * @return User
         */
        public function addVideoComment(VideoComment $videoComment)
        {
            $videoComment->setUser($this);
            $this->videoComments[] = $videoComment;

            return $this;
        }

        /**
         * Remove videoComments
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         */
        public function removeVideoComment(VideoComment $videoComment)
        {
            $this->videoComments->removeElement($videoComment);
        }

        /**
         * Get videoComments
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getVideoComments()
        {
            return $this->videoComments;
        }

        /**
         * @param int $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return int
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * Checks whether the user's account has expired.
         *
         * Internally, if this method returns false, the authentication system
         * will throw an AccountExpiredException and prevent login.
         *
         * @return Boolean true if the user's account is non expired, false otherwise
         *
         * @see AccountExpiredException
         */
        public function isAccountNonExpired()
        {
            return true;
        }

        /**
         * Checks whether the user is locked.
         *
         * Internally, if this method returns false, the authentication system
         * will throw a LockedException and prevent login.
         *
         * @return Boolean true if the user is not locked, false otherwise
         *
         * @see LockedException
         */
        public function isAccountNonLocked()
        {
            return true;
        }

        /**
         * Checks whether the user's credentials (password) has expired.
         *
         * Internally, if this method returns false, the authentication system
         * will throw a CredentialsExpiredException and prevent login.
         *
         * @return Boolean true if the user's credentials are non expired, false otherwise
         *
         * @see CredentialsExpiredException
         */
        public function isCredentialsNonExpired()
        {
            return true;
        }

        /**
         * Checks whether the user is enabled.
         *
         * Internally, if this method returns false, the authentication system
         * will throw a DisabledException and prevent login.
         *
         * @return Boolean true if the user is enabled, false otherwise
         *
         * @see DisabledException
         */
        public function isEnabled()
        {
            return UserStatus::isActive($this->status);
        }

        /**
         * Add userRegistrations
         *
         * @param \Znaika\ProfileBundle\Entity\UserRegistration $userRegistrations
         *
         * @return User
         */
        public function addUserRegistration(UserRegistration $userRegistrations)
        {
            $this->userRegistrations[] = $userRegistrations;

            return $this;
        }

        /**
         * Remove userRegistrations
         *
         * @param \Znaika\ProfileBundle\Entity\UserRegistration $userRegistrations
         */
        public function removeUserRegistration(UserRegistration $userRegistrations)
        {
            $this->userRegistrations->removeElement($userRegistrations);
        }

        /**
         * Get userRegistrations
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getUserRegistrations()
        {
            return $this->userRegistrations;
        }

        /**
         * Returns last user registration or new user registration object
         *
         * @return UserRegistration
         */
        public function getLastUserRegistration()
        {
            if ($this->userRegistrations->count())
            {
                $userRegistration = $this->userRegistrations->last();
            }
            else
            {
                $userRegistration = new UserRegistration();
                $userRegistration->setUser($this);
            }

            return $userRegistration;
        }

        /**
         * Add passwordRecoveries
         *
         * @param \Znaika\ProfileBundle\Entity\PasswordRecovery $passwordRecoveries
         *
         * @return User
         */
        public function addPasswordRecovery(PasswordRecovery $passwordRecoveries)
        {
            $this->passwordRecoveries[] = $passwordRecoveries;

            return $this;
        }

        /**
         * Remove passwordRecoveries
         *
         * @param \Znaika\ProfileBundle\Entity\PasswordRecovery $passwordRecoveries
         */
        public function removePasswordRecovery(PasswordRecovery $passwordRecoveries)
        {
            $this->passwordRecoveries->removeElement($passwordRecoveries);
        }

        /**
         * Get passwordRecoveries
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getPasswordRecoveries()
        {
            return $this->passwordRecoveries;
        }

        /**
         * Returns last password recovery or new user recovery object
         *
         * @return PasswordRecovery
         */
        public function getLastPasswordRecovery()
        {
            if ($this->passwordRecoveries->count())
            {
                $passwordRecovery = $this->passwordRecoveries->last();
            }
            else
            {
                $passwordRecovery = new PasswordRecovery();
                $passwordRecovery->setUser($this);
            }

            return $passwordRecovery;
        }

        /**
         * Set sex
         *
         * @param integer $sex
         *
         * @return User
         */
        public function setSex($sex)
        {
            $this->sex = $sex;

            return $this;
        }

        /**
         * Get sex
         *
         * @return integer
         */
        public function getSex()
        {
            return $this->sex;
        }

        /**
         * Gets the unique identifier of the participant
         *
         * @return integer
         */
        public function getId()
        {
            return $this->getUserId();
        }

        public function __toString()
        {
            $firstName = $this->getFirstName();
            if (trim($firstName) != "")
            {
                $name = $this->getFirstName();
                $name = $this->role != UserRole::ROLE_USER ? $name . " " . $this->getMiddleName() : $name;
                $name .= " " . $this->getLastName();
                $name = trim($name);
            }
            else
            {
                $name = $this->getNickname();
            }

            return (string)$name;
        }

        /**
         * @param int $points
         */
        public function setPoints($points)
        {
            $this->points = $points;
        }

        /**
         * @return int
         */
        public function getPoints()
        {
            return $this->points;
        }

        /**
         * @param \DateTime $birthDate
         */
        public function setBirthDate($birthDate)
        {
            $this->birthDate = $birthDate;
        }

        /**
         * @return \DateTime
         */
        public function getBirthDate()
        {
            return $this->birthDate;
        }

        /**
         * @param int $role
         */
        public function setRole($role)
        {
            $this->role = $role;
        }

        /**
         * @return int
         */
        public function getRole()
        {
            return $this->role;
        }

        /**
         * @param int $facebookId
         */
        public function setFacebookId($facebookId)
        {
            $this->facebookId = $facebookId;
        }

        /**
         * @return int
         */
        public function getFacebookId()
        {
            return $this->facebookId;
        }

        /**
         * @param int $vkId
         */
        public function setVkId($vkId)
        {
            $this->vkId = $vkId;
        }

        /**
         * @return int
         */
        public function getVkId()
        {
            return $this->vkId;
        }

        /**
         * @param int $odniklassnikiId
         */
        public function setOdnoklassnikiId($odniklassnikiId)
        {
            $this->odnoklassnikiId = $odniklassnikiId;
        }

        /**
         * @return int
         */
        public function getOdnoklassnikiId()
        {
            return $this->odnoklassnikiId;
        }

        /**
         * @param string $nickname
         */
        public function setNickname($nickname)
        {
            $this->nickname = $nickname;
        }

        /**
         * @return string
         */
        public function getNickname()
        {
            return $this->nickname;
        }

        public function serialize()
        {
            return serialize(array(
                $this->userId,
                $this->password,
                $this->firstName,
                $this->lastName,
                $this->createdTime,
            ));
        }

        public function unserialize($serialized)
        {
            list(
                $this->userId,
                $this->password,
                $this->firstName,
                $this->lastName,
                $this->createdTime,
                ) = unserialize($serialized);
        }

        public function addSupervisedVideo(Video $supervisedVideo)
        {
            $this->supervisedVideos[] = $supervisedVideo;

            return $this;
        }

        public function removeSupervisedVideo(Video $supervisedVideo)
        {
            $this->supervisedVideos->removeElement($supervisedVideo);
        }

        public function getSupervisedVideos()
        {
            return $this->supervisedVideos;
        }

        public function addBanInfo(Info $info)
        {
            $this->banInfos[] = $info;

            return $this;
        }

        public function removeBanInfo(Info $info)
        {
            $this->banInfos->removeElement($info);
        }

        public function getBanInfos()
        {
            return $this->banInfos;
        }

        public function setGrade($grade)
        {
            $this->grade = $grade;
        }

        public function getGrade()
        {
            return $this->grade;
        }

        /**
         * @param Region $region
         *
         * @return User
         */
        public function setRegion(Region $region)
        {
            $this->region = $region;

            return $this;
        }

        /**
         * @return Region
         */
        public function getRegion()
        {
            return $this->region;
        }

        /**
         * @param string $city
         */
        public function setCity($city)
        {
            $this->city = $city;
        }

        /**
         * @return string
         */
        public function getCity()
        {
            return $this->city;
        }

        /**
         * Sets file.
         *
         * @param UploadedFile $file
         */
        public function setPhoto(UploadedFile $file = null)
        {
            $this->photo = $file;
        }

        /**
         * Get file.
         *
         * @return UploadedFile
         */
        public function getPhoto()
        {
            return $this->photo;
        }

        public function getBigPhotoUrl()
        {
            $userId        = $this->getUserId();
            $photoFileName = $this->getPhotoFileName();

            return "/user-photo/{$userId}/{$photoFileName}_big";
        }

        public function getSmallPhotoUrl()
        {
            $userId        = $this->getUserId();
            $photoFileName = $this->getPhotoFileName();

            return "/user-photo/{$userId}/{$photoFileName}_small";
        }

        public function addChangeUserEmail(ChangeUserEmail $changeUserEmail)
        {
            $this->changeUserEmails[] = $changeUserEmail;

            return $this;
        }

        public function removeChangeUserEmail(ChangeUserEmail $changeUserEmail)
        {
            $this->changeUserEmails->removeElement($changeUserEmail);
        }

        public function getChangeUserEmails()
        {
            return $this->changeUserEmails;
        }

        public function getLastChangeUserEmail()
        {
            if ($this->changeUserEmails->count())
            {
                $changeUserEmail = $this->changeUserEmails->last();
            }
            else
            {
                $changeUserEmail = new ChangeUserEmail();
                $changeUserEmail->setUser($this);
            }

            return $changeUserEmail;
        }

        public function setUpdatedTime($updatedTime)
        {
            $this->updatedTime = $updatedTime;
        }

        public function getUpdatedTime()
        {
            return $this->updatedTime;
        }

        public function setBanReason($banReason)
        {
            $this->banReason = $banReason;
        }

        public function getBanReason()
        {
            return $this->banReason;
        }

        public function setPhotoFileName($photoFileName)
        {
            $this->photoFileName = $photoFileName;
        }

        public function getPhotoFileName()
        {
            return $this->photoFileName;
        }

        public function addVideoLike(VideoLike $videoLike)
        {
            $this->videoLikes[] = $videoLike;

            return $this;
        }

        public function removeVideoLike(VideoLike $videoLike)
        {
            $this->videoLikes->removeElement($videoLike);
        }

        public function getVideoLikes()
        {
            return $this->videoLikes;
        }

        public function addVideoCommentLike(VideoCommentLike $videoCommentLike)
        {
            $this->videoCommentLikes[] = $videoCommentLike;

            return $this;
        }

        public function removeVideoCommentLike(VideoCommentLike $videoCommentLike)
        {
            $this->videoCommentLikes->removeElement($videoCommentLike);
        }

        public function getVideoCommentLikes()
        {
            return $this->videoCommentLikes;
        }

        public function addParentRelation(UserParentRelation $parentRelation)
        {
            $this->parentRelations[] = $parentRelation;

            return $this;
        }

        public function removeParentRelation(UserParentRelation $parentRelation)
        {
            $this->parentRelations->removeElement($parentRelation);
        }

        public function getParentRelations()
        {
            return $this->parentRelations;
        }

        public function addChildRelations(UserParentRelation $relation)
        {
            $this->childRelations[] = $relation;

            return $this;
        }

        public function removeChildRelation(UserParentRelation $relation)
        {
            $this->childRelations->removeElement($relation);
        }

        public function getChildRelations()
        {
            return $this->childRelations;
        }

        public function addTeacherSubject(TeacherSubject $subject)
        {
            $subject->setTeacher($this);
            $this->teacherSubjects[] = $subject;

            return $this;
        }

        public function removeTeacherSubject(TeacherSubject $subject)
        {
            $this->teacherSubjects->removeElement($subject);
        }

        public function getTeacherSubjects()
        {
            return $this->teacherSubjects;
        }

        public function setTeacherAchievement($teacherAchievement)
        {
            $this->teacherAchievement = $teacherAchievement;
        }

        public function getTeacherAchievement()
        {
            return $this->teacherAchievement;
        }

        public function setTeacherExperience($teacherExperience)
        {
            $this->teacherExperience = $teacherExperience;
        }

        public function getTeacherExperience()
        {
            return $this->teacherExperience;
        }

        /**
         * @param \Znaika\ProfileBundle\Entity\UserSettings $userSettings
         */
        public function setUserSettings($userSettings)
        {
            $this->userSettings = $userSettings;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\UserSettings
         */
        public function getUserSettings()
        {
            if (!$this->userSettings)
            {
                $this->userSettings = new UserSettings();
                $this->userSettings->setUser($this);
            }

            return $this->userSettings;
        }
    }