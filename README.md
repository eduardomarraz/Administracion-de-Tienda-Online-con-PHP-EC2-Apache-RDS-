         ___        ______     ____ _                 _  ___  
        / \ \      / / ___|   / ___| | ___  _   _  __| |/ _ \ 
       / _ \ \ /\ / /\___ \  | |   | |/ _ \| | | |/ _` | (_) |
      / ___ \ V  V /  ___) | | |___| | (_) | |_| | (_| |\__, |
     /_/   \_\_/\_/  |____/   \____|_|\___/ \__,_|\__,_|  /_/ 
 ----------------------------------------------------------------- 
### Resumen del Proyecto (Spanish):
Vamos a realizar en AWS una automatización de mensajes mediante las metricas y alarmas de CloudWatch estableciendo que conforme los contenedores docker (Creados en una EC2) sufran alguna caida o fallo imprevisto, envien un tópico SNS, y luego ese mensaje activa una función Lambda que envía una notificación al canal de Slack "#devops".

### Project Summary (English): 
We will implement message automation on AWS using CloudWatch metrics and alarms, establishing that as Docker containers (created on an EC2 instance) experience any unexpected failures or crashes, they will send a message to an SNS topic. Then, that message triggers a Lambda function that sends a notification to the Slack channel "#devops".

#### Pasos:
Configuración de una Instancia EC2 en AWS
Este repositorio contiene instrucciones detalladas para configurar una instancia EC2 en AWS y establecer un servidor web utilizando Apache y PHP.

#### Paso 1: Configuración de la Instancia EC2
Inicia sesión en la Consola de AWS y navega a EC2:
Inicia sesión en tu cuenta de AWS y dirígete al servicio EC2.
Lanzamiento de una Nueva Instancia:
Haz clic en "Launch Instance" para lanzar una nueva instancia.
Selecciona una AMI de Amazon Linux.
Elige el tipo de instancia t2.micro.
Configura adecuadamente los ajustes de seguridad para permitir el tráfico HTTP y SSH.
Crea una nueva clave de par de claves o utiliza una existente para acceder a tu instancia mediante SSH.
Lanza la instancia.

#### Paso 2: Una vez que la instancia EC2 esté en funcionamiento, necesitarás conectarte a ella mediante SSH. Sigue estos pasos para hacerlo:
Abre tu terminal o cliente SSH:
Utiliza tu terminal o un cliente SSH como PuTTY si estás en un sistema operativo Windows.
Conéctate a la instancia EC2:
Utiliza el comando SSH junto con la dirección IP pública de tu instancia EC2 y la clave de par de claves que configuraste durante el lanzamiento de la instancia. Por ejemplo:
 ```json
ssh -i ruta/a/tu/clave.pem ec2-user@direccion-ip-publica
```
Reemplaza "ruta/a/tu/clave.pem" con la ruta a tu clave de par de claves y "direccion-ip-publica" con la dirección IP pública de tu instancia EC2.
Inicia sesión en la instancia:
Una vez conectado, estarás dentro de la instancia EC2 y podrás comenzar a trabajar en ella.
Con estos pasos, habrás establecido una conexión SSH con tu instancia EC2 en AWS y podrás proceder con la configuración y despliegue de tu aplicación.

#### Paso 3: Instalación y Configuración del Servidor Web Apache con PHP
Ahora que estás conectado a tu instancia EC2, es hora de instalar y configurar el servidor web Apache con soporte para PHP. A continuación, se detallan los pasos a seguir:

Actualizar librerías:
Ejecuta el siguiente comando para actualizar las librerías de tu instancia:
 ```json
sudo yum update -y
```
Instalar Docker:
Utiliza el siguiente comando para instalar Docker en tu instancia
 ```json
sudo yum install -y docker
```
Arrancar Docker:
Inicia el servicio Docker con el siguiente comando:
```json
sudo service docker start
```
Añadir usuario al grupo Docker:
Para evitar usar sudo cada vez que ejecutes comandos Docker, añade tu usuario al grupo Docker con el siguiente comando:
```json
sudo usermod -a -G docker $(whoami)
```
Instala pip3, el gestor de paquetes de Python, con el siguiente comando:
```json
sudo yum install -y python3-pip
```
Descarga el binario de Docker Compose con el siguiente comando:
```json
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
```
Dar permisos de ejecución al archivo binario de Docker Compose:
Haz que el archivo binario de Docker Compose sea ejecutable con el siguiente comando:
```json
sudo chmod +x /usr/local/bin/docker-compose
```
Comprobar la instalación de Docker Compose:
Verifica que Docker Compose esté instalado correctamente ejecutando:
```json
docker-compose --version
```
Crear la estructura de directorios del proyecto:
Crea una carpeta llamada "proyecto" y dentro de ella crea dos subdirectorios llamados "html" y "php". Navega al directorio "html" con el siguiente comando:
```json
mkdir proyecto && cd proyecto/html
```
Dentro de este directorio Ejectuar los siguientes comandos para instalar php:
```json
sudo yum install php  php-cli php-json  php-mbstring  -y 

sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" 

sudo php composer-setup.php 

sudo php -r "unlink('composer-setup.php');" 

sudo php composer.phar require aws/aws-sdk-php 
```
#### Paso 4: Crear una Lambda y un Tópico SNS
En esta etapa, configuraremos una función Lambda y un tópico SNS para manejar las notificaciones de nuestra aplicación. Sigue estos pasos:

Crear una función Lambda:
Accede al servicio AWS Lambda desde la consola de AWS.
Haz clic en "Create function" y elige "Author from scratch".
Asigna un nombre a tu función y selecciona Python 3.12 como el lenguaje de programación.
En el campo "Execution role", elige el único IAM Role disponible.
Haz clic en "Create function".
Agregar código a la función Lambda:
En la sección de código de la función Lambda, copia y pega el siguiente código Python:
```json
import urllib3
import json

http = urllib3.PoolManager()

def lambda_handler(event, context):
    url = "https://hooks.slack.com/services/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"
    msg = {
        "channel": "#devops",
        "username": "abdessamad.ammi",
        "text": event['Records'][0]['Sns']['Message'],
        "icon_emoji": ""
    }
    
    encoded_msg = json.dumps(msg).encode('utf-8')
    resp = http.request('POST', url, body=encoded_msg)
    print({
        "message": event['Records'][0]['Sns']['Message'], 
        "status_code": resp.status, 
        "response": resp.data
    })

```
Este código enviará un mensaje al canal de Slack "#devops" cada vez que se publique un mensaje en el tópico SNS.
Crear un tópico SNS:
Accede al servicio Amazon SNS desde la consola de AWS.
Haz clic en "Create topic" y asigna un nombre al tópico.
Una vez creado, selecciona el tópico y haz clic en "Create subscription".
Elige el tipo de protocolo "Lambda" y selecciona la función Lambda que acabas de crear.
Haz clic en "Create subscription".
Con estos pasos, has configurado una función Lambda para manejar las notificaciones de tu aplicación y un tópico SNS para publicar mensajes.

#### Paso 5: Configuración del Proyecto
Ahora, vamos a configurar el proyecto de nuestra aplicación. Sigue estos pasos:

Crear archivos PHP y HTML:
Dentro del directorio "html" de tu proyecto, crea los siguientes archivos:
info.php: Contiene la información de PHP.
```json
<?php phpinfo(); ?> 
```
index.html: Es la página principal de la aplicación.
```json
<!DOCTYPE html>  

<html lang="en">  

<head>  

    <meta charset="UTF-8">  

    <meta name="viewport" content="width=device-width, initial-scale=1.0">  

    <title>Contact Form</title>  

</head>  

<body>  

    <h1>Contact Form</h1>  

    <form action="submit.php" method="POST">  

        <label for="name">Name:</label><br>  

        <input type="text" id="name" name="name" required><br>  

        <label for="email">Email:</label><br>  

        <input type="email" id="email" name="email" required><br>  

        <label for="message">Message:</label><br>  

        <textarea id="message" name="message" rows="4" required></textarea><br>  

        <input type="submit" value="Submit">  

    </form>  

</body>  

</html> 
```
Crear archivo submit.php:
Crea un archivo llamado "submit.php" dentro del directorio "html" para manejar el envío de formularios.
Copia y pega el siguiente código en el archivo para manejar el envío del formulario y almacenar los datos en una base de datos MySQL.
```json
<?php
require 'vendor/autoload.php';

use Aws\Exception\AwsException;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
 
    // Crear mensaje para enviar al tópico SNS
    $messageToSend = json_encode([
        'email' => $email,
        'name' => $name,
        'message' => $message
    ]);
 
    try {
         
        // Insertar datos del formulario en la base de datos MySQL
        $mysqli = new mysqli("mysql", "my_user", "my_password", "my_database");
 
        // Verificar conexión
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
 
        // Preparar y vincular
        $stmt = $mysqli->prepare("INSERT INTO form_data (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
 
        // Ejecutar consulta
        $stmt->execute();
 
        echo "Message sent successfully."; 
    } catch (AwsException $e) {
        echo "Error sending message: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
```
Asegúrate de reemplazar los valores de conexión a la base de datos con los adecuados para tu entorno.
Con estos pasos, has configurado la estructura básica de tu proyecto y has creado los archivos necesarios para tu aplicación.

#### Creamos el Dockerfile de nuestro servicio php, dentro de la carpeta “php”:
```json
# Use an official PHP Apache runtime 

FROM php:8.2-apache 

# Enable Apache modules 

RUN a2enmod rewrite  

# Install PostgreSQL client and its PHP extensions 

RUN apt-get update \ 

    && apt-get install -y libpq-dev \ 

    && docker-php-ext-install pdo pdo_pgsql 

# Install MySQLi extension 

RUN docker-php-ext-install mysqli   

# Set the working directory to /var/www/html 

WORKDIR /var/www/html 

# Copy the PHP code file in /app into the container at /var/www/html 

COPY ../html . 
```
#### Creamos el script sql  llamado create_table.sql en la ruta raiz del proyecto que creara nuestra tabla del formulario , y le damos permisos de ejecución y lectura con el comando “chmod +xr create_table.sql” :
```json
CREATE TABLE IF NOT EXISTS form_data ( 

    id INT AUTO_INCREMENT PRIMARY KEY, 

    name VARCHAR(255) NOT NULL, 

    email VARCHAR(255) NOT NULL, 

    message TEXT NOT NULL, 

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
); 
```
Creamos el directorio “mysql_data” en la ruta raiz del proyecto donde persistiran nuestros datos. 

#### Añadir Rol a la EC2 

Vamos a la consola de la EC2, vamos a acciones -> seguridad -> Añadir Rol 
Elegimos el Rol “LabInstanceProfile” 
Luego vamos al servicio IAM y buscamos este Rol, y añadimos la politica “CloudWatchFullAccess” 

#### Paso 6: Despliegue de la Infraestructura
En este paso, vamos a utilizar Docker Compose para desplegar la infraestructura de nuestra aplicación. Sigue estos pasos:

Crear el archivo docker-compose.yml:
En la raíz de tu proyecto, crea un archivo llamado "docker-compose.yml".
Copia y pega el siguiente contenido en el archivo para definir los servicios de Apache, MySQL y phpMyAdmin:
```json
version: '3.9'

services:
  # Servicio del servidor web Apache
  webserver:
    container_name: PHP-webServer
    build:
      # Ruta del Dockerfile
      context: .
      dockerfile: ./php/Dockerfile  
    # Monta el directorio local ./html en /var/www/html en el contenedor
    volumes:
      - ./html:/var/www/html 
    # Mapea el puerto 80 del host al puerto 80 del contenedor 
    ports:
      - 80:80 
    networks:
      - my_network

  # Servicio de MySQL
  mysql:
    container_name: mysql-db
    image: mysql:latest
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: example_password
      MYSQL_DATABASE: my_database
      MYSQL_USER: my_user
      MYSQL_PASSWORD: my_password
    volumes:
      - ./mysql_data:/var/lib/mysql
      - ./create_table.sql:/docker-entrypoint-initdb.d/create_table.sql # Monta el archivo SQL en el directorio de inicio de MySQL
    networks:
      - my_network

  # Servicio de phpMyAdmin
  phpmyadmin:
    container_name: phpmyadmin
    image: phpmyadmin/phpmyadmin:latest
    restart: always
    ports:
      - 8080:80
    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: example_password
    networks:
      - my_network

networks:
  my_network:
```
Crear directorios y archivos adicionales:
Asegúrate de tener los siguientes directorios y archivos creados en la raíz de tu proyecto:
html: Contiene los archivos PHP y HTML de tu aplicación.
php: Contiene el Dockerfile y otros archivos relacionados con el servicio PHP.
mysql_data: Directorio donde persistirán los datos de MySQL.
create_table.sql: Script SQL para crear la tabla de la base de datos.
Ejecutar el comando docker-compose:
En la terminal de tu instancia EC2, navega hasta la raíz de tu proyecto donde se encuentra el archivo "docker-compose.yml".
Ejecuta el siguiente comando para desplegar la infraestructura:
```json
sudo docker-compose up
```
Con estos pasos, habrás desplegado la infraestructura de tu aplicación utilizando Docker Compose en tu instancia EC2.

#### Paso 7: Automatización de Operaciones
En esta etapa, vamos a automatizar las operaciones de monitoreo de nuestros servicios Docker. Sigue estos pasos:

Crear el archivo monitoring_services_status.sh:
En la raíz de tu proyecto, crea un archivo llamado "monitoring_services_status.sh".
Copia y pega el siguiente contenido en el archivo para monitorear el estado de los contenedores Docker y enviar métricas a CloudWatch:
```json
#!/bin/bash

# Función para enviar métrica a CloudWatch
send_metric_to_cloudwatch() {
    local metric_name=$1
    local value=$2
    aws cloudwatch put-metric-data --namespace "CustomMetrics" --metric-name "$metric_name" --value "$value"
}

# Verificar si el contenedor MySQL está corriendo
if docker ps -q --filter "name=mysql-db" | grep -q .; then
    # Enviar métrica igual a 0 si el contenedor está corriendo
    send_metric_to_cloudwatch "mysql" 0
else
    # Enviar métrica igual a 1 si el contenedor no está corriendo
    send_metric_to_cloudwatch "mysql" 1
fi

# Verificar si el contenedor PHP-webServer está corriendo
if docker ps -q --filter "name=PHP-webServer" | grep -q .; then
    # Enviar métrica igual a 0 si el contenedor está corriendo
    send_metric_to_cloudwatch "php-webserver" 0
else
    # Enviar métrica igual a 1 si el contenedor no está corriendo
    send_metric_to_cloudwatch "php-webserver" 1
fi

# Verificar si el contenedor phpmyadmin está corriendo
if docker ps -q --filter "name=phpmyadmin" | grep -q .; then
    # Enviar métrica igual a 0 si el contenedor está corriendo
    send_metric_to_cloudwatch "phpmyadmin" 0
else
    # Enviar métrica igual a 1 si el contenedor no está corriendo
    send_metric_to_cloudwatch "phpmyadmin" 1
fi
```
Este script utiliza la AWS CLI para enviar métricas a CloudWatch que indican si los contenedores están en ejecución.
Dar permisos de ejecución al script:
Ejecuta el siguiente comando para dar permisos de ejecución al script:
```json
chmod +x monitoring_services_status.sh
```
Configurar el crontab:
Abre el archivo crontab ejecutando el siguiente comando:
```json
crontab -e
```
Añade la siguiente línea al archivo crontab para ejecutar el script cada minuto:
```json
* * * * * /home/nombre_del_usuario/proyecto/monitoring_services_status.sh
```
Reemplaza "ruta/a/tu/script" con la ubicación completa del script en tu instancia EC2.
Con estos pasos, has automatizado las operaciones de monitoreo de tus servicios Docker y estás enviando métricas a CloudWatch de forma regular.

#### Paso 8: Configuración de Alarmas en CloudWatch
En este último paso, vamos a configurar alarmas en CloudWatch para monitorear el estado de nuestros servicios Docker y recibir notificaciones en caso de problemas. Sigue estos pasos:

Verificar las métricas en CloudWatch:
Accede al servicio CloudWatch desde la consola de AWS.
Navega a "Metrics" y busca el espacio de nombres "CustomMetrics". Deberías ver las métricas "mysql", "php-webserver" y "phpmyadmin".

Crear alarmas (continuación):
Haz clic en "Alarms" en el panel de navegación de CloudWatch.
Haz clic en "Create alarm".
Selecciona la métrica correspondiente a uno de tus servicios Docker (por ejemplo, "php-webserver").
Especifica las condiciones para la alarma. Por ejemplo, puedes configurar la alarma para que se active si la métrica es igual o mayor que 1 durante al menos 1 período de evaluación (que podría ser 1 minuto).
Haz clic en "Next".

Configura la acción de la alarma para enviar una notificación al tópico SNS que creaste anteriormente.
Completa el proceso de creación de la alarma.
Repite estos pasos para crear alarmas para los otros servicios Docker (MySQL y phpMyAdmin).

Probar las alarmas:
Una vez que las alarmas estén configuradas, puedes probarlas deteniendo uno de los contenedores Docker manualmente (por ejemplo, el contenedor de php-webserver).
CloudWatch detectará que la métrica correspondiente supera el umbral configurado y activará la alarma.
Como resultado, se enviará una notificación al tópico SNS, que a su vez enviará un mensaje al canal de Slack "#devops" a través de la función Lambda configurada.
Con estos pasos, habrás configurado alarmas en CloudWatch para monitorear el estado de tus servicios Docker y recibir notificaciones en caso de problemas.
