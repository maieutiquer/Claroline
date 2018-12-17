<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Listener\Tool;

use Claroline\AppBundle\API\SerializerProvider;
use Claroline\AppBundle\Persistence\ObjectManager;
use Claroline\CoreBundle\Event\DisplayToolEvent;
use Claroline\CoreBundle\Repository\ResourceNodeRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @DI\Service
 */
class TrashListener
{
    /** @var AuthorizationCheckerInterface */
    private $authorization;
    /** @var TwigEngine */
    private $templating;
    /** @var SerializerProvider */
    private $serializer;
    /** @var ResourceNodeRepository */
    private $resourceRepository;

    /**
     * ResourcesListener constructor.
     *
     * @DI\InjectParams({
     *     "authorization" = @DI\Inject("security.authorization_checker"),
     *     "templating"    = @DI\Inject("templating"),
     *     "om"            = @DI\Inject("claroline.persistence.object_manager"),
     *     "serializer"    = @DI\Inject("claroline.api.serializer")
     * })
     *
     * @param AuthorizationCheckerInterface $authorization
     * @param TwigEngine                    $templating
     * @param ObjectManager                 $om
     * @param SerializerProvider            $serializer
     */
    public function __construct(
        AuthorizationCheckerInterface $authorization,
        TwigEngine $templating,
        ObjectManager $om,
        SerializerProvider $serializer
    ) {
        $this->authorization = $authorization;
        $this->templating = $templating;
        $this->serializer = $serializer;
        $this->resourceRepository = $om->getRepository('ClarolineCoreBundle:Resource\ResourceNode');
    }

    /**
     * @DI\Observe("open_tool_workspace_resource_trash")
     *
     * @param DisplayToolEvent $event
     */
    public function onDisplayWorkspace(DisplayToolEvent $event)
    {
        $workspace = $event->getWorkspace();

        $content = $this->templating->render(
            'ClarolineCoreBundle:tool:trash.html.twig', [
                'workspace' => $workspace,
            ]
        );

        $event->setContent($content);
        $event->stopPropagation();
    }
}
