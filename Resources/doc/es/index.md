PositibeCoreBundle
=================

Este agrega funcionalidad de administración de CMS a los bundle de Positibe ORM Core.

Este bundle va a agregar funcionalidades a los bundle PositibeCmfRoutingExtraBundle, PositibeOrmMenuBundle, PositibeOrmSeoBundle, PositibeOrmMediaBundle, PositibeOrmBlockBundle y PositibeCmsBundle.

Sin embargo no existe dependencia directa con estos bundles, por lo que puede instalar cada uno de estos independientemente y este bundle se encarga de agregar las funcionalidades para cada uno de estos.

Funcionalidades que vienen independiente:
    * Configuración de recursos mediante SyliusResourceBundle.
    * Cambio de idiomas mediante LunaticsLocaleBundle
    * Habilitados los listener de GedmoDoctrineExtension con StofDoctrineExtensionBundle.
    * Algunos formularios útiles que vienen con SonataCoreBundle

Instalación
-----------

Puede instalar este bundle con composer agregando el paquete ``positibe/cmf-bundle`` a tu composer.json.

Este bundle te instalará un conjunto de librerías y otros bundle que deberás registrarlos en tu AppKernel::

    // app/appKernel.php

    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Lunetics\LocaleBundle\LuneticsLocaleBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new Positibe\Bundle\CoreBundle\PositibeCoreBundle(),

            //Este bundle debe ir despues de los bundles que crean recursos, pero antes del DoctrineBundle
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),

            //Este bundle siempre debe ir de último, ya debe tenerlo instalado así que solo muevelo al final
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        );

        // ...

        return $bundles;
    }


Configuración
-------------

La mayoría de las configuraciones de los bundles instalados están preconfigurados en el archivo `Resources/config/config.yml` del PositibeCMfBundle.

    # app/config/config.yml

        imports:
            - { resource: @PositibeCoreBundle/Resources/config/config.yml }

Además debe definir los `locales` del sistema:

    # app/config/parameters{.dist}.yml

    parameters:
        locale: es
        locales: [es, en , fr]

**Nota:** Esta configuración es la que posee por defecto el bundle por lo que no es necesario sobreescribirla

Para que funcione correctamente el idioma en su idioma debe estar habilitado el fallback locale en la coniguración de symfony:

    # app/config/config.yml

    framework:
        translator:      { fallback: "%locale%" } # Descomenta la linea si está comentada

Por último el bundle SyliusResourceBundle necesita tener habilitado la sobreescritura de parámetros del metodo http. Solo debes ir los controladores frontales app.php, app_dev.php y otros que posea y habilitarlos:
You also need to enable HTTP method parameter override, by calling the following method on the **Request** object.

    <?php
    // web/app{_dev|_test}.php

    use Symfony\Component\HttpFoundation\Request;

    Request::enableHttpMethodParameterOverride();

La extensión de traducciones de DoctrineExtension está habilitada, pero si quieres hacerlo sin tener una entidad de traducción para una clase en específico, necesita usar que entidad por defecto:

    # app/config/config.yml

    # ..
    # Doctrine Configuration
    doctrine:
        # ..
        orm:
            # ..
            mappings:
                translatable:
                    type: annotation
                    alias: Gedmo
                    prefix: Gedmo\Translatable\Entity
                    # make sure vendor library location is correct
                    dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"

Actualiza el esquema
--------------------

Debe actualizar el esquema para agregar las nuevas tablas mediante el comando `php app/console doctrine:schema:update --force`.


y listo ya puede usar las funcionalidades básicas que brinda este bundle.

Documentación
-------------

Ahora sigue con configurando recursos: en `@PositibeCoreBundle/Resources/doc/es/resource_management.md`.


