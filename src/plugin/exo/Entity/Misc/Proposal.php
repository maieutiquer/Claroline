<?php

namespace UJM\ExoBundle\Entity\Misc;

use Claroline\AppBundle\Entity\Identifier\Uuid;
use Claroline\AppBundle\Entity\Meta\Order;
use Doctrine\ORM\Mapping as ORM;
use UJM\ExoBundle\Entity\ItemType\MatchQuestion;
use UJM\ExoBundle\Library\Model\ContentTrait;

/**
 * Proposal.
 *
 * @ORM\Entity()
 * @ORM\Table(name="ujm_proposal")
 */
class Proposal
{
    use ContentTrait;
    use Order;
    use Uuid;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UJM\ExoBundle\Entity\ItemType\MatchQuestion", inversedBy="proposals")
     * @ORM\JoinColumn(name="interaction_matching_id", referencedColumnName="id")
     */
    private $interactionMatching;

    /**
     * Proposal constructor.
     */
    public function __construct()
    {
        $this->refreshUuid();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get InteractionMatching.
     *
     * @return MatchQuestion
     */
    public function getInteractionMatching()
    {
        return $this->interactionMatching;
    }

    /**
     * Set InteractionMatching.
     */
    public function setInteractionMatching(MatchQuestion $interactionMatching)
    {
        $this->interactionMatching = $interactionMatching;
    }
}
