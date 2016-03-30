# Restfood

## Requisitos del sistema
* PHP >= 5.6
* MySQL >= 5.5
* Servidor Web 

## Instalación
* Clonar el proyecto
* Instalar las dependencias vía composer.
* Base de datos:
    * La configuración de la base de datos se encuentra en el fichero "/src/app.php", está configurado para que utilice
    "restfood" como base de datos, usuario y contraseña.
    * Se puede generar el esquema de la base de datos a partir de la información de mapping de Doctrine mediante el 
    siguiente comando:
    ```
    php orm:schema-tool:create
    ```
    * Se incluye un dump de la base de datos con datos de prueba para facilitar el testeo en "/_MISC/restfood.sql". Se 
    puede importar facilmente con el siguiente comando:
    ```
    php dbal:import _MISC/restfood.sql
    ```

## Consideraciones de implementación

### Tecnologías y librerías:

* **Microframework [Silex](http://silex.sensiolabs.org/):** es el hermano pequeño de Symfony, construído con sus propios componentes. Lo he elegido porque
es muy liviano a la vez que potente, permitiéndo añadir componentes Symfony según la necesidad, aparte claro está, de 
cuantas librerías de terceros como queramos. Otra de los motivos que me han llevado a elegirlo es que es ideal para el 
desarrollo de APIs REST, puesto que permite definir controladores (acciones o endpoints) de una forma muy sencilla, incluyendo
funcionalidades para el contro de verbos http, generación de respuestas, etc. Además, incluye un contenedor de inyección 
de dependencias muy sencillo y potente.
* **[Doctrine ORM](http://www.doctrine-project.org/projects/orm.html)**: probablemente el ORM para PHP más extendido. Lo 
he elegido porque el hecho de trabajar con un ORM nos permite tener versionada la base de datos, también porque nos permite
trabajar directamente con objetos, abstrayéndonos de la estructura de datos subyacente y dotando al código de una mayor
carga semántica.
* **[webmozart/json](https://github.com/webmozart/json)**: según su propia definición, un codificador/decodificador de JSON
robusto, normaliza el comportamiento entre las diferentes versiones de PHP, arroja excepciones semánticas y permite 
validación del esquema.
* **[ramsey/uuid](https://github.com/ramsey/uuid)**: poco que decir, una librería para la generación de identificadores 
universales de recursos.
* **[PHPUnit](https://phpunit.de/)**: como framework de testing.
* **[Mockery](https://github.com/padraic/mockery)**: framework de Mocking en PHP. Me gusta porque facilita enormemente la 
creación de mocks, además contribuyo con el proyecto :-).
 

### Decisiones tomadas y detalles de diseño

* En cuanto a la arquitectura de la aplicación, no he seguido los principios de "clean architecture" porque nunca he 
trabajado antes bajo ese patrón de diseño y he de decir que no lo conozco a fondo. No obstante, he intentado que mi código
esté completamente desacoplado del framework, de hecho, creo que en gran medida lo he conseguido, puesto que el framework
en sí únicamente se encarga de la definición de urls y control del los verbos http para cada una (a parte por supuesto, 
de gestionar todo el flujo entre peticiones y respuestas).

* También se puede decir que existe un desacoplamiento entre las diferentes capas de código de aplicación:

    * Bajo el namespace "Restfood\RESTFul" se encuentra toda la lógica que tiene que ver con el protocolo HTTP.

    * El resto de contenido dentro del namespace "Restfood" contiene la lógica directamente relacionada con el dominio 
    de la aplicación, que es independiente del protocolo con el que se acceda a ella.

    * Las entidades (los objetos que modelan los datos del dominio), se encuentran en "Restfood\Entitiy". Pese a que utilizamos
    Doctrine ORM y Mysql para la persistencia de los datos, la aplicación están desacopladas de dichas herramientas, ya
    que las entidades implementan unas determinadas interfaces que son en las que confían el resto de objetos de la 
    aplicación. De este modo, podríamos definir otras entidades que trabajen con otra capa de persistencia totalmente 
    distinta (o sin persistencia), y la aplicación funcionaría perfectamente. También comentar los datos que contienen la
    información de mapping que utiliza Doctrine están ubicados en fichero yml en el directorio "/config/doctrine".
    
* En general, he procurado respetar al máximo los principios SOLID, haciéndo mucho incapie el en principio de inversión 
de dependencias (como se puede observar por la gran cantidad de interfaces) y en el principio de responsabilidad única.
También he tratado de generar un código muy legible, que no necesite documentación alguna para entenderlo (aunque he añadido
documentación de phpdoc a los método porque es una costumbre que tengo y, me gusta hacerlo para ayudar al ide con el 
autocompletado y la inferencia de tipos).

* Hay algunas decisiones de diseño concretas que me gustaría comentar:

    * Durante el desarrollo me dí cuenta de que el código de las tres entidades/recursos principales de la aplicación, 
    así como las operaciones a realizar sobre ellas eran practicamente idénticas. De este modo, para su gestión he creado
    una única clase "Restfood\Manager\ResourceManager", que permite gestionarlas todas pasándole cierta configuración 
    necesaria en el momento de la instanciacion. Luego, he definido un servicio para cada recurso. He optado por esta opción
    porque la otra opción que se me venía a la cabeza pasaba por usar herencia, pero me parecía tan sencillo que creo que
    esta opción daba lugar a un código más limpio y, de paso, me quitaba de en medio los problemas que acarrea la herencia.
    Probablemente exista alguna solución mejor que cualquiera que las dos que planteo, basada seguramente en algún diseño 
    mediante composición y delegación, pero dado el tiempo no podía detenerme mucho en ello.
    
    * Al hilo de lo anterior, ocurre lo mismo con los "endpoints" que gestionan las operaciones sobre estos recursos. En
    este punto sí que he optado por una solución basada en herencia, con una clase abstracta y tres clases concretas que 
    la extienden. Lo he hecho un poco por mostrar las dos opciones dentro del código, pero en base a la experiencia obtenida,
    me quedaría con la primera.
    
    * Puede que os extrañen los nombre utilizados en algunos casos, os los explico:   
        * "Identifier" en lugar de "Id": esto lo hago porque id tiene la "semántica secuestrada" y lleva a pensar que lo
        que estamos obteniendo es el identificador (clave primaria) de la entidad. Utilizando identifier quiero dejar 
        patente que ese es el campo que se utiliza para identificar la entidad de forma única dentro de la aplicación, y 
        que no necesariamente tiene que ser la clave primaria en la base de datos.        
        * "ObtainXXX" en lugar de "GetXXX", la misma explicación que en el caso anterior. Get lleva a pensar que estamos 
        obteniendo el valor de un campo del objeto con ese nombre, y no tiene por qué ser así, puede que lo que estemos 
        obteniendo con obtain sea un valor calculado.
    
* En cuanto a validación, los datos son validados en las diferentes operaciones y se arrojan errores con una semántica
adecuada indicando la causa del problema (campo único duplicado, recurso inexistente, campo requerido vacío, etc).

### Testing

* En cuanto a testing, he testeado la mayor parte del código generado, según el último reporte obtenido en el momento de 
generar este documento, el namespace "Restfood" tiene un 69% de cobertura de tests, aunque este dato no arroja demasiada
verdad porque hay objetos, como las entidades, que no he testeado porque considero que no tiene sentido, y que aporta 
más trabajo de mantenimiento que valor en sí. Para la genearción de dobles de tests he utilizado el framework mockery.

### Performance y optimización

No he prestado demasiada atención a este aspecto por falta de tiempo, y porque considero que no era el objetivo principal
de la prueba. De este modo, probablemente se pueda optimizar el número de consultas realizadas a la base de datos, puesto
ahora es doctrine quien se encarga de realizarlas cuando se necesitan (mediante lazy loading).
    
### URLs

Comentar que en las urls he utilizado los UUID de los recursos para identificarlos y acceder a ellos, creo que es mejor
solución que utilizar, por ejemplo, un id autoincremental, especialmente por temas de seguridad. Lo ideal hubiese sido
codificar de algún modo este valor para no exponer directamente los datos de nuestra aplicación, pero considero que eso
se escapaba del alcance de la prueba.

#### Alérgenos

##### **CREAR:** /allergens
* Método: **POST**
* Payload: Json con el nombre del recurso `{"name": "gluten"}`
* Código Respuesta: **201**
* Cuerpo Respuesta: JSON con la representación del recurso generado.
* Adicional: Cabecera http **location** con enlace al recurso creado.

##### **EDITAR:** /allergens/{identificador}
* Método: **PUT**
* Payload: Json con los campos y valores a modificar `{"id":"bfb36960-df46-480a-8ddc-59f012dfd9e8","name":"lactosa"}`
* Código Respuesta: **200**
* Cuerpo Respuesta: JSON con la representación del recurso generado.
* Consideraciones: 
    * Se genera un error si se trata de modificar el id del recurso.
    * Probablemente lo más correcto hubiése sido utilizar el verbo PATCH en lugar de PUT, pero ya no me da tiempo a 
    cambiarlo.

##### **BORRAR:** /allergens/{identificador}
* Método: **DELETE**
* Payload: Vacío
* Código Respuesta: **204**
* Cuerpo Respuesta: Vacío
* Consideraciones: Se genera un error 404 si se trata de eliminar un recurso que no existe.

##### **LISTAR:** /allergens
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de alérgenos en formato JSON
* Consideraciones: No he implementado paginación ni filtros por falta de tiempo.

##### **MOSTRAR:** /allergens/{identificador}
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Representación del recurso en formato JSON
* Consideraciones: Se genera error 404 si no existe el recurso    

#### LISTADO DE PLATOS QUE CONTIENEN UN ALÉRGENO: '/allergens/{identifier}/dishes'
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de platos en formato JSON
* Consideraciones: Se genera error 404 si no existe el alérgeno

#### Ingredientes

Mismas consideraciones que en el caso anterior, omito información por abreviar.

##### **CREAR:** /ingredients
* Método: **POST**
* Payload: Json con el nombre del recurso `{"name": "pollo"}`
* Código Respuesta: **201**
* Cuerpo Respuesta: JSON con la representación del recurso generado.
* Adicional: Cabecera http **location** con enlace al recurso creado.

##### **EDITAR:** /ingredients/{identificador}
* Método: **PUT**
* Payload: Json con los campos y valores a modificar `{"id":"bfb36960-df46-480a-8ddc-59f012dfd9e8","name":"pollo"}`
* Código Respuesta: **200**
* Cuerpo Respuesta: JSON con la representación del recurso generado.

##### **BORRAR:** /ingredients/{identificador}
* Método: **DELETE**
* Payload: Vacío
* Código Respuesta: **204**
* Cuerpo Respuesta: Vacío

##### **LISTAR:** /ingredients
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de recursos en formato JSON

##### **MOSTRAR:** /ingredients/{identificador}
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Representación del recurso en formato JSON    

##### **LISTADO DE ALÉRGENOS DE UN INGREDIENTE:** /ingredients/{identifier}/allergens
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de recursos en formato JSON    
* Consideraciones: Se genera error 404 si no existe el recurso

##### **ESTABLECER ALÉRGENOS DE UN INGREDIENTE:** /ingredients/{identifier}/allergens
* Método: **PUT**
* Payload: Array de identificadores de alérgenos codificado en JSON: `["bfb36960-df46-480a-8ddc-59f012dfd9e8", "bfb36960-df46-480a-8ddc-59f012dfddsfsdf"]`
* Código Respuesta: **200**
* Cuerpo Respuesta: Array con los recursos establecidos en formato JSON    
* Consideraciones:
 * Por simplicidad y falta de tiempo, no he implementado las acciones de añadir y borrar un alérgeno a un ingrediente, 
 de este modo, esta acción puede ser usada para estos casos, enviando el listado de alérgenos más el que se deseé añadir
 o menos el que se quiera borrar.
 * Si alguno de los ids de alérgenos enviados en la petición no existe, la aplicación lo ignorará silenciosamente.

#### Platos

Mismas consideraciones que en el caso anterior, omito información por abreviar.

##### **CREAR:** /dishes
* Método: **POST**
* Payload: Json con el nombre del recurso `{"name": "pollo a la milanesa"}`
* Código Respuesta: **201**
* Cuerpo Respuesta: JSON con la representación del recurso generado.
* Adicional: Cabecera http **location** con enlace al recurso creado.

##### **EDITAR:** /dishes/{identificador}
* Método: **PUT**
* Payload: Json con los campos y valores a modificar `{"id":"bfb36960-df46-480a-8ddc-59f012dfd9e8","name":"pollo al curry"}`
* Código Respuesta: **200**
* Cuerpo Respuesta: JSON con la representación del recurso.

##### **BORRAR:** /dishes/{identificador}
* Método: **DELETE**
* Payload: Vacío
* Código Respuesta: **204**
* Cuerpo Respuesta: Vacío

##### **LISTAR:** /dishes
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de recursos en formato JSON

##### **MOSTRAR:** /dishes/{identificador}
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Representación del recurso en formato JSON
* Consideraciones: Si se tuviese más conocimiento del uso de la aplicación, en la respuesta se podría enviar información
adicional, como el listado de alérgenos del plato, para reducir el número de peticiones a la API.

##### **LISTADO DE INGREDIENTES DE UN PLATO:** /dishes/{identifier}/ingredients
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de recursos en formato JSON    
* Consideraciones: Se genera error 404 si no existe el recurso

##### **ESTABLECER LOS INGREDIENTES DE UN PLATO:** /dishes/{identifier}/ingredients
* Método: **PUT**
* Payload: Array de identificadores de ingredientes codificado en JSON: `["bfb36960-df46-480a-8ddc-59f012dfd9e8", "bfb36960-df46-480a-8ddc-59f012dfddsfsdf"]`
* Código Respuesta: **200**
* Cuerpo Respuesta: Array con los recursos establecidos en formato JSON    
* Consideraciones: 
    * Por simplicidad y falta de tiempo, no he implementado las acciones de añadir y borrar un ingrediente a un plato, 
     de este modo, esta acción puede ser usada para estos casos, enviando el listado de ingredientes más el que se deseé 
     añadir o menos el que se quiera borrar.
    * Si alguno de los ids de ingredientes enviados en la petición no existe, la aplicación lo ignorará silenciosamente.   

##### **LISTADO DE ALÉRGENOS DE UN PLATO:** /dishes/{identifier}/allergens
* Método: **GET**
* Payload: Vacío
* Código Respuesta: **200**
* Cuerpo Respuesta: Array de recursos en formato JSON    
* Consideraciones: Se genera error 404 si no existe el recurso

## Bonus

Finalmente no me ha dado tiempo a implementar esta parte, pero tengo claro cómo lo haría:

* Crearía una entidad **CustomDish** que estaría relacionada 1:N con la entidad Dish (un plato puede ser customizado N
veces, y un plato customizado está relacionado con un solo plato). Esta entidad daría lugar a una tabla con el mismo 
en la base de datos con los campos **id, timestamp, dish_id**.
* Crearía dos relaciones de tipo M:N en la entidad **CustomDish** con respecto a la entidad **Ingredient**: **AddedIngredients** y
** RemovedIngredients**. De este modo, para cada plato customizado almacenaría los ingredientes añadidos y los eliminados.
De este modo se generaría dos tablas nuevas en la base de datos:
    * **CustomDishAddedIngredient (custom_dish_id, ingredient_id)** 
    * **CustomDishRemovedIngredient (custom_dish_id, ingredient_id)**
* Con las entidades y estructura de datos indicada ya podríamos llevar el registro de los platos modificados, fecha de 
creación y su configuración de ingredientes.
* Para la gestión desde la api crearía las siguientes URLs:
    * **CREAR PLATO CUSTOMIZADO:** /custom_dish
        * Método: **POST**
        * Payload: Documento JSON con el id del plato a modificar, array de ids de ingredientes a añadir, array de ids de 
        ingredientes a eliminar:
        ```
        {
            "dish": "bfb36960-df46-480a-8ddc-59f012dfd9e8",
            "add": [
                "bfb36960-df46-480a-8ddc-59f012dfd9e8",
                "bfb36960-df46-480a-8ddc-59fd32d3dl2k"
            ],
            "remove": [
                "bfb36960-df46-480a-8ddc-59f01e23e883",
                "bfb36960-df46-480a-8ddc-59f049mf09er",
                "bfb36960-df46-480a-8ddc-59f0121e3je9"
            ]
        }
        ```    
        * Código Respuesta: **201**
        * Cuerpo Respuesta: Representación JSON del plato modificado    
        * Adicional: Cabecera location con el enlace a la url para ver el detalle del plato modificado
        
    * **LISTAR PLATOS CUSTOMIZADOS:** /custom_dish
        * Método: **GET**
        * Payload: Vacío            
        * Código Respuesta: **200**
        * Cuerpo Respuesta: Array de platos modificados en formato json. En este listado probablemente no incluiría el 
        listado de ingredientes de cada plato.
        
    * **VER PLATO CUSTOMIZADO:** /custom_dish/{identificador}
        * Método: **GET**
        * Payload: Vacío            
        * Código Respuesta: **200**
        * Cuerpo Respuesta: Representación JSON del plato modificado. Sólo incluiría el nombre del plato fecha e ingredientes
        que contiene, nada de diferenciar entre añadidos y eliminados.
        
    * **ELIMINAR PLATO CUSTOMIZADO:** /custom_dish/{identificador}
        * Método: **DELETE**
        * Payload: Vacío            
        * Código Respuesta: **204**
        * Cuerpo Respuesta: Vacío
        
    * **EDITAR PLATO CUSTOMIZADO:** /custom_dish/{identificador}
        * Método: **PUT**
        * Payload: Documento JSON con el id del plato a modificar, array de ids de ingredientes a añadir, array de ids de 
        ingredientes a eliminar:
        ```
        {
            "dish": "bfb36960-df46-480a-8ddc-59f012dfd9e8",
            "add": [
                "bfb36960-df46-480a-8ddc-59f012dfd9e8",
                "bfb36960-df46-480a-8ddc-59fd32d3dl2k"
            ],
            "remove": [
                "bfb36960-df46-480a-8ddc-59f01e23e883",
                "bfb36960-df46-480a-8ddc-59f049mf09er",
                "bfb36960-df46-480a-8ddc-59f0121e3je9"
            ]
        }
        ```            
        * Código Respuesta: **200**
        * Cuerpo Respuesta: Representación JSON del plato customizado resultante
        * Consideraciones: 
            * La información enviada en el payload reemplazará a la existente del recurso
            * Se generara error si el id no coincide.