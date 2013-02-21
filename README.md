Sistema De Información Automatizado Para El Registro Y Control De Pacientes En El Consultorio De Barrio Adentro
========================

Sistema de información automatizado para el registro y control de pacientes que asisten al consultorio 
de barrio adentro

1) Pasos de Instalacion
----------------------------------

Antes de instalar la aplicacion debes tener configurado adecuadamente tu servidor.

### Descargue una version de Symfony2 (Opcional)

   Para asegurarse que su servidor corre adecuamente descarge e instale [Symfony2] [1]

### Use Composer (*recomendado*)

Symfony2 utiliza [Composer] [2] para la gestión de sus dependencias.

Si usted no tiene Composer aún , descargar siguiendo las instrucciones de
http://getcomposer.org/ o simplemente ejecutar el siguiente comando:

    curl -s https://getcomposer.org/installer | php

Composer instala Symfony y todas sus dependencias
`path/to/install` directorio.

2) Descargando una version
-------------------------------------

Antes de seguir debes haber comprobado que Symfony2 funciona correctamente en tu Servidor

### Descargar una version

Lo descomprimes en tu www o htdocs y actualiza el archivo "parameters.yml":

   parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: null
    database_name: consultorioBA
    database_user: root
    database_password: null
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    locale: es
    secret: xxxxxxxxxxxxxxx
    database_path: null

Luego ejecutas el comando desde una terminal para descargarte todas las librerias necesarias:

    php composer.phar install

Creamos la base de datos:

    php app/console doctrine:database:create

Creamos la estructura de las tablas:

   php app/console doctrine:schema:create

Cargamos los fixtures de la aplicacion:

   php app/console doctrine:fixtures:load

Si luego de ejecutar algun paso tienes alguna advertencia, debes resolverla antes de continuar.

3) Abriendo La Aplicacion Por Primera Vez
--------------------------------

Debes acceder a traves de tu navegador a la url:

   localhost/{app}/web/app.php/

Al ingresar a la URL inmediatamente te pedira un usuario y contraseña (estos se cargaron con los fixtures):

   Usuario: admin
   Contraseña: adminadmin

Si el sistema te da la bienvenida en la pagina de inicio, significa que todo ha salido correctamente.

Enjoy!

[1]:  http://symfony.com/doc/2.1/book/installation.html
[2]:  http://getcomposer.org/