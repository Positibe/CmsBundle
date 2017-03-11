<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Block\Service;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\ContentBundle\Entity\Repository\PageRepository;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class CategoryBlockService
 * @package Positibe\Bundle\ContentBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class CategoryBlockService extends BaseBlockService
{
    protected $template = 'PositibeContentBundle:Block:block_contents.html.twig';
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

        $parentName = $blockContext->getSetting('name');
        $count = $blockContext->getSetting('count');

        if ($parent = $this->getCategoryRepository()->findOneByName($parentName)) {
            if ($contents = $this->getContentRepository()->findContentByParent($parent, $count)) {
                $response = $this->renderResponse(
                    $blockContext->getTemplate(),
                    array(
                        'block' => $blockContext->getBlock(),
                        'contents' => $contents,
                        'category' => $parent
                    ),
                    $response
                );
            }
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
                'template' => $this->template,
                'name' => null,
                'count' => 3
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
     * @return PageRepository
     */
    public function getContentRepository()
    {
        return $this->em->getRepository('PositibeContentBundle:Page');
    }

    /**
     * @return PageRepository
     */
    public function getCategoryRepository()
    {
        return $this->em->getRepository('PositibeContentBundle:Category');
    }
} 