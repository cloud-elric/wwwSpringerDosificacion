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
    - apPaterno *
    - apMaterno **    
    - email *
    - telefono **
    - edad *
    - sexo *
    - id_doctor *
    - peso *
    - id_paciente_cliente *
    - txt_token_seguridad ***

    * campos obligatorios
    ** campos opcional
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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
    - email **
    - edad **
    - sexo **
    - page *
    - txt_token_seguridad ***    


    ** campo opcional pero al menos uno obligatorio.
    * campo oblogatorio.
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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
    - txt_token_seguridad ***


    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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
    - clave *
    - cedula (opcional)


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
    - txt_token_seguridad ***


    * campo obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

**Parametros del Servico**

    - idDoctor *
    - nombre **
    - apellido **
    - email **
    - password **
    - cedula **
    - txt_token_seguridad ***


    * campo obligatorio 
    ** campo opcional pero al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

**Parametros del Servico**

    - idDoctor *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

    - http://nombre-servidor/web/api/leer-paciente
    - Metodo GET.

**Parametros del Servico**

    - page *
    - txt_token_seguridad ***

    * campo obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

**Parametros del Servico**

    - idPaciente *
    - nombre **
    - apPaterno **
    - apMaterno **
    - email **
    - telefono **
    - edad **
    - sexo**
    - txt_token_seguridad ***


    * campo obligatorio
    ** campo opcional pero al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

**Parametros del Servico**

    - idPaciente *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

    - idTratamiento *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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

    - id_tratamiento *
    - id_presentacion *
    - num_peso *
    - dosisSugerida *
    - dosisAcumulada *
    - dosisDiaria *
    - diasTratamiento *
    - num_estatura **
    - fch_visita **
    - id_tratamiento_cliente *
    - id_dosis_cliente *
    - dosisObjetivo *
    - dosisObjetivoCal *
    - dosisRedondeada *
    - numMeses *
    - numCapsulas *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    ** campo opcional
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

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
    - "consulta" = "Json con datos de dosis del paciente"
    - "tratamiento" = "Json con los datos del tratamiento actualizado"


## 15.- Servicio para descargar PDF de Dosis de un Paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/download-pdf

**Parametros del Servico**

    - token *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Datos incorrectos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No exixte archivo"

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.


## 16.- Servicio para mostrar pacientes de cada doctor

**Nombre del servicio**

    - http://nombre-servidor/web/api/get-pacientes-doctor

**Parametros del Servico**

    - id_doctor *
    - page **
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    ** campo opcional
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No hay pacientes"

Por ultimo, si **se envian ambos parametro correctamente** el servicio respondera con.

    - "error" = false
    - "message" = "Pacientes encontrados"
    - "pacientes" = JSON con los datos de los pacientes (maximo 50 registros y con paginacion)

si **se envian solo el parametro de id_doctor correctamente** el servicio respondera con.

    - "error" = false
    - "message" = "Pacientes encontrados"
    - "pacientes" = JSON con los datos de los pacientes (todos lo que se han registrado)


## 17.- Servicio para crear tratamientos de paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/crear-tratamiento

**Parametros del Servico**

    - id_doctor *
    - id_paciente *
    - id_presentacion *
    - txt_nombre_tratamiento *
    - num_peso *
    - num_dosis_sugerida *
    - num_dosis_acumulada *
    - num_dosis_diaria *
    - num_dias_tratamiento *
    - fch_inicio_tratamiento *
    - id_tratamiento_cliente *
    - id_paciente_cliente *
    - num_dosis_objetivo *
    - num_dosis_objetivo_cal *
    - num_dosis_redondeada *
    - num_meses *
    - num_capsulas *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Error al guardar tratamiento"
    - "tratamientoErr" = JSON con errores al guardar en la BD

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Tratamiento creado"
    - "tratamiento" = JSON con los datos del tratamiento


## 18.- Servicio para mostrar tratamientos de paciente

**Nombre del servicio**

    - http://nombre-servidor/web/api/mostrar-tratamientos

**Parametros del Servico**

    - id_doctor *
    - id_paciente *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No hay tratamientos"

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Tratamientos mostrados"
    - "tratamientos" = JSON con los datos del tratamiento


## 19.- Servicio para crear relacion entre paciente y aviso de privacidad

**Nombre del servicio**

    - http://nombre-servidor/web/api/rel-paciente-aviso

**Parametros del Servico**

    - id_paciente *
    - id_aviso *
    - b_acepto *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Error al guardar en la BD"
    - "relacionErr" = JSON con errores al guardar en la BD

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Paciente acepto el aviso"
    - "relacion" = "Relacion guardada correctamente"

    o

    - "error" = false
    - "message" = "Paciente no acepto el aviso"
    - "relacion" = "Relacion guardada correctamente"


## 20.- Servicio para mostrar tratamiento por id

**Nombre del servicio**

    - http://nombre-servidor/web/api/get-tratamiento

**Parametros del Servico**

    - id_tratamiento *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No hay tratamiento"
    - "tratamiento" = []    

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Tratamiento mostrado"
    - "tratamiento" = JSON con los datos del tratamiento


## 21.- Servicio para mostrar todos los datos relacionados con el doctor

**Nombre del servicio**

    - http://nombre-servidor/web/api/get-data-doctor

**Parametros del Servico**

    - txt_token *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan parametros"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Doctor no encontrado"  

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Pacientes encontrados"
    - "pacientes" = JSON con datos de los pacientes
    - "tratamiento" = JSON con los datos del tratamiento
    - "dosis" = JSON con datos de las dosis    


## 22.- Servicio para asignar una fecha de finalizacion al tratamiento

**Nombre del servicio**

    - http://nombre-servidor/web/api/finalizar-tratamiento

**Parametros del Servico**

    - tokenTratamiento *
    - fchFinalizar *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan Datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "No se encuentra el tratamiento"

    o

    - "error" = true
    - "message" = "Error al guardar tratamiento"
    - "tratErrors" = JSON con errores al guardar

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Asignada fecha de finalización"
    - "tratamiento" = JSON con los datos del tratamiento  


## 22.- Servicio para agregar datos de App a base de datos web

**Nombre del servicio**

    - http://nombre-servidor/web/api/set-data-pacientes

**Parametros del Servico**

    - JSON con todos los datos del paciente, tratamientos y dosis *
    - txt_token_seguridad ***


    * campo obligatorio al menos uno obligatorio
    *** Si la variable privada $seguridad en ApiController es true este campo es obligatorio si no opcional

Si estos parametros **no se envia en la peticion** se regresa un json con los siguientes valores

    - "error" = true
    - "message" = "Faltan Datos"

Si los parametros **se envian correctamente pero hay un error** se regresará un json con los siguientes valores

    - "error" = true
    - "message" = "Error al guardar dosis"
    - "dosisErr" = JSON con los errores al guardar en la BD

    o

    - "error" = true
    - "message" = "Error al guardar tratamiento"
    - "tratamientoErr" = JSON con los errores al guardar en la BD

    o

    - "error" = true
    - "message" = "Error al guardar paciente"
    - "pacienteErr" = JSON con los errores al guardar en la BD

Por ultimo, si todo sale **correctamente** en el servicio este descarga el archivo.

    - "error" = false
    - "message" = "Asignada fecha de finalización"
    - "tratamiento" = JSON con los datos del tratamiento  
