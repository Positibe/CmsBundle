<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Controller;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FOS\RestBundle\View\View;
use Positibe\Bundle\CmsBundle\Entity\BaseContent;
use Positibe\Bundle\CmsBundle\Entity\Page;
use Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as SyliusResourceController;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Workflow\Exception\ExceptionInterface;

/**
 * Class TranslatableController
 * @package Positibe\Bundle\CmsBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ResourceController extends SyliusResourceController
{
    /**
     * Load the correct locale for seo and menus depend of data_locale http parameter
     *
     * @param RequestConfiguration $configuration
     * @return BaseContent|\Sylius\Component\Resource\Model\ResourceInterface
     */
    public function findOr404(RequestConfiguration $configuration)
    {
        /** @var BaseContent|Page $page */
        $page = parent::findOr404($configuration);

        if ($page instanceof TranslatableInterface && $dataLocale = $configuration->getRequest()->get(
                'data_locale',
                $this->getParameter('locale')
            )
        ) {
            $page->setLocale($dataLocale);

            if ($page instanceof SeoAwareInterface && $seoMetadata = $page->getSeoMetadata()) {
                $seoMetadata->setLocale($dataLocale);
                $this->get('doctrine.orm.entity_manager')->refresh($seoMetadata);
            }

            if ($page instanceof MenuNodeReferrersInterface) {
                foreach ($page->getMenuNodes() as $menu) {
                    $menu->setLocale($dataLocale);
                    $this->get('doctrine.orm.entity_manager')->refresh($menu);
                }
            }

            $this->get('doctrine.orm.entity_manager')->refresh($page);
        }

        if ($transition = $configuration->getRequest()->request->get('transition')) {
            try {
                $this->get('workflow.registry')->get($page)
                    ->apply($page, $transition);
            } catch (ExceptionInterface $e) {
            }
        }

        return $page;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        try {
            return parent::deleteAction($request);
        } catch (ForeignKeyConstraintViolationException $e) {
            $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
            $this->flashHelper->addErrorFlash($configuration, 'not_deleted');

            return $this->redirectHandler->redirectToReferer($configuration);
        }
    }


    public function moveUpAction(Request $request)
    {
        return $this->move($request, 1);
    }

    public function moveDownAction(Request $request)
    {
        return $this->move($request, -1);
    }

    /**
     * @param Request $request
     * @param int $movement
     *
     * @return RedirectResponse
     */
    protected function move(Request $request, $movement)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $resource = $this->findOr404($configuration);

        $position = $configuration->getSortablePosition();
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue(
            $resource,
            $position,
            $accessor->getValue($resource, $position) + $movement
        );

        $this->manager->persist($resource);
        $this->manager->flush();

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($resource, 204));
        }

        $this->flashHelper->addSuccessFlash($configuration, 'move', $resource);

        return $this->redirectHandler->redirectToIndex($configuration, $resource);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function applyTransitionAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        try {
            $resource = $this->findOr404($configuration);

            $this->get('doctrine')->getManager()->flush();
        } catch (ExceptionInterface $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            $resource = null;
        }

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }

    public function deleteMultipleAction(Request $request)
    {
        $resources = $request->get('app_comment_delete_multiple');

        $repository = $this->getDoctrine()->getRepository($this->metadata->getClass('model'));
        $manager = $this->getDoctrine()->getManager();
        foreach ($resources as $id) {
            if ($resource = $repository->find($id)) {
                $manager->remove($resource);
            }
        }
        $manager->flush();

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
} 