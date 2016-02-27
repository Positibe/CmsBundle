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


use Doctrine\ORM\EntityManager;
use Positibe\Bundle\OrmContentBundle\Entity\Blocks\PageBlock;
use Positibe\Bundle\OrmContentBundle\Entity\Repository\PageRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageBlockService
 * @package Positibe\Bundle\OrmContentBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageBlockService extends BaseBlockService
{
    protected $template = 'PositibeOrmContentBundle:Block:block_static_content.html.twig';
    protected $em;

    /**
     * @param string $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param EntityManager $entityManager
     * @param null $template
     */
    public function __construct($name, $templating, EntityManager $entityManager, $template = null)
    {
        if ($template) {
            $this->template = $template;
        }
        parent::__construct($name, $templating);
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }
        /** @var PageBlock $block */
        $block = $blockContext->getBlock();

        $this->getContentRepository($block)->findOneContent($block->getPage());

        if ($blockContext->getBlock()->getEnabled()) {
            $response = $this->renderResponse(
                $blockContext->getTemplate(),
                array('block' => $block, 'content' => $block->getPage()),
                $response
            );
        }

        $response->setTtl($blockContext->getSetting('ttl'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'template' => $this->template
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
     * @param PageBlock $pageBlock
     * @return \Doctrine\ORM\EntityRepository|PageRepository
     */
    public function getContentRepository(PageBlock $pageBlock)
    {
        return $this->em->getRepository(get_class($pageBlock->getPage()));
    }

    public function getCacheKeys(BlockInterface $block)
    {
        return array('block_id' => $block->getName());
    }
} 