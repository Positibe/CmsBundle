<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Block;

use Positibe\Bundle\OrmBlockBundle\Block\Service\AbstractBlockService;
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractVisibilityBlock;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class ContentBlockService
 * @package Positibe\Bundle\OrmMediaBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContentBlockService extends AbstractBlockService
{
    protected $template = 'PositibeOrmContentBundle:Block:block_content.html.twig';
    protected $requestStack;

    public function __construct($name, $templating, RequestStack $requestStack)
    {
        parent::__construct($name, $templating);
        $this->requestStack = $requestStack;
    }


    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'template' => $this->template,
                'request' => $this->requestStack->getMasterRequest()
            )
        );
    }

    /**
     * @param BlockInterface|AbstractVisibilityBlock $block
     * @return array
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(
            'block_id' => $block->getName(),
            'request_uri' => $this->requestStack->getMasterRequest()->getRequestUri()
        );
    }
} 