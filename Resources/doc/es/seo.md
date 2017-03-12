Configuración del Seo
=====================

Configuración base
------------------

Lo primero es declarar los valores por defecto del Seo del sitio :

    # app/config/config.yml

    sonata_seo:
        page:
            title: Mi super titulo
            metas:
                name:
                    description: Mi sitio es el super sitio
                    keywords: sitio, web, super
                    viewport: width=device-width, initial-scale=1
            http-equiv:
                'Content-Type':         text/html; charset=utf-8
                'X-Ua-Compatible':      IE=edge

Y luego agregar las funciones necesarias en las plantillas twig:

    # app/Resources/views/base.html.tig

    <!DOCTYPE html>
    <html {{ sonata_seo_html_attributes() }}>
    <head {{ sonata_seo_head_attributes() }}>
        {{ sonata_seo_title() }}
        {{ sonata_seo_metadatas() }}
        {{ sonata_seo_link_canonical() }}
        {{ sonata_seo_lang_alternates() }}
        <!--...-->

En la documentación del SonataSeoBundle aparece más opciones de configuración básica.

Seo por contenido
-----------------


Internacionalización
--------------------

Para internacionalizar el Seo debe instalar el PositibeCoreSeoBundle que brinda el mismo objeto SeoMetadata pero con
internacionalización mediante Gedmo Doctrine Extension.

**Importante:** Recuerde registrar primero el CoreSeoBundle y después el PositibeCoreSeoBundle para poder sobreescribir
 el mapeo de la entidad.

