Debe configurar los parametros `locales` y `block_locations`.
Cache
-----

You can cache each block by configuration.

You can use some extra cache to store the proper key for each locale for example:

..code-block:: ninja

    {{ sonata_block_render({'type': 'positibe_cms.pages_by_category', 'settings': {'extra_cache_keys': {'locale': app.request.locale}}}, {'category': 'news'}) }}