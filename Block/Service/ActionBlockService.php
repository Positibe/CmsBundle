<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Block\Service;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class ActionBlockService
 * @package Positibe\Bundle\CmsBundle\Block\Service
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ActionBlockService extends AbstractBlockService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param BlockContextInterface $blockContext
     * @param Response $response
     * @return mixed|Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        //@fixme To load the resource controller of sylius with _sylius config.
        $controller = $this->createController($blockContext->getSetting('action'));

        $parameters = array_merge(
            array('request' => $blockContext->getSetting('request')),
            $blockContext->getSetting('parameters')
        );

        $response = call_user_func_array($controller, $parameters);
        $response->setTtl($blockContext->getSetting('ttl'));

        return $response;
    }

    /**
     * @readme This method is a copy from \Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver::createController
     *
     * Returns a callable for the given controller.
     *
     * @param string $controller A Controller string
     *
     * @return mixed A PHP callable
     *
     * @throws \InvalidArgumentException
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                // controller in the a:b:c notation then
                $controller = $this->container->get('controller_name_converter')->parse($controller);
            } elseif (1 == $count) {
                // controller in the service:method notation
                list($service, $method) = explode(':', $controller, 2);

                return array($this->container->get($service), $method);
            } elseif ($this->container->has($controller) && method_exists($service = $this->container->get($controller), '__invoke')) {
                return $service;
            } else {
                throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'action' => null,
                'parameters' => array(),
                'request' => $this->container->get('request_stack')->getMasterRequest()
            )
        );
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
} 