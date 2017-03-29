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

use Positibe\Bundle\CmsBundle\Entity\Block;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class CmsBlockService
 * @package Positibe\Bundle\CmsBundle\Block\Service
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
abstract class CmsBlockService extends AbstractBlockService
{
    protected $template = 'PositibeCmsBundle:Block:block_simple.html.twig';
    protected $requestStack;

    /**
     * @param string $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param RequestStack $requestStack
     */
    public function __construct($name, $templating, RequestStack $requestStack)
    {
        parent::__construct($name, $templating);
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }

        if ($blockContext->getBlock()->getEnabled()) {
            $response = $this->renderResponse(
                $blockContext->getTemplate(),
                array('block' => $blockContext->getBlock(), 'content' => $blockContext->getBlock()),
                $response
            );
        }

        $response->setTtl($blockContext->getSetting('ttl'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'template' => $this->template,
                'request' => false
            )
        );
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param BlockInterface|Block $block
     * @return array
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(
            'block_id' => $block->getName(),
            'request_uri' => $this->requestStack->getMasterRequest()->getRequestUri(),
            'request_locale' => $this->requestStack->getMasterRequest()->getLocale()
        );
    }
} 