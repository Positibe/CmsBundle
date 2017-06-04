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

use Sylius\Bundle\ResourceBundle\Controller\ResourceController as SyliusResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WebsiteController
 * @package AppBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class WebsiteController extends SyliusResourceController
{
    public function activeAction(Request $request, $domain)
    {
        try {
            $this->container->get('positibe_cms.website_session_manager')->active($this->getUser(), $domain);
        } catch (NotFoundHttpException $e) {
            $this->addFlash('alert', $e->getMessage());
        }

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}