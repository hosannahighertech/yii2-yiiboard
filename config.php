<?php

return [       
    'userClass'=>'hosanna\profile\models\User',
    'userIdColumn'=>'id',         
    'userNameColumn'=>'username',            
    'genderColumn'=>'gender',     
    'birthdateColumn'=>'birthdate',          
    'regdateColumn'=>'regtime',
    'profile'=>[
        'view'=>'/profile/account/view',
        'edit'=>'/profile/account/view',
    ],            
    'css'=>['css/forum.css'],   
];
