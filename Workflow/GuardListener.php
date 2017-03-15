<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Workflow;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableReadInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodReadInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\Voter\PublishTimePeriodVoter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

/**
 * Class GuardListener
 * @package Positibe\Bundle\CmsBundle\Workflow
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class GuardListener implements EventSubscriberInterface
{
    protected $tokenStorage;
    protected $publishTimePeriodVoter;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->publishTimePeriodVoter = new PublishTimePeriodVoter();
    }

    public function onPublish(GuardEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        /** @var PublishableReadInterface|PublishTimePeriodReadInterface $subject */
        $subject = $event->getSubject();
        if ($this->publishTimePeriodVoter->vote($token, $subject, [PublishWorkflowChecker::VIEW_ATTRIBUTE]) === VoterInterface::ACCESS_DENIED) {
            $event->setBlocked(true);
            return;
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.publishable.guard.publish' => 'onPublish',
            'workflow.publishable.guard.republish' => 'onPublish',
        ];
    }

}