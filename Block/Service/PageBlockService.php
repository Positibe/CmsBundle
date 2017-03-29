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

use Positibe\Bundle\CmsBundle\Entity\PageBlock;
use Positibe\Bundle\CmsBundle\Repository\PageRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PageBlockService
 * @package Positibe\Bundle\CmsBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageBlockService extends CmsBlockService
{
    protected $template = 'PositibeCmsBundle:Block:block_content.html.twig';
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param string $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param RequestStack $requestStack
     */
    public function __construct($name, $templating, RequestStack $requestStack)
    {
        parent::__construct($name, $templating, $requestStack);
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
     * @param PageBlock $pageBlock
     * @return \Doctrine\ORM\EntityRepository|PageRepository
     */
    public function getContentRepository(PageBlock $pageBlock)
    {
        $repository = $this->em->getRepository(get_class($pageBlock->getPage()));

        if (method_exists($repository, 'setLocale')) {
            $repository->setLocale($this->requestStack->getMasterRequest()->getLocale());
        }

        return $repository;
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param mixed $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }


} 