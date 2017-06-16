<?php

return [
    'adminEmail' => 'admin@example.com',
    'modUsuarios' => [
        'facebook'=>[
            'usarLoginFacebook'=>true,
            'APP_ID'=>'1754524001428992', // Identificador de la aplicación
            'APP_SECRET'=>'739c88b9290adb41a040bde473c1d54d', // Clave secreta de la aplicación
            'CALLBACK_URL'=>'http://notei.com.mx/test/wwwLogin/web/callback-facebook',
            'dataBasic'=>true, // Obtiene datos basicos del usuario como nombre, imagen, apellido, email
            'friends'=>true, // Visualiza a los amigos que esten usuando la aplicacion
            'permisosForzosos'=>'email, user_friends',
            'permisos'=>'public_profile, email, user_friends',
        ],
        'sesiones' => [ 
            'guardarSesion' => true, // Guardara el registro de sesiones del usuario
            'sesionUnicaPorUsuario' => true, // Solamente habra una sesión por usuario
            'cerrarPrimeraSesion' => true // Cierra la primera sesion abierta para una nueva sesion 
        ],
        'mandarCorreoActivacion' => true, // Envia correo electronico para activar la cuenta del usuario
		'email' => [ 
			'emailActivacion' => 'welcome@2gom.com.mx',
			'subjectActivacion' => 'Activar cuenta',
			'emailRecuperarPass' => 'support@2gom.com.mx',
			'subjectRecuperarPass' => 'Recuperar contraseña' 
		],
		'recueperarPass' => [ 
			'diasValidos' => 2, // Numero de dias que durara la recuperación de la contraseña
			'validarFechaFinalizacion' => true 
		] // validar si la recuperación de contraseña validara la fecha de expiracion
 
    ]
];
