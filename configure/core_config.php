<?php
return array(
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
                              'label' => 'Consumer secret',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'sociallogin/facebook/permission',
                              'type' => 'select',
                              'label' => 'Permission',
                              'validation' => array('required' => false),
                              'value' => ['email','public_profile'],
                              'data' => array('email' => 'email',
                                              'groups_access_member_info' => 'groups_access_member_info',
                                              'publish_to_groups' => 'publish_to_groups',
                                              'user_age_range' => 'user_age_range',
                                              'user_birthday' => 'user_birthday',
                                              'user_events' => 'user_events',
                                              'user_friends' => 'user_friends',
                                              'user_gender' => 'user_gender',
                                              'user_gender' => 'user_gender',
                                              'user_hometown' => 'user_hometown',
                                              'user_likes' => 'user_likes',
                                              'user_link' => 'user_link',
                                              'user_location' => 'user_location',
                                              'user_photos' => 'user_photos',
                                              'user_posts' => 'user_posts',
                                              'user_tagged_places' => 'user_tagged_places',
                                              'user_videos' => 'user_videos',
                                              'manage_pages' => 'manage_pages',
                                              'publish_pages' => 'publish_pages'
                              ),
                              'multiple' => true
                            ),
                      array('path' => 'sociallogin/facebook/version',
                              'type' => 'select',
                              'label' => 'Version',
                              'validation' => array('required' => false),
                              'data' => ['v3.0' => 'v3.0'],
                              'value' => 'v3.0'
                            ),

          )
  )
);

?>
