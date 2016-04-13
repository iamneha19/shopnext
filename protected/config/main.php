<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
Yii::setPathOfAlias('xupload', dirname(__FILE__).'/../extensions/xupload');

return array(
	'timeZone' => 'Asia/Calcutta',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
	'theme'=>'frontendTheme',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.geolocation.*',
		'ext.OAuth2.*',
		'application.extensions.solr.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'shopnext',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
		'superadmin',
		'owner',
		'api',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		'GetAccessRule'=>array(
	        'class'=>'GetAccessRuleComponent',
	    ), 
		
		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'facebook'=>array('class'=>'ext.YiiFacebook.YiiFacebook'),
        'google'=>array('class'=>'ext.YiiGoogle.YiiGoogle'),
     	
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'caseSensitive'=>true,  
				'routeVar'=>'route',
			'rules'=>array(
				'product/detail/<name>'=>'product/ProductDetail',
				'product/ShopProducts/<name>'=>'product/ShopProducts',
				'product/sharing/<product_id>'=>'product/ProductDetail',
				'shop/sharing/<shop_id>'=>'shop/shopDetails',	
				'shop/detail/<state_id>/<city_id>/<locality_id>/<name>'=>'shop/shopDetails',
				'shop/shoppingCart'=>'shop/shoppingCart',
				'shop/category/<category>'=>'shop/category',
				'deal/detail/<title>'=>'deal/detail',
				'deal/<deal_id>'=>'deal/detail',				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
			),
		),
		
		// 'db'=>array(
			// 'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		// ),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=192.168.0.59;dbname=shopnext',
			'emulatePrepare' => true,
			'username' => 'neha.agrawal',
			'password' => 'admin@123',
			'charset' => 'utf8',
		),
		'clientScript' => array(
			'scriptMap' => array(
				'jquery.js' => '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.js',
                'jquery-ui.min.js'=>false
			),
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		
		'shopSolr'=>array(
            'class'=>'CSolrComponent',
            'host'=>'192.168.0.132',
            'port'=>8080,
            'indexPath'=>'/solr/shopnext'
        ),
		'locationSolr'=>array(
            'class'=>'CSolrComponent',
            'host'=>'192.168.0.132',
            'port'=>8080,
            'indexPath'=>'/solr/shopnext'
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'superAdminEmail'=>'rohan.kadam@sts.in',
		 // 'SERVER'=>'http://localhost/shopnext_git/',
		 // 'SERVER'=>'http://192.168.0.132/shopnext/',
		 'SERVER'=>'http://localhost/shopnext/',

		// 'SERVER'=>'http://dev2.taolabs.in/php/shopnext/',
		//'LOCAL_SERVER'=>'http://localhost/shopnext/',
		//'DEV_SERVER'=>'http://dev2.taolabs.in/php/shopnext/',
		'BLOCK_CONTROLLER'=>array(0=>'Default'),		
		// Used for facebook login/register
		
		/* 'fbAppId'=>'856497144421590',
		'fbAppSecret'=>'2d9f46b495b881cfa7499cc51da0bcb8', */
		
		 'fbAppId'=>'1553593948235269',
		'fbAppSecret'=>'d7df87ecc7bb167020fe4985edddeeec',
		// Used for Google+ login/register
		
		 /* 'googleClientId' => '1033798182857-nveok0n4r14qv4li1dqgcn8gi9quo9ni.apps.googleusercontent.com',
		 'googleClientSecret' => 'tuvYmfsPu6qnVublyrJf5zod', */
		 
		'googleClientId' => '8568146103-708hrksva586cvao83annf1cumudj50v.apps.googleusercontent.com',
		'googleClientSecret' => 'z95ZldFyhph-_boNiyBcrtkk',
		// Used for Twitter login/register
		// 'googleClientId' => '1033798182857-nveok0n4r14qv4li1dqgcn8gi9quo9ni.apps.googleusercontent.com',
		// 'googleClientSecret' => 'tuvYmfsPu6qnVublyrJf5zod',
		'MAILER_SETTINGS' =>array(
								'mailer'=>'smtp',
								'mailer_secure'=>'ssl ',
								'mailer_host'=>'smtp.gmail.com',
								'mailer_port_number'=>'465',
								'mailer_auth'=>'1',
								'mailer_email_id'=>'rohan.kadam@sts.in',
								'mailer_password'=>'Donald@123',
								'default_mailfrom_id'=>'garima.singh@sts.in',
								'default_mailfrom_name'=>'Shopnext',
							),
		'BLOCK_ACTION'=>array(
								0=>'Index',
								1=>'AutocompleteBlog',
								2=>'AutocompleteBlogComment',
								3=>'Activate',
								4=>'Deactivate',
								5=>'Setstatus',
								6=>'AutocompleteCategory',
								7=>'AutocompleteUser',
								8=>'GetDynamicCity',
								9=>'GetDynamicLocality',
								10=>'AutocompleteShop',
								11=>'ManageImages',
								12=>'UploadData',
								13=>'ChangeStatus',
								14=>'SetStatus',
								15=>'GetDynamicState',
							),
		'ALLOW_CONTROLLER_ACTION'=>array(
							'CITY'=>array(0=>'GetDynamicState'),
							'LOCALITY'=>array(0=>'GetDynamicState',1=>'GetDynamicCity'),
							'DEAL'=>array(0=>'ChangeStatus',1=>'Setstatus',2=>'AutocompleteShop'),
							'BLOG'=>array(0=>'AutocompleteBlog',1=>'Setstatus',2=>'ChangeStatus'),
							'BLOGCOMMENT'=>array(0=>'AutocompleteBlogComment',1=>'Setstatus',2=>'ChangeStatus'),
							'ADMIN'=>array(0=>'Activate',1=>'Deactivate',2=>'Setstatus',3=>'ChangeStatus'),
							'OWNER'=>array(0=>'Activate',1=>'Deactivate',2=>'Setstatus',3=>'ChangeStatus'),
							'SHOPCOMMENT'=>array(0=>'Setstatus',1=>'ChangeStatus'),
							'PRODUCTCOMMENT'=>array(0=>'Setstatus',1=>'ChangeStatus'),
							'DEALCOMMENT'=>array(0=>'Setstatus',1=>'ChangeStatus'),
							'SHOP'=>array(0=>'AutocompleteCategory',1=>'AutocompleteOwner',2=>'GetDynamicCity',3=>'GetDynamicLocality',4=>'ManageImages',5=>'Upload',6=>'uploadData',7=>'Setstatus',8=>'changeStatus'),
							'USER'=>array(0=>'setStatus',1=>'ChangeStatus'),
							'CATEGORY'=>array(0=>'setStatus',1=>'ChangeStatus'),
							'PRODUCTCATEGORY'=>array(0=>'setStatus',1=>'ChangeStatus'),
							'PRODUCT'=>array(0=>'ManageImages',1=>'Upload',2=>'uploadData',3=>'SetStatus ',4=>'ChangeStatus',5=>'AutocompleteShop',6=>'AutocompleteCategory',)
							),
		'SOLR_CONFIG'=>array(
								'hostname' =>'192.168.0.132',
								'login'    =>'admin',
								'password' =>'solr',
								'port'     =>'8080',
								'path'     => '/solr/shop'
							),
		'SOLR_LOCATION_CONFIG'=>array(
								'hostname' =>'192.168.0.132',
								'login'    =>'admin',
								'password' =>'solr',
								'port'     =>'8080',
								'path'     => '/solr/location'
							),
	),
);