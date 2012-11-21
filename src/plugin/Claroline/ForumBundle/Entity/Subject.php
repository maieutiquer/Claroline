<?php

namespace Claroline\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Claroline\ForumBundle\Entity\Message;
use Claroline\ForumBundle\Entity\Forum;

/**
 * @ORM\Entity
 * @ORM\Table(name="claro_forum_subject")
 */
class Subject extends AbstractResource
{

    /**
     * @ORM\Column(type="string", name="title")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Claroline\ForumBundle\Entity\Message", mappedBy="subject", cascade={"persist"})
     */
    protected $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage(Message $message)
    {
        $this->subjects->add($message);
    }

    public function removeMessage(Message $message)
    {
        $this->subjects->removeElement($message);
    }

    public function resetMessages()
    {
        $this->messages = new ArrayCollection();
    }


}