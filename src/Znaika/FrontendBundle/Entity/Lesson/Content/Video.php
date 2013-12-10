<?
namespace Znaika\FrontendBundle\Entity\Lesson\Content;

use Doctrine\ORM\Mapping as ORM;

class Video
{

    /**
     * @var integer
     */
    private $videoId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $grade;

    /**
     * @var string
     */
    private $urlName;

    /**
     * @var \DateTime
     */
    private $createdTime;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \Znaika\FrontendBundle\Entity\Lesson\Category\Subject
     */
    private $subject;


    /**
     * Get videoId
     *
     * @return integer
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Video
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set grade
     *
     * @param integer $grade
     * @return Video
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return integer
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set urlName
     *
     * @param string $urlName
     * @return Video
     */
    public function setUrlName($urlName)
    {
        $this->urlName = $urlName;

        return $this;
    }

    /**
     * Get urlName
     *
     * @return string
     */
    public function getUrlName()
    {
        return $this->urlName;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return Video
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
     * Set subject
     *
     * @param \Znaika\FrontendBundle\Entity\Lesson\Category\Subject $subject
     * @return Video
     */
    public function setSubject(\Znaika\FrontendBundle\Entity\Lesson\Category\Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \Znaika\FrontendBundle\Entity\Lesson\Category\Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}