Idiomas
=======

Para el trabajo completo con el idioma recomendamos el uso de `"lunetics/locale-bundle"`.

Agregar al routing principal el locale_routing.yml bajo un acceso libre:

    # app/config/routing.yml

    locale:
        resource: "@PositibeCoreBundle/Resources/config/locale_routing.yml"
        prefix:   /

En la plantilla usar:

    # {*}.html.twig

    {{ positibe_locale_switcher(app.request.get('routeDocument').name| default(null)) }}
    <!-- o -->
    {{ positibe_locale_switcher(app.request.get('_route')| default(null)) }}
