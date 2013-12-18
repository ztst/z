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
     * @var \Znaika\FrontendBundle\Entity\Profile\User
     */
    private $user;


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
     * Set user
     *
     * @param \Znaika\FrontendBundle\Entity\Profile\User $user
     * @return VideoComment
     */
    public function setUser(\Znaika\FrontendBundle\Entity\Profile\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Znaika\FrontendBundle\Entity\Profile\User
     */
    public function getUser()
    {
        return $this->user;
    }
}