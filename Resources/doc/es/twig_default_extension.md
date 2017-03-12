Twig extension
==============

Esta extensión es necesaria para funciones usadas por estos bundles como ``truncate`` y ``localizeddate``.

**Importante:** Es posible que ya tenga instalada y configurada estas extensiones por lo que los siguientes pasos son
 opcionales.

Instalación
-----------

Instala o asegurate de tener instaladas las extension de twig en tu composor.json ``twig/extension``.

Configuración
-------------

Agrega a tu config.yml la configuración:

    # app/config/config.yml

    imports:
        - { resource: @PositibeCoreBundle/Resources/config/services/twig_extension_services.yml }

**Nota:** La configuración anterior solo habilita el uso de las extensiones ``truncate``,
 ``wordwrap`` y ``localizeddate``. Pero una vez instalada puede agregar servicios para otras extensiones. Ver
 documentación del propio bundle para esto.