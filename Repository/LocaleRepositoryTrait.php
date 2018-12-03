<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\HttpFoundation\RequestStack;


/***
 * Class LocaleRepositoryTrait
 * @package Positibe\Bundle\CoreBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait LocaleRepositoryTrait
{
    protected $locale;

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        if ($request = $requestStack->getMasterRequest()) {
            $this->locale = $request->getLocale();
        }

        return null;
    }

    public function getQuery(QueryBuilder $qb)
    {
        $query = $qb->getQuery();
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        if ($this->locale) {
            $query->setHint(
                TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                $this->locale // take locale from session or request etc.
            );

            $query->setHint(
                TranslatableListener::HINT_FALLBACK,
                1 // fallback to default values in case if record is not translated
            );
        }

        return $query;
    }
} 