PositibeCmsBundle
=================

This bundle provide a AbstractPage, Page and Category entity and integrate some other Positibe bundles to create a Page CMS representation.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/orm-content-bundle

You must see the configuration of:
* PositibeOrmSeoBundle
* PositibeOrmMenuBundle
* PositibeCmsBundle
* PositibeOrmMediaBundle
* PositibeCmfRoutingExtraBundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            // All dependencies of all Positibe bundles
            new Positibe\Bundle\CmsBundle\PositibeCmsBundle(),

            // ...
        );
    }

Configuration
-------------

Import all necessary configurations to your app/config/config.yml the basic configuration.
    # app/config/config.yml
    imports:
        - { resource: @PositibeCmsBundle/Resources/config/config.yml }

**Caution:**: This bundle use the timestampable, sluggable, softdeleteable, translatable and sortable extension of GedmoDoctrineExtension. Be sure that you have the listeners for this extensions enable. You can also to use StofDoctrineExtensionBundle.

Available Blocks
----------------

Pages by category:
~~~~~~~~~~~~~~~~~~

This display 3 pages from ``services`` category like normal list:

    {{ sonata_block_render({'type': 'positibe_cms.pages_by_category'}, {'category': 'services'}) }}

If you want a grid layer displaying use a custom template:

    {{ sonata_block_render({'type': 'positibe_cms.pages_by_category'}, {'category': 'services', 'template':'PositibeCmsBundle:Block:grid_contents.html.twig'}) }}

Featured page
~~~~~~~~~~~~~

This display a featured page:

    {{ sonata_block_render({'type': 'positibe_cms.featured_page'}) }}

You can use the ``data_class`` option to use a custom class ``Positibe\Bundle\CmsBundle\Entity\Page`` is used by default:

    {{ sonata_block_render({'type': 'positibe_cms.featured_page'}, {'data_class': 'Positibe\Bundle\CmsBundle\Entity\Category'}) }}
