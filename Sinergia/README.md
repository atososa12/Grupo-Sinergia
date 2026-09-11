<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Sinergia

5. Maquetación del proyecto

Cada negocio es un “tenant” dentro del sistema.

1) El dueño del negocio crea su comercio
Cuando un cliente del SaaS entra a /register o /registro-negocio, ese flujo no crea solo un usuario: crea dos cosas a la vez:

un registro en la tabla comercio
un usuario con rol dueño vinculado a ese comercio

Esto está en RegistroNegocioController.php.

La lógica es:

toma los datos del negocio
crea el Comercio
crea el User con:
nombre
apellido
email
contraseña
rol = dueño
id_comercio = el ID del comercio recién creado (numerado, por ejemplo, el primer comercio en la BD es 1, el segundo 2 y así. Habría que revisar  esa lógica)
hace login automático
redirige al dashboard

Entonces, el dueño queda “asociado” a su negocio mediante la relación id_comercio en User.php y Comercio.php.

2) ¿Dónde agregan sus productos y datos del comercio?
Después del registro, el dueño entra a /dashboard.

Ahí se muestran:

nombre del comercio
formulario para agregar productos
lista de productos
pedidos recibidos
posibilidad de cambiar estados del pedido

Todo eso se resuelve en DashboardController.php y la vista en index.blade.php.





Auth::user() devuelve el usuario logueado
$usuario->comercio trae el comercio del dueño
todos los productos creados se guardan con id_comercio = comercio actual
todos los pedidos también quedan ligados al mismo comercio
Es decir, el negocio no “vive” en una app global separada: vive en una fila de la tabla comercio y todas sus entidades relacionadas apuntan a ese id_comercio.

3) ¿Cuál es la vista final para el cliente común?
La vista pública del cliente es:

/comercio/{idComercio} (idcomercio es un número. Para el prototipo, debería ser el "username". Por ejemplo, si es lacuartamasa, la url debería ser sinergia.uy/comercio/lacuartamasa... Actualmente es sinergia.uy/comercio/1; sinergia.uy/comercio/2 y así. 

Eso lo maneja TiendaController.php y la vista pública está en show.blade.php.

Allí el cliente común ve:

nombre del negocio
dirección y contacto
catálogo de productos
carrito
formulario de pedido
checkout

Ese cliente no necesita estar logueado para comprar. El pedido se crea con id_usuario = null, y queda asociado al comercio mediante id_comercio.

4) ¿Cómo se registra el usuario final al negocio?
Actualmente, no se registra como “cliente del negocio”.

La implementación actual de compra es anónima:

el usuario final completa nombre, teléfono, dirección y hace el pedido
no hay login del cliente
no hay cuenta de cliente
no hay membresía al negocio

Para un SaaS con “clientes registrados”, se debe implementar: .

un usuario cliente separado del usuario dueño
una relación entre cliente y negocio
por ejemplo:
cliente puede registrarse con email/password
puede guardar dirección
puede ver historial de compras
puede tener favoritos, suscripciones, etc.
Resumen de la arquitectura actual


El sistema funciona así:

Un cliente del SaaS crea su negocio desde /registro-negocio
Se crea:
Comercio
Usuario dueño vinculado a ese comercio
El dueño entra a /dashboard
Ahí agrega productos y ve pedidos
El cliente final entra a /comercio/{idComercio}
Ve el catálogo y hace un pedido sin login
El pedido queda asociado al comercio y se procesa desde el dashboard del dueño

Lo que falta para el SaaS

cada comercio es un tenant
cada dueño puede tener varios comercios
cada cliente puede registrarse y comprar
cada negocio puede tener una base de clientes propia
se recomienda un modelo de “usuarios con roles” y otra tabla de clientes por negocio
Si quieres, en el próximo paso puedo dejarte la estructura exacta para esto:

dueño crea negocio
dueño crea productos
cliente se registra
cliente compra y guarda historial
el negocio ve sus clientes y pedidos
cada negocio queda totalmente aislado del resto
Si te parece, te armo esa versión pro del SaaS en este mismo proyecto.



