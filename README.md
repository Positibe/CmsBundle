PositibeOrmContentBundle
========================

This bundle provide a Page entity and integrate some other Positibe bundles to create a Page CMS representation.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/orm-content-bundle

You must see the configuration of:
* PositibeOrmSeoBundle
* PositibeOrmMenuBundle
* PositibeOrmBlockBundle
* PositibeOrmMediaBundle
* PositibeOrmRoutingBundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            // All dependencies of all Positibe bundles
            new Positibe\Bundle\OrmContentBundle\PositibeOrmContentBundle(),

            // ...
        );
    }

Configuration
=============

Import all necessary configurations to your app/config/config.yml the basic configuration.
    # app/config/config.yml
    imports:
        - { resource: @PositibeOrmContentBundle/Resources/config/config.yml }

**Caution:**: This bundle use the timestampable, sluggable, softdeleteable, translatable and sortable extension of GedmoDoctrineExtension. Be sure that you have the listeners for this extensions enable. You can also to use StofDoctrineExtensionBundle.


