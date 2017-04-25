<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Cache\Sonata;

use Sonata\Cache\Exception\UnsupportedException;
use Sonata\CacheBundle\Adapter\ApcCache as SonataApcCache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @fixme Remove this class and from Compiler when Sonata add support for both Apc and Apcu cache
 * This class only try to improve the apc using checking
 *
 * Class ApcCache
 * @package Positibe\Bundle\CmsBundle\Cache\Sonata
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ApcCache extends SonataApcCache
{
    /**
     * Check that Apc is enabled.
     *
     * @return bool
     *
     * @throws UnsupportedException
     */
    protected function checkApc()
    {
        if (!function_exists('apc_add') || !ini_get('apc.enabled')) {
            throw new UnsupportedException(
                __CLASS__.' does not support data caching. you should install APC or APCu to use it'
            );
        }
    }

    /**
     * Cache action.
     *
     * @param string $token A configured token
     *
     * @return Response
     *
     * @throws AccessDeniedHttpException
     */
    public function cacheAction($token)
    {
        if ($this->token == $token) {
            if (version_compare(PHP_VERSION, '5.5.0', '>=') && function_exists('opcache_reset')) {
                opcache_reset();
            }

            if (function_exists('apc_add') && ini_get('apc.enabled')) {
                apcu_clear_cache();
            }

            return new Response(
                'ok', 200, array(
                'Cache-Control' => 'no-cache, must-revalidate',
                'Content-Length' => 2, // to prevent chunked transfer encoding
            )
            );
        }

        throw new AccessDeniedHttpException('invalid token');
    }

}