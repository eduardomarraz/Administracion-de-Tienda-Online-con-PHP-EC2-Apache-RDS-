         ___        ______     ____ _                 _  ___  
        / \ \      / / ___|   / ___| | ___  _   _  __| |/ _ \ 
       / _ \ \ /\ / /\___ \  | |   | |/ _ \| | | |/ _` | (_) |
      / ___ \ V  V /  ___) | | |___| | (_) | |_| | (_| |\__, |
     /_/   \_\_/\_/  |____/   \____|_|\___/ \__,_|\__,_|  /_/ 
 ----------------------------------------------------------------- 
### Multi-website Practice with AWS CloudFront

#### Step 1:
Configuración de una Instancia EC2 en AWS
Este repositorio contiene instrucciones detalladas para configurar una instancia EC2 en AWS y establecer un servidor web utilizando Apache y PHP.

Pasos
Paso 1: Configuración de la Instancia EC2
Inicia sesión en la Consola de AWS y navega a EC2:
Inicia sesión en tu cuenta de AWS y dirígete al servicio EC2.
Lanzamiento de una Nueva Instancia:
Haz clic en "Launch Instance" para lanzar una nueva instancia.
Selecciona una AMI de Amazon Linux.
Elige el tipo de instancia t2.micro.
Configura adecuadamente los ajustes de seguridad para permitir el tráfico HTTP y SSH.
Crea una nueva clave de par de claves o utiliza una existente para acceder a tu instancia mediante SSH.
Lanza la instancia.
Paso 2: Conexión a la Instancia EC2 mediante SSH
Opciones para Conectarse:
Utiliza el botón “Conectar” que aparece cuando seleccionas la EC2.
Utiliza tu cliente SSH para conectarte a tu instancia EC2 utilizando la dirección IP pública proporcionada por AWS y la clave de par de claves en Cloud9.
Paso 3: Instalación y Configuración del Servidor Web Apache
Ejecución de Comandos:
Una vez conectado a la instancia EC2, ejecuta los siguientes comandos:
 ```json
sudo yum update -y
sudo yum install httpd -y
sudo service httpd start
sudo chkconfig httpd on
cd /var/www/html
sudo yum install php php-cli php-json php-mbstring -y
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php composer-setup.php
sudo php -r "unlink('composer-setup.php');"
sudo php composer.phar require aws/aws-sdk-php
sudo yum install php-mysqlnd -y
sudo wget https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm 
sudo dnf install mysql80-community-release-el9-1.noarch.rpm -y
sudo rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql-2023
sudo dnf install mysql-community-client -y
sudo dnf install mysql-community-server -y
sudo service httpd restart
```
Paso 4: Creación de RDS
Configuración de RDS:
Elige la opción “easy create”, motor de base de datos “mysql”.
Selecciona el tamaño “free trial” y configura las credenciales.
En la opción de “setup EC2 connection”, elige tu instancia creada anteriormente.
Paso 5: Creación de Tabla en RDS
Conexión y Creación:
Conéctate a la base de datos desde la instancia EC2.
Una vez conectado, crea la base de datos y la tabla necesarias.
Paso 6: Creación de Scripts PHP para Procesar los Productos
Creación de Archivos:
Crea los archivos PHP y CSS dentro de la carpeta html con el siguiente comando:
```json
sudo touch agregar_producto.php modificar_producto.php borrar_producto.php index.php styles.css
```
Notas
Asegúrate de reemplazar las variables con tus propios datos en los scripts PHP.
Verifica la configuración del servidor Apache y PHP para asegurarte de que todo esté funcionando correctamente.
Accede a la IP pública de tu instancia EC2 para interactuar con la interfaz y probar las funciones.
Para solucionar errores, revisa los logs del frontend y del servicio PHP en /var/log/.
