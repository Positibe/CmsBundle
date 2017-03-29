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
use Positibe\Bundle\CmsBundle\Repository\PageRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class PagesByCategoryBlockService
 * @package Positibe\Bundle\CmsBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PagesByCategoryBlockService extends AbstractBlockService
{
    protected $template = 'PositibeCmsBundle:Block:block_contents.html.twig';
    protected $em;

    /**
     * @param string $name 'positibe_cms.pages_by_category'
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

        $parentName = $blockContext->getSetting('category');
        $count = $blockContext->getSetting('count');

        if ($parent = $this->getCategoryRepository()->findOneByName($parentName)) {
            if ($contents = $this->getContentRepository()->findContentByCategory($parent, $count)) {
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
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'template' => $this->template,
                'category' => null,
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
        return $this->em->getRepository('PositibeCmsBundle:Page');
    }

    /**
     * @return PageRepository
     */
    public function getCategoryRepository()
    {
        return $this->em->getRepository('PositibeCmsBundle:Category');
    }
} 