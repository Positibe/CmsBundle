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

use Positibe\Bundle\CmsBundle\Entity\Category;
use Positibe\Bundle\CmsBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    protected $defaultTemplate = 'content/index.html.twig';
    protected $defaultCategoryTemplate = 'content/category.html.twig';

    /**
     * Render the provided content.
     *
     * When using the publish workflow, enable the publish_workflow.request_listener
     * of the core bundle to have the contentDocument as well as the route
     * checked for being published.
     * We don't need an explicit check in this method.
     *
     * @param Request $request
     * @param RouteReferrersReadInterface $contentDocument
     * @param string $contentTemplate Symfony path of the template to render
     *                                 the content document. If omitted, the
     *                                 default template is used.
     *
     * @return Response
     */
    public function indexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
        $contentTemplate = $contentTemplate ?: $this->defaultTemplate;

        $contentTemplate = str_replace(
            array('{_format}', '{_locale}'),
            array($request->getRequestFormat(), $request->getLocale()),
            $contentTemplate
        );

        $params = array('content' => $contentDocument);

        return $this->render($contentTemplate, $params);
    }

    /**
     * @param Request $request
     * @param Category $contentDocument
     * @param null $contentTemplate
     * @return Response
     */
    public function categoryIndexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
        $contentTemplate = $contentTemplate ?: $this->defaultCategoryTemplate;

        $contentTemplate = str_replace(
            array('{_format}', '{_locale}'),
            array($request->getRequestFormat(), $request->getLocale()),
            $contentTemplate
        );

        $children = $this->get('positibe.repository.page')->createPaginator(
            [
                'category' => $contentDocument->getName(),
                'state' => 'published',
                'can_publish_on_date' => new \DateTime('now'),
            ],
            ['publishStartDate' => 'DESC']
        );

        $params = ['content' => $contentDocument, 'children' => $children];

        return $this->render($contentTemplate, $params);
    }

    public function iframeAction(Page $page)
    {
        return $this->render('@PositibeCms/Page/_iframe.html.twig', ['content' => $page]);
    }
}
