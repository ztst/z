<?
namespace Znaika\FrontendBundle\Entity\Lesson\Category;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 */
class Subject
{

    /**
     * @var integer
     */
    private $subjectId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $urlName;

    /**
     * @var \DateTime
     */
    private $createdTime;


    /**
     * Get subjectId
     *
     * @return integer 
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Subject
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
     * Set urlName
     *
     * @param string $urlName
     * @return Subject
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
     * @return Subject
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
}