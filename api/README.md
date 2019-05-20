# Slim Framework 3 Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can run these commands 

    cd [my-app-name]
    php composer.phar start o composer start

Run this command in the application directory to run the test suite

    php composer.phar test o composer test

That's it! Now go build something cool.

### Dependencias

Para que el proyecto funcione se debe tener en nuestro entorno:

* php curl

#### Campos de bd y data retornada a almacenar

Listado por tablas de Wordpress, se está tomando como guía la data del post con id **1487**:
**wp_post**

* **id:** automático
* **post_author:** 1
* **post_date:** realEstates.date
* **post_date_gtm:** realEstates.date
* **post_content:** realEstates.description
* **post_title:** realEstates.description
* **post_status:** publish
* **comment_status:** closed
* **ping_status:** closed
* **post_name:** realEstates.description (reemplazando blanks con "-")
* **post_parent:** 0
* **post_type:** property
* **post_mime_type:** null

**imágenes**
* **id:** automático
* **post_author:** 1
* **post_date:** realEstates.date
* **post_date_gtm:** realEstates.date
* **post_content:** null
* **post_title:** realEstates.description
* **post_status:** inherit
* **comment_status:** open
* **ping_status:** closed
* **post_name:** realEstates.description (reemplazando blanks con "-")
* **post_parent:** 0
* **post_type:** attachment
* **post_mime_type:** image/png
* **guid:** realEstates.multimedias[x].url


**wp_postmeta**

**wp_postmeta (precio)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_price
* **meta_value:** realEstates.transactions[0].value[0]

**wp_postmeta (dimensiones)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_size
* **meta_value:** realEstates.features[0].value[x] (donde el key sea "surface")

**wp_postmeta (unidad de medida)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_size_postfix
* **meta_value:** m2

**wp_postmeta (habitaciones)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_bedrooms
* **meta_value:** realEstates.features[0].value[x] (donde el key sea "rooms")

**wp_postmeta (baños)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_bathrooms
* **meta_value:** realEstates.features[0].value[x] (donde el key sea "bathrooms")

**wp_postmeta (mapa)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_map
* **meta_value:** 0

**wp_postmeta (dirección)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_address
* **meta_value:** realEstates.address.ubication + " " + realEstates.address.location.country + realEstates.address.location.level(1 al 8)+ realEstates.address.location.upperlevel

**wp_postmeta (coordenadas)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_location
* **meta_value:** realEstates.address.coordinates.latitude + "," + realEstates.address.coordinates.longitude

**wp_postmeta (imágenes)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_property_images
* **meta_value:** id del post de tipo attachment 

**wp_postmeta (agente)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_agent_display_option
* **meta_value:** none

**wp_postmeta (agente)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_agents
* **meta_value:** -1

**wp_postmeta (featured)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_featured
* **meta_value:** 0

**wp_postmeta (slider)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** REAL_HOMES_add_in_slider
* **meta_value:** no

**wp_postmeta (seo1)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** _yoast_wpseo_primary_property-type
* **meta_value:** 57

**wp_postmeta (seo2)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** _yoast_wpseo_primary_property-city
* **meta_value:** 54

**wp_postmeta (seo3)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** _yoast_wpseo_primary_property-status
* **meta_value:** 55

**wp_postmeta (seo4)**
* **meta_id:** automático
* **post_id:** id del post creado
* **meta_key:** _yoast_wpseo_metadesc
* **meta_value:** ✅ Estudio Colombia ✅ empresa líder en venta y alquiler de INMUEBLES en San Lorenzo-Hortaleza con transparencia y confianza.