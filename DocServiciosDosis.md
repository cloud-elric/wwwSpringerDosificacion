# Documentación Servicios Dosificación

Este documento es una descripción de los servicios utilizos en el api de dosificación.


## 1.- Servicio Login

**Nombre del servicio**

    - http://nombre-servidor/web/api/login

**Parametros del Servico**

    - usuario *
    - password *


    * campo obligatorio

Si estos parametros **no se envian en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Email y/o contraseña incorrecto(s)"

Por ultimo, si todo sale **correctamente** en el servicio el json tendrá los siguientes valores

    - "error" = false
    - "message" = "Doctor encontrado"
    - "doctor" = Json con datos del doctor registrado.


## 2.- Servicio Recuperar password

**Nombre del servicio**

    - http://nombre-servidor/web/api/mandar-password

**Parametros del Servico**

    - correo (obligatorio)

Si este parametro **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "No existe doctor"

Si el parametro **se envia correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Email no registrado"

Por ultimo, si todo sale **correctamente** en el servicio se mandara un email al correo que se mando en la parametro el json tendrá los siguientes valores

    - "error" = false
    - "message" = "Correo enviado correctamente"


## 3.- Servicio Agregar Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/crear-paciente

**Parametros del Servico**

    - nombre *
    - apellidoPat *
    - apellidoMat *
    - email *
    - telefono *
    - nacimiento *


    * campos obligatorios

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Datos invalidos"
    - "errosPac" = Json con errores al guardar los datos en la BD

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Paciente guardado"
    - "paciente" = Json con datos del paciente que se acaba de agregar


## 4.- Servicio Recuperar Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/buscar-paciente

**Parametros del Servico**

    - nombre **
    - apPaterno **
    - apMaterno **
    - email **
    - tel **
    - fecha **
    - page *


    ** campo opcional pero al menos uno obligatorio.
    * campo oblogatorio.

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No hay datos"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Paciente mostrado"
    - "paciente" = Json con datos del paciente del que se acaba de hacer la busqueda
    - "request" = json con los datos de la peticion


## 5.- Servicio Buscar un paciente es especifico

**Nombre del servicio**

    - http://nombre-servidor/web/api/get-paciente

**Parametros del Servico**

    - idPaciente (obligatorio)

Si este parametro **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "No existe paciente"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = No existe paciente"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Paciente encontrado"
    - "paciente" = Json con datos del paciente del que se acaba de hacer la busqueda


## 6.- Servicio Agregar Doctor

**Nombre del servicio**

    - http://nombre-servidor/web/api/crear-doctor

**Parametros del Servico**

    - nombre *
    - apellido *
    - email *
    - password *


    * campos obligatorios

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Datos invalidos"
    - "errosDoc" = Json con errores al guardar los datos en la BD

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Doctor guardado"
    - "paciente" = Json con datos del doctor que se acaba de agregar


## 7.- Servicio Buscar todos los Doctores

**Nombre del servicio**

    - http://nombre-servidor/web/api/leer-doctor
    - Metodo GET.

**Parametros del Servico**

    - page *

    * campo obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No hay datos"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Doctores mostrados"
    - "doctor" = Json con datos de los doctores que se han registrado


## 8.- Servicio para Actualizar datos de un Doctor

**Nombre del servicio**

    - http://nombre-servidor/web/api/actualizar-doctor
    - Metodo GET.

**Parametros del Servico**

    - idDoctor *
    - nombre **
    - apellido **
    - email **
    - password **


    * campo obligatorio 
    ** campo opcional pero al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Datos invalidos"
    - "errosDoc" = json con errores al guardar en la BD
    
    o

    - "error" = true
    - "message" = "No hay datos"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Doctor actualizado"
    - "doctor" = Json con datos de los doctores que se ha actualizado


## 9.- Servicio para Eliminar datos de un Doctor

**Nombre del servicio**

    - http://nombre-servidor/web/api/eliminar-doctor
    - Metodo GET.

**Parametros del Servico**

    - idDoctor *


    * campo obligatorio al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "No hay datos por get"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Datos invalidos"
    - "errosDoc" = json con errores al eliminar de la BD
    
    o

    - "error" = true
    - "message" = "No hay datos"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Doctor eliminado"


## 10.- Servicio Buscar todos los pacientes

**Nombre del servicio**

    - http://nombre-servidor/web/api/leer-doctor
    - Metodo GET.

**Parametros del Servico**

    - page *

    * campo obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No hay datos"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Pacientes mostrados"
    - "pacientes" = Json con datos de los pacientes que se han registrado


## 11.- Servicio para Actualizar datos de un Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/actualizar-paciente
    - Metodo GET.

**Parametros del Servico**

    - idPaciente *
    - nombre **
    - apellidoPat **
    - apellidoMat
    - email **
    - $telefono **
    - $nacimiento **


    * campo obligatorio
    ** campo opcional pero al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "No hay datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Datos invalidos"
    - "errosPac" = json con errores al guardar en la BD
    
    o

    - "error" = true
    - "message" = "Paciente no encontrado"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Paciente actualizado"
    - "doctor" = Json con datos del Paciente que se ha actualizado


## 12.- Servicio para Eliminar datos de un Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/eliminar-paciente
    - Metodo GET.

**Parametros del Servico**

    - idPaciente *


    * campo obligatorio al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "No hay datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Datos invalidos"
    - "errosPac" = json con errores al eliminar de la BD
    
    o

    - "error" = true
    - "message" = "No hay datos"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Paciente eliminado"


## 13.- Servicio para mostrar datos de Dosis de un Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/get-dosis-paciente

**Parametros del Servico**

    - idPaciente *


    * campo obligatorio al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "No hay dosis asignada"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No se encontro paciente"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Datos encontrados"
    - "dosis" = Json con datos de dosis del paciente


## 14.- Servicio para generar PDF de Dosis de un Paciente y mandarla por correo al Doctor

**Nombre del servicio**

    - http://nombre-servidor/web/api/generar-pdf

**Parametros del Servico**

    - id_doctor *
    - id_paciente *
    - num_peso *
    - num_estatura *
    - fch_visita *


    * campo obligatorio al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Error al enviar email"

    o

    - "error" = true
    - "message" = "Error al guardar o no existe paciente o no existe doctor"

Por ultimo, si todo sale **correctamente** en el servicio este responderá con un json el cual tendrá los siguientes valores

    - "error" = false
    - "message" = "Email enviado correctamente"


## 15.- Servicio para descargar PDF de Dosis de un Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/download-pdf

**Parametros del Servico**

    - token *


    * campo obligatorio al menos uno obligatorio

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Datos incorrectos"

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

