<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=yiishop',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        //>>邮件配置主键
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',//QQ发件邮件服务器SMTP
                'username' => '10943575@qq.com',//账号
                'password' => 'zzqhayuulzeubhfh',//密码
                'port' => '25',//端口
                'encryption' => 'tls',//加密方式
            ],
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.///安达市多打算大萨达多阿打算大阿达的萨达2313232112323121321312231321
            'useFileTransport' => false,//发送正式邮件
        ],
    ],
];
