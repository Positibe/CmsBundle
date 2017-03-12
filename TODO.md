Definir todas las configuraciones del CMS en este bundle, sacar la antigua configuración del bundle cmf de Sylius,

El lunetics locale change locale se cambio para este bundle

Poner las adminsitraciones fuera de las traducciones de los contenidos. Si el sitio está en `en` poder administrarlo en español y hacer las traducciones en base a eso.

Mantener la funcionalidad de CustomControllerInformation

1. Pasar el controlador Generic para el Positibe ContentBundle
2. Eliminar el Compiler pass y ponerle directamente el tag doctrine.event_subscriber al servicio.