<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Sitemap;

use Positibe\Bundle\CmfRoutingExtraBundle\Entity\AutoRoute;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocaleCollection;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AlternateLocaleProvider
 * @package Positibe\Bundle\CmsBundle\Sitemap
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AlternateLocaleProvider implements AlternateLocaleProviderInterface
{
    protected $requestStack;
    protected $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param RequestStack $requestStack
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * Creates a collection of AlternateLocales for one content object.
     *
     * @param object $content
     *
     * @return AlternateLocaleCollection
     */
    public function createForContent($content)
    {
        $alternateLocaleCollection = new AlternateLocaleCollection();
        if (!$content instanceof TranslatableInterface || !$content instanceof RouteReferrersReadInterface) {
            return $alternateLocaleCollection;
        }

        $currentLocale = $content->getLocale() ?: $this->requestStack->getMasterRequest()->getLocale();
        /** @var AutoRoute $route */
        /* @todo Ver como no ejecutar este script en cada request
        foreach ($content->getRoutes() as $route) {
            if ($route->getLocale() === $currentLocale) {
                continue;
            }

            $alternateLocaleCollection->add(
                new AlternateLocale(
                    $this->urlGenerator->generate(
                        $content,
                        ['_locale' => $route->getLocale()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    $route->getLocale()
                )
            );
        }*/

        return $alternateLocaleCollection;
    }

    /**
     * Creates a collection of AlternateLocales for many content object.
     *
     * @param array|object[] $contents
     *
     * @return AlternateLocaleCollection[]
     */
    public function createForContents(array $contents)
    {
        $result = [];
        foreach ($contents as $content) {
            $result[] = $this->createForContent($content);
        }

        return $result;
    }

}