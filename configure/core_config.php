<?php
return array(
  'soaiallogin' => array(
          'menu' => "SOCIAL LOGIN CONFIG",
          'title' => "Configuración de API's",
          'config' =>  array(
                     
            array('path' => 'sociallogin/config/redes',
              'type' => 'select',
              'label' => 'Permisos',
              'validation' => array('required' => false),
              'value' => ['email','public_profile'],
              'data' => array('facebook' => 'Faceebook',
                              'google' => 'Google'
              ),
              'multiple' => true
            ),
            array('path' => 'sociallogin/config/email-template-newuser',
            'type' => 'select',
            'label' => 'Template E-mail Nuevo usuario',
            'validation' => array('required' => true),
            'data' => getTemplatesEmail(),
            'value' => ''
            ),
            array('path' => 'sociallogin/config/conection',
            'type' => 'select',
            'label' => 'Tipo de conexion',
            'validation' => array('required' => false),
            'value' => 'login',
            'data' => array('login' => 'login',
                  'login_registro' => 'login + registro'
              )
            )

          )
    ),
    'loginfacebook' => array(
      'menu' => "SOCIAL LOGIN FACEBOOK",
      'title' => "Configuración de API's",
      'config' =>  array(
        array('path' => 'sociallogin/facebook/api',
                'type' => 'text',
                'label' => 'API KEY',
                'validation' => array('required' => false),
                'value' => ''
              ),
        array('path' => 'sociallogin/facebook/secret',
                'type' => 'text',
                'label' => 'API Secret',
                'validation' => array('required' => false),
                'value' => ''
              ),
        array('path' => 'sociallogin/facebook/version',
                'type' => 'select',
                'label' => 'Version',
                'validation' => array('required' => false),
                'data' => ['v20.0' => 'v20.0'],
                'value' => 'v20.0'
              ),
        array('path' => 'sociallogin/facebook/permission',
            'type' => 'select',
            'label' => 'Permisos',
            'validation' => array('required' => false),
            'value' => ['email','public_profile'],
            'data' => array('email' => 'email',
                            'public_profile' => 'public_profile',
                            'user_birthday' => 'user_birthday',
                            'user_gender' => 'user_gender'
            ),
            'multiple' => true
        )
      )
    ),
    'logingoogle' => array(
      'menu' => "SOCIAL LOGIN GOOGLE",
      'title' => "Configuración de API's",
      'config' =>  array(
        array('path' => 'sociallogin/google/api',
                'type' => 'text',
                'label' => 'API KEY',
                'validation' => array('required' => false),
                'value' => ''
              ),
        array('path' => 'sociallogin/google/secret',
                'type' => 'text',
                'label' => 'API Secret',
                'validation' => array('required' => false),
                'value' => ''
              )
    )
  )


);

?>