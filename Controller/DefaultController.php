<?php

namespace Positibe\Bundle\CmsBundle\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Positibe\Bundle\CmsBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\CmsBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @param AbstractPage  $contentDocument
     * @param string  $contentTemplate Symfony path of the template to render
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

//        $children = $this->get('doctrine.orm.entity_manager')->getRepository('PositibeCmsBundle:StaticContent')->createPagination($contentDocument);
        $children = new Pagerfanta(new ArrayAdapter($contentDocument->getChildren()->toArray(), true, null));
        $children->setCurrentPage($request->get('page', 1), true, true);
        $children->setMaxPerPage(5);

        $params = array('content' => $contentDocument,'children' => $children );

        return $this->render($contentTemplate, $params);
    }
}
