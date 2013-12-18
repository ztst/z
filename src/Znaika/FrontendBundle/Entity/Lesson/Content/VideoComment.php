<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Doctrine\ORM\Mapping as ORM;

    class VideoComment
    {

        /**
     * @var integer
     */
    private $videoCommentId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $createdTime;

    /**
     * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Video
     */
    private $video;

    /**
     * @var \Znaika\FrontendBundle\Entity\User\UserInfo
     */
    private $userInfo;


    /**
     * Get videoCommentId
     *
     * @return integer 
     */
    public function getVideoCommentId()
    {
        return $this->videoCommentId;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return VideoComment
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return VideoComment
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
     * Set video
     *
     * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
     * @return VideoComment
     */
    public function setVideo(\Znaika\FrontendBundle\Entity\Lesson\Content\Video $video = null)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Video 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set userInfo
     *
     * @param \Znaika\FrontendBundle\Entity\User\UserInfo $userInfo
     * @return VideoComment
     */
    public function setUserInfo(\Znaika\FrontendBundle\Entity\User\UserInfo $userInfo = null)
    {
        $this->userInfo = $userInfo;
    
        return $this;
    }

    /**
     * Get userInfo
     *
     * @return \Znaika\FrontendBundle\Entity\User\UserInfo 
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }
}