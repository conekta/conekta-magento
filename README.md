magento_plugin
==============

#Instalación

  * Copiar el folder app y pegarlo en donde tienes instalado magento para que se una con el folder app de Magento.
  * En el admin de Magento deberás ir a System->Cache Management. Seleccionarás todos los Cache Types y los deshabilitarás. También tienes que vaciar el cache de Magento haciendo clic en "Flush Magento Cache" y "Flush Cache Storage". Esto es para empezar a probar el plugin.
  * En la sección de System->Configuration, hay una sección de Payment Methods en la barra de navegación izquierda, tienes que dar clic ahí. En el listado de métodos de pago que se te muestra, tiene que aparecer "Pago con Tarjeta de Débito / Crédito", "Pago con Oxxo", "Pago con Transferencia Bancaria". Si no aparecen estos métodos de pago, revisa que magento tenga permisos suficientes para accesar los folders que uniste con los de Magento.
  * En cada uno de los métodos de pago de Conekta debe estar la opción de "Enabled" en "Yes" y las opciones de "Api Keys" puedes usar llaves de prueba:
    
Api Public Key: 
    EpnQNGMghzDrytvfpqtG
Api Private Key: 
    1tv5yJp3xnVZ7eK67m4h


Al realizar estos pasos, los métodos de pago de Conekta ya deberían estar disponibles en el Checkout para que los pruebes.

= Inventario

El inventario del producto que se compre por medio de los métodos de pago de Conekta NO será decrementado al completar el checkout hasta que Conekta envíe notificación a tu servidor de Magento de que el pedido ha sido pagado.

= Dependencias

Los módulos que incluye este plugin son:
  Conekta_Banco
  Conekta_Oxxo
  Conekta_Tarjeta
  Conekta_Checkout
  
El módulo de Conekta_Checkout debe estar habilitado para que la lógica de Inventario funcione adecuadamente. De lo contrario, el inventario se decrementará al completar el checkout independientemente si el estatus del pedido es de pagado en Conekta o no.
