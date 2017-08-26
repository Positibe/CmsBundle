<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Form\DataTransformer;

use Positibe\Bundle\MenuBundle\Model\ContentIdUtil;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ContentIdDataTransformer
 * @package Positibe\Bundle\CmsBundle\Form\DataTransformer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContentIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        try {
            return ContentIdUtil::getModelAndId($value)[1];
        } catch (\Exception $e) {
            return null;
        }

    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        //We use a eventListener to set the proper value FQN:id
        return $value;
    }

}