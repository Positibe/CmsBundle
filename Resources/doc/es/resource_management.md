Resources management
====================

Por defecto el bundle no incluye gestión de los recursos debido a que esta puede hacerse de diversas maneras. Pero
incluye soporte opcional para hacerlo mediante el SyliusResourceBundle del proyecto Sylius `sylius/resource-bundle`.

Introducción
------------

Los recursos son simplemente modelos de tu aplicación. La gestión de cada recurso permite de forma sencilla
interactuar con estos sin importar si son entidades o documentos. El bundle ofrece varios servicios que van a
permitir de forma abstracta trabajar con cada una desde cualquier lugar de la aplicación y facilita las pruebas.

Teniendo como ejemplo un entidad Post configurada como un recurso mediante app.post,
poniendo los siguientes comandos en la consola vemos los servicios y los parámetros configurados listos para usar:

    $ php app/console container:debug | grep my_entity
    app.controller.post              container      Sylius\Bundle\ResourceBundle\Controller\ResourceController
    app.form.type.post               container      AppBundle\Form\Type\PostType
    app.manager.post                 n/a            alias for doctrine.orm.default_entity_manager
    app.repository.post              container      Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository
    //...

    $ php app/console container:debug --parameters | grep my_entity
    app.config.classes                   {...}
    app.controller.post.class               Sylius\Bundle\ResourceBundle\Controller\ResourceController
    app.form.type.post.class                AppBundle\Form\Type\PostType
    app.model.post.class                    AppBundle\Entity\Post
    app.repository.post.class               AppBundle\Entity\PostRepository
    app.validation_group.my_entity          ["my_app"]
    app_post.driver                         doctrine/orm
    app_post.driver.doctrine/orm            true
    app_post.object_manager                 default

Con estos servicios puedes ir añadiendo funcionalidades a tu sistema sin que tengas que trabajar directo con las clases.

Instalación
-----------

Puede instalar este bundle con composer agregando el paquete ``sylius/recources-bundle`` a tu composer.json.

Este bundle te instalará un conjunto de librerías y otros bundle que deberás registrarlos en tu AppKernel::

    <?php

    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            //... Todos los bundles que contienen recursos
            new AppBundle\AppBundle(),
            new Positibe\Bundle\CmfRoutingExtraBundle\PositibeCmfRoutingExtraBundle(),
            new Positibe\Bundle\OrmMenuBundle\PositibeOrmMenuBundle(),
            new Positibe\Bundle\OrmBlockBundle\PositibeOrmBlockBundle(),
            new Positibe\Bundle\CoreBundle\PositibeCoreBundle(),
            new Positibe\Bundle\CmsBundle\PositibeCmsBundle(),
            //.. y muchos más

            //Luego agregue estps
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
        );
    }

**Importante:** Es necesario que los bundles propios o que contengan recursos se registren primero que el el
SyliusResourcesBundle.

Por último necesita agregar unas lineas al archivo app.php y al app_dev.php (Igual para otros entornos que posea):

    // web/app{_dev}.php
    use Symfony\Component\HttpFoundation\Request;

    //...
    require_once __DIR__.'/../app/AppKernel.php';

    $kernel = new AppKernel('dev', true);
    $kernel->loadClassCache();

    //Esta linea es la que debe agregar
    Request::enableHttpMethodParameterOverride();

    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    //..

Y listo ya puedes configurar tus propios recursos.

Configurando los recursos del PositibeCoreBundle
-----------------------------------------------

Los recursos ya vienes con una configuración por defecto que puede importar o copiar:

    # app/config/config.yml

    imports:
        # ....
        # - { resource: @PositibeCoreBundle/Resources/config/config.yml }
        - { resource: @PositibeCoreBundle/Resources/config/sylius_crud.yml }

Con esta configuración tiene acceso pleno a:

MenuNode:
* entity: Positibe\Bundle\CmsBundle\Entity\MenuNode
* positibe.controller.menu: Sylius\Bundle\ResourceBundle\Controller\ResourceController
* positibe.repository.menu: Positibe\Bundle\CoreBundle\Repository\MenuNodeRepository
* positibe.manager.menu: ~

Page:
* entity: Positibe\Bundle\CmsBundle\Entity\Page
* positibe.controller.menu: Positibe\Bundle\CoreBundle\Controller\TranslatableController
* positibe.repository.menu: Positibe\Bundle\CoreBundle\Repository\PageRepository
* positibe.manager.menu: ~

entre otras..

Ya existen configuradas las rutas para realizar el crud de estas entidades, puede agregar de una en una o todas a la
vez:

    # app/config/routing.yml

    # ...
    _positibe_cmf:
        resource: "@PositibeCoreBundle/Resources/config/routing.yml"
        prefix: /dashboard

Por defecto las plantillas
