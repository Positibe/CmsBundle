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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class MenuController
 * @package Positibe\Bundle\CmsBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuController extends Controller
{
    public function getContentFormAction(Request $request)
    {
        $class = $request->get('contentClass');
        $form = $this->createForm(
            'entity',
            null,
            array(
                'attr' => array(
                    'id' =>'positibe_menu_node_content',
                    'name' =>'positibe_menu_node[content]',
                    'class' => 'chosen-select'
                ),
                'class' => $class,
                'required' => false,
                'label'=> 'menu_node.form.content_label',
                'placeholder' => 'chosen.form.select_option'
            )
        );

        return $this->render(
            'PositibeMenuBundle::_form_widget.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
} 