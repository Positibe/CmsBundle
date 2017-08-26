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

use Doctrine\ORM\EntityRepository;
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
        $contentClass = $request->get('contentClass');
        $data = null;
        if ($path = $request->get('selectedByPath')) {
            try {
                $route = $this->get('router')->match(str_replace(['/app.php', '/app_dev.php'], '', $path));
                if (isset($route['_content_id'])) {
                    list($class, $id) = explode(':', $route['_content_id']);
                    $data = $this->get('doctrine.orm.entity_manager')->getRepository($class)->find($id);
                }
            } catch (\Exception $e) {
            }
        } elseif ($id = $request->get('selectedById')) {
            $data = $this->get('doctrine.orm.entity_manager')->getRepository($contentClass)->find($id);
        }

        $queryBuilderCallback = null;

        $metadata = $this->get('doctrine.orm.entity_manager')->getClassMetadata($contentClass);
        if ($metadata->hasField('host')) {
            $host = $this->get('session')->get('active_website');
            $queryBuilderCallback = function (EntityRepository $er) use ($host) {
                return $er->createQueryBuilder('o')->where('o.host = :host')->setParameter(
                    'host',
                    $host
                );
            };
        }
        $form = $this->createForm(
            'entity',
            $data,
            array(
                'attr' => array(
                    'id' => 'positibe_menu_node_content',
                    'name' => 'positibe_menu_node[content]',
                    'class' => 'chosen-select',
                ),
                'class' => $contentClass,
                'required' => false,
                'label' => 'menu_node.form.content_label',
                'placeholder' => 'chosen.form.select_option',
                'query_builder' => $queryBuilderCallback,
            )
        );

        return $this->render(
            'PositibeMenuBundle::_form_widget.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
} 