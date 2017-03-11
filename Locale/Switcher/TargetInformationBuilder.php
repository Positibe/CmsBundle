<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Locale\Switcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class TargetInformationBuilder
 * @package Lunetics\LocaleBundle\Switcher
 *
 * @todo revisar la ultima versión de LunelicsLocaleBundle
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TargetInformationBuilder
{
    private $request;
    private $router;
    private $showCurrentLocale;
    private $useController;
    private $allowedLocales;

    /**
     * @param Request $request Request
     * @param RouterInterface $router Router
     * @param array $allowedLocales Config Var
     * @param bool $showCurrentLocale Config Var
     * @param bool $useController Config Var
     */
    public function __construct(
        Request $request,
        RouterInterface $router,
        $allowedLocales = array(),
        $showCurrentLocale = false,
        $useController = false
    ) {
        $this->request = $request;
        $this->router = $router;
        $this->allowedLocales = $allowedLocales;
        $this->showCurrentLocale = $showCurrentLocale;
        $this->useController = $useController;
    }

    /**
     * Builds a bunch of informations in order to build a switcher template
     * for custom needs
     *
     * Will return something like this (let's say current locale is fr :
     *
     * current_route: hello_route
     * current_locale: fr
     * locales:
     *   en:
     *     link: http://app_dev.php/en/... or http://app_dev.php?_locale=en
     *     locale: en
     *     locale_target_language: English
     *     locale_current_language: Anglais
     *
     * @param string|null $targetRoute The target route
     * @param array $parameters Parameters
     *
     * @return array           Informations for the switcher template
     */
    public function getTargetInformations($targetRoute = null, $parameters = array())
    {
        $request = $this->request;
        $router = $this->router;

        $infos = array();

        $infos['current_locale'] = $request->getLocale();
        $infos['current_route'] = $request->attributes->get('_route');
        $infos['locales'] = array();

        foreach ($this->allowedLocales as $locale) {

            $targetLocaleTargetLang = \Locale::getDisplayLanguage($locale, $locale);
            $targetLocaleCurrentLang = \Locale::getDisplayLanguage($locale, $request->getLocale());

            $switchRoute = $router->generate(
                'positibe_change_locale',
                array('newLocale' => $locale, 'redirectRoute' => $targetRoute)
            );

            $infos['locales'][$locale] = array(
                'locale_current_language' => $targetLocaleCurrentLang,
                'locale_target_language' => $targetLocaleTargetLang,
                'link' => $switchRoute,
                'locale' => $locale,
            );
        }

        return $infos;
    }
}
