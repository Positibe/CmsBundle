PositibeCmsBundle
=================

This bundle provide a AbstractPage, Page and Category entity and integrate some other Positibe bundles to create a Page CMS representation.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/orm-content-bundle

This bundle set the required configuration of other Positibe Bundles for you like:
* PositibeMenuBundle
* PositibeMediaBundle
* PositibeCmfRoutingExtraBundle
* PositibeCmsBundle

But it's better take a look at those README.md file

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            // Sylius ResourceBundle
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            new Symfony\Cmf\Bundle\SeoBundle\CmfSeoBundle(),
            new Positibe\Bundle\MediaBundle\PositibeMediaBundle(),
            new Positibe\Bundle\CmfRoutingExtraBundle\PositibeCmfRoutingExtraBundle(),
            new Positibe\Bundle\CoreBundle\PositibeCoreBundle(),
            new Positibe\Bundle\MenuBundle\PositibeMenuBundle(),
            new Positibe\Bundle\CmsBundle\PositibeCmsBundle(),
            // All dependencies of all Positibe bundles
            new Positibe\Bundle\CmsBundle\PositibeCmsBundle(),

            // ...
        );
    }

Configuration
=============

Import all necessary configurations to your app/config/config.yml the basic configuration.
    # app/config/config.yml
    imports:
        - { resource: '@PositibeCoreBundle/Resources/config/config.yml'}
        - { resource: '@PositibeCmsBundle/Resources/config/config.yml' }
        - { resource: '@PositibeMediaBundle/Resources/config/config.yml'}
        - { resource: '@PositibeMenuBundle/Resources/config/config.yml'}
        - { resource: '@PositibeCmfRoutingExtraBundle/Resources/config/config.yml' }

**Caution:**: This bundle use the timestampable, sluggable, softdeleteable, translatable and sortable extension of GedmoDoctrineExtension. Be sure that you have the listeners for this extensions enable. You can also to use StofDoctrineExtensionBundle.
**Caution:**: If you have the parameter ``positibe.menu_node.class: Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode`` configured in your config.yml please erase it.

