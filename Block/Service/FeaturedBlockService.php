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

use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class FeaturedBlockService
 * @package Positibe\Bundle\CmsBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FeaturedBlockService extends BaseBlockService
{
    protected $template = 'PositibeCmsBundle:Block:block_content.html.twig';
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

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }

        $class = $blockContext->getSetting('class');
        $contents = $this->getContentRepository($class)->findByFeatured();

        if (count($contents) > 0) {
            $response = $this->renderResponse(
                $blockContext->getTemplate(),
                array(
                    'block' => $blockContext->getBlock(),
                    'content' => $contents[0]
                ),
                $response
            );
        }

        $response->setTtl($blockContext->getSetting('ttl'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'template' => $this->template,
                'class' => 'Positibe\Bundle\CmsBundle\Entity\Page'
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
     * @param $class
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getContentRepository($class)
    {
        return $this->em->getRepository($class);
    }

    public function getCacheKeys(BlockInterface $block)
    {
        return array('type' => $block->getType());
    }
} 