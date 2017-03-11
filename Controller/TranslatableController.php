<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Controller;

use FOS\RestBundle\View\View;
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeReferrersInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as SyliusResourceController;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class TranslatableController
 * @package Positibe\Bundle\ContentBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TranslatableController extends SyliusResourceController
{
    /**
     * Load the correct locale for seo and menus depend of data_locale http parameter
     *
     * @param RequestConfiguration $configuration
     * @return AbstractPage|\Sylius\Component\Resource\Model\ResourceInterface
     */
    public function findOr404(RequestConfiguration $configuration)
    {
        /** @var AbstractPage $page */
        $page = parent::findOr404($configuration);

        if ($page instanceof TranslatableInterface && $dataLocale = $configuration->getRequest()->get('data_locale')) {
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

        return $page;
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


} 