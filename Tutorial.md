<div align= "center">

# Instalación Plugin Magento 1 (1.9 Community Edition).
</div>

### Requerimientos técnicos:

- Magento v.1.9.
- PHP v.5.6 - v.5.6.9

Paso 1:
--------

- Ingresar como administrador a tu sitio de Magento.

![tutorial](/images/1.1.png)


Paso 2:
--------

- Ir a la sección de Magento Connect Manager. Esta opción se encuentra en la sección “System”, dentro del sub-menú “Magento Connect”.

![tutorial](/images/2.1.png)

Paso 3:
--------

- Instalar una nueva extensión, para esto utilizaremos la “extension key” que esta en el marketplace de magento en el siguiente link: https://marketplace.magento.com/catalogsearch/result/?q=conekta.
- Una vez dentro de la página de magento seleccionamos el plugin para magento 1.

![tutorial](/images/3.1.png)

- En la siguiente ventana seleccionamos la versión 1.9 (Community Edition) y presionamos el botón “Add to Cart”.

![tutorial](/images/3.2.png)

- Nos iremos a la parte del carrito, donde aceptaremos la compra y nos mandará a la siguiente página donde daremos clic en el botón “Install”.

![tutorial](/images/3.3.png)

- En la siguiente página solo daremos clic en “copy”.

![tutorial](/images/3.4.png)

- En la página de nuestro administrador de extensiones pegamos el link que copiamos en la pantalla anterior y daremos clic en “Install”.

![tutorial](/images/3.5.png)

- Seguido daremos clic en “proceed”.

![tutorial](/images/3.6.png)

- Al finalizar la instalación, deberás dar clic en el botón “Refresh”.

![tutorial](/images/3.7.png)

- Después de refrescar la instalación, deberás regresar al admin de Magento. Esta opción se encuentra en la parte superior de la página, “Return to Admin”

![tutorial](/images/3.8.png)


Paso 4:
--------

- Ir a la sección de Cache Management. Esta opción se encuentra en la sección “System” al final de la lista.

![tutorial](/images/4.1.png)

- Seleccionar y desactivar todos los elementos de Caché, eligiendo la opción de “Disable” y dando clic al botón “Submit”.

![tutorial](/images/4.2.png)

- Realizar un flush de Magento Cache y Cache Storage dando clic a cada uno de los botones.

![tutorial](/images/4.3.png)

Paso 5:
--------

- Configuración de métodos de pago. Ir a la sección de Configuration. Esta opción se encuentra en la sección “System” al final de la lista.

![tutorial](/images/5.1.png)

- Navegar hasta encontrar la opción de “Payment Methods” y dar clic en ella.

![tutorial](/images/5.2.png)

- Configurar los cuatro métodos de pago Conekta con tus llaves Privadas y Públicas. Estas llaves se encuentran en la opción de “API Keys” de tu Administrador Conekta. Recuerda que es muy importante utilizar la llave privada en modo producción. Si utilizas la llave privada en modo sandbox, todos los pagos que se realicen serán ficticios y no recibirás depósitos por parte de Conekta.

![tutorial](/images/5.3.png)

- Dentro de la configuración de Conekta Card, puedes indicar si deseas aceptar Meses sin Intereses dentro de tu sitio.

![tutorial](/images/5.4.png)

- Al concluir, deberás de guardar las configuraciones dando clic al botón “Save Config” y habrás terminado exitosamente la instalación.
