<?php

    namespace Znaika\ProfileBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;

    class TeacherSubject
    {
        /**
         * @var integer
         */
        private $teacherSubjectId;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $teacher;

        /**
         * @var Subject
         */
        private $subject;

        /**
         * @param \DateTime $createdTime
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;
        }

        /**
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Category\Subject $subject
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Category\Subject
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param \Znaika\ProfileBundle\Entity\User $teacher
         */
        public function setTeacher($teacher)
        {
            $this->teacher = $teacher;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getTeacher()
        {
            return $this->teacher;
        }

        /**
         * @param int $teacherSubjectId
         */
        public function setTeacherSubjectId($teacherSubjectId)
        {
            $this->teacherSubjectId = $teacherSubjectId;
        }

        /**
         * @return int
         */
        public function getTeacherSubjectId()
        {
            return $this->teacherSubjectId;
        }

    }