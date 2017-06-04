<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Website;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WebsiteSessionManager
 * @package AppBundle\Website
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class WebsiteSessionManager
{
    const WEBSITE_KEY = 'active_website';

    /** @var  Session */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param User $user
     * @param $domain
     */
    public function active(User $user, $domain)
    {
        if ($domain === '_clear') {
            $this->session->remove(WebsiteSessionManager::WEBSITE_KEY);

            return;
        }

        foreach ($user->getWebsites() as $website) {
            if ($website->getDomain() === $domain and $website->getEnabled()) {
                $this->session->set(WebsiteSessionManager::WEBSITE_KEY, $domain);

                return;
            }
        }

        throw new NotFoundHttpException(
            sprintf('El sitio %s no está disponible en el sistema', str_replace('-', '.', $domain))
        );
    }
}