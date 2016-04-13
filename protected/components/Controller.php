<?php
/**)
 }* Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	public $dataImages;
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();
	public $user_id = null;
	
	/**
	 * Meta data for page
	 */
	public $page_title = 'Shopnext';
	public $page_type = 'Shop';
	public $page_description = 'Shopnext Description';
	public $page_image = '';
	public $tweet_url = '';
	
	/*
		Generic function to convert addedon & updatedon date to date format from timestamp
	*/
	public function init()
	{
		$module = $this->uniqueid;
		
		if(!empty($this->action->Id))
		{
			$operation = $this->action->Id;
		}
		else
		{
			$operation = 'index';
		}
		
		$geodata = $this->getUserGeolocation();

		if($module=='site' && $operation=='index')
		{
			$this->getDealsNotification($geodata);
		}
	}
	public static function dateConvert($date)
	{
		$date = date("d-M-Y h:i:s a",$date);
		return $date;
	}
	
	public static function dateFromTimestamp($date,$format = 'd-m-Y')
	{
		$date = date($format,$date);
		return $date;
	}
	
	public static function timeFromTimestamp($date,$format = 'h:i:s a')
	{
		$date = date($format,$date);
		return $date;
	}	
	
	public static function datetimeFromTimestamp($date,$format = 'd-m-Y h:i:s a')
	{
		$date = date($format,$date);
		return $date;
	}	
		/* 	
			*Generic function to convert date to ('d-m-y') format from timestamp
		*/
	public static function dobConvert($date)
	{
		$date = date("d-m-Y",$date);
		return $date;
	}
	/*
		Generic function for delete to change status
	*/
	public static function updateDeletedStatus($model,$id)
	{
		$module = ucfirst(Yii::app()->controller->getId());
		$operation = ucfirst(Yii::app()->controller->action->id)."d";
		if($module=='BlogComment')
		{
			$module =  'Blog Comment';
		}
		else if($module=='ProductCategory')
		{
			$module =  'Product Category';
		}
		
		if($module=='Shop')
		{
			$shop_data = Controller::getIndexData($id);
			$shop_data['status'] = "0";
			// $solr_library = new solrLibrary;
			// $solr_library->indexData($shop_data);
			
			Yii::app()->shopSolr->updateOne($shop_data);
			// exit;
		}
		
		if($module=='City')
		{
			$city_data = Controller::getCityIndexData($id);
			$city_data['status'] = "0";
			Yii::app()->locationSolr->updateOne($city_data);
		}
		
		if($module=='Locality')
		{
			$locality_data = Controller::getLocalityIndexData($id);
			$locality_data['status'] = "0";
			Yii::app()->locationSolr->updateOne($locality_data);
		}
		
		if(!isset($_GET['ajax']))
		{
			Yii::app()->user->setFlash('success', $module." ".$operation." Successfully!");
		}
		else
		{
			echo '<div id="statusMsg" class="Metronic-alerts alert alert-success fade in">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<i class="fa-lg fa fa-check"></i>'.$module." ".$operation.' Successfully !
					</div>';
		}
		
		//return $model::model()->updateByPk($id,array('status'=>0));
		return call_user_func(array($model,'model'))->updateByPk($id,array('status'=>0));
		
	}
	
	/*
	Garima
	@getSAMenus return list of all left-menu list for SuperAdmin(SA) module
	Please note :If submenu is to be listed under  menu include it in "submenu" array with its corresponding array for 
				name and icon. Submenu key itself is its controller name. if menu is independent menu i.e. it does not
				contains any submenu then add array key "controller" with its corresponding controller name.				  
	*/
	public static function getSAMenus($spermission = null)
	{
		if($spermission == null){
			$spermission = ApplicationSessions::run()->read('role_permission');
		}
		
		$permission = array();
		
		if(!empty($spermission))
		{
			foreach($spermission as $row=>$val)
			{
				
				if(in_array('Create',$val) || in_array('Admin',$val))
				{
					array_push($permission,$row);
					if(in_array('Create',$val))
					{
						array_push($permission,$row.'_Create');
					}
					if(in_array('Admin',$val))
					{
						array_push($permission,$row.'_Admin');
					}
				}			
			}	
		}
		
		if(!empty($permission))
		{
			
			$master_visibility = (in_array('COUNTRY',$permission) ||  in_array('STATE',$permission) || in_array('CITY',$permission) || in_array('LOCALITY',$permission) || in_array('BRAND',$permission) || in_array('ROLE',$permission)) ? true : false;
			$shops_visibility  = (in_array('SHOP',$permission) ||  in_array('CATEGORY',$permission) || in_array('PRODUCT',$permission) || in_array('COMMENT',$permission)) ? true : false;
			$blogs_visibility  = (in_array('BLOG',$permission) ||  in_array('BLOGCOMMENT',$permission)) ? true : false;
			$deals_visibility  = (in_array('DEAL',$permission) ||  in_array('DEALCOMMENT',$permission)) ? true : false;
						
			$menu = array(
				"menu" =>array(
							'Master' => array(
										"menu-icon"	=> "icon-folder",
										"visibility" => $master_visibility,
										"submenu"   => array(
													"country"  => array(
																		"visibility" => in_array('COUNTRY',$permission),
																		"name"		 => "Country",
																		"icon"		 => "fa fa-globe",
																		"options"	 => array("add"=> in_array('COUNTRY_Create',$permission),"list"=>in_array('COUNTRY_Admin',$permission)),
																	),
													"state"    => array(
																		"visibility" => in_array('STATE',$permission),
																		"name"		 => "State",
																		"icon"		 => "fa fa-map-marker",
																		"options"	 => array("add"=> in_array('STATE_Create',$permission),"list"=>in_array('STATE_Admin',$permission)),
																	),
													"city"     => array(
																		"visibility" => in_array('CITY',$permission),
																		"name"		 => "City",
																		"icon"		 => "fa fa-building-o",
																		"options"	 => array("add"=> in_array('CITY_Create',$permission),"list"=>in_array('CITY_Admin',$permission)),
																	),
													"locality" => array(
																		"visibility" => in_array('LOCALITY',$permission),
																		"name"		 => "Locality",
																		"icon"		 => "fa fa-road",
																		"options"	 => array("add"=> in_array('LOCALITY_Create',$permission),"list"=>in_array('LOCALITY_Admin',$permission)),
																	),
													"brand" => array(
																	"visibility" => in_array('BRAND',$permission),
																	"icon" => "fa fa-bullseye",
																	"name"=>"Brand",
																	"options"	 => array("add"=> in_array('BRAND_Create',$permission),"list"=>in_array('BRAND_Admin',$permission)),
																),
													"role" => array(
																	"visibility" => in_array('ROLE',$permission),
																	"icon" => "fa fa-user",
																	"name"=>"Role",
																	"options"	 => array("add"=> in_array('ROLE_Create',$permission),"list"=>in_array('ROLE_Admin',$permission)),
																),
													
													),
										),
							'Shops' => array(
										"menu-icon"	=> "fa fa-university",
										"visibility" => $shops_visibility,
										"submenu"   => array(
													"shop"     => array(
																		"visibility" => in_array('SHOP',$permission),
																		"name"=>"Shops Master",
																		"icon"=>"fa fa-university",
																		"options"	 => array("add"=> in_array('SHOP_Create',$permission),"list"=>in_array('SHOP_Admin',$permission)),
																	),
													"shopComment"  => array(
																		"visibility" => in_array('SHOPCOMMENT',$permission),
																		"name"=>"Shop Comments",
																		"icon"=>"fa fa-comments-o",
																		"options"	 => array("add"=> in_array('SHOPCOMMENT_Create',$permission),"list"=>in_array('SHOPCOMMENT_Admin',$permission)),
																	),
													"category" => array(
																		"visibility" => in_array('CATEGORY',$permission),
																		"name"=>"Shop Category",
																		"icon"=>"fa fa-sitemap",
																		"options"	 => array("add"=> in_array('CATEGORY_Create',$permission),"list"=>in_array('CATEGORY_Admin',$permission)),
																	),
													"product"  => array(
																		"visibility" => in_array('PRODUCT',$permission),
																		"name"=>"Products",
																		"icon"=>"fa  fa-pinterest-square",
																		"options"	 => array("add"=> in_array('PRODUCT_Create',$permission),"list"=>in_array('PRODUCT_Admin',$permission)),
																	),
													"productComment"  => array(
																		"visibility" => in_array('PRODUCTCOMMENT',$permission),
																		"name"=>"Product Comments",
																		"icon"=>"fa fa-comments-o",
																		"options"	 => array("add"=> in_array('PRODUCTCOMMENT_Create',$permission),"list"=>in_array('PRODUCTCOMMENT_Admin',$permission)),
																	),
													"productCategory"  => array(
																		"visibility" => in_array('PRODUCTCATEGORY',$permission),
																		"name"=>"Product Category",
																		"icon"=>"fa  fa-sitemap",
																		"options"	 => array("add"=> in_array('PRODUCTCATEGORY_Create',$permission),"list"=>in_array('PRODUCTCATEGORY_Admin',$permission)),
																	),
													),
										),
							'Blogs' => array(
										"menu-icon"	=> "fa fa-rss-square",
										"visibility" => $blogs_visibility,
										"submenu" 	=> array(
															"blog"	=> array(
																			"visibility" => in_array('BLOG',$permission),
																			"name"=>"Blogs",
																			"icon"=>"fa fa-rss",
																			"options"	 => array("add"=> in_array('BLOG_Create',$permission),"list"=>in_array('BLOG_Admin',$permission)),
																		),
															"blogComment"  => array(
																			"visibility" => in_array('BLOGCOMMENT',$permission),
																			"name"=>"BlogComment",
																			"icon"=>"fa fa-comments-o",
																			"options"	 => array(/*"add"=> in_array('BLOGCOMMENT_Create',$permission),*/"list"=>in_array('BLOGCOMMENT_Admin',$permission)),
																		),
													),
										),
							'Admin' => array(
										"menu-icon"	 =>	"fa icon-user",
										"visibility" => in_array('ADMIN',$permission),
										"controller" => "admin",
										"options"	 => array("add"=> in_array('ADMIN_Create',$permission),"list"=>in_array('ADMIN_Admin',$permission)),
									),
							'Users' => array(
										"menu-icon" => "fa fa-users",
										"visibility" => in_array('USER',$permission),
										"controller" => "user",
										"options"	 => array("add"=> in_array('USER_Create',$permission),"list"=>in_array('USER_Admin',$permission)),
									),
							
							'Deals' => array(
										"menu-icon" => "fa fa-thumbs-o-up",
										"visibility" => $deals_visibility,
										"submenu" 	=> array(
															"deal"	=> array(
																			"visibility" => in_array('DEAL',$permission),
																			"name"=>"Deals Master",
																			"icon"=>"fa fa-thumbs-o-up",
																			"options"	 => array("add"=> in_array('DEAL_Create',$permission),"list"=>in_array('DEAL_Admin',$permission)),
																		),
															"dealComment"  => array(
																			"visibility" => in_array('DEALCOMMENT',$permission),
																			"name"=>"Deal Comments",
																			"icon"=>"fa fa-comments-o",
																			"options"	 => array(/*"add"=> in_array('BLOGCOMMENT_Create',$permission),*/"list"=>in_array('DEALCOMMENT_Admin',$permission)),
																		),
													),
										),
							"Banners" => array(
										"menu-icon" => "fa fa-file-image-o",
										"visibility" => in_array('BANNER',$permission),
										"controller" => "banner",
										"options"	 => array("add"=> in_array('BANNER_Create',$permission),"list"=>in_array('BANNER_Admin',$permission)),
									),
							"Orders" => array(
										"menu-icon" => "fa fa-edit",
										"visibility" => in_array('ORDER',$permission),
										"controller" => "order",
										"options"	 => array(/*"add"=> in_array('BLOGCOMMENT_Create',$permission),*/"list"=>in_array('ORDER_Admin',$permission)),
									),
							"owner"  => array(
											"menu-icon"=>"fa icon-user",
											"visibility" => in_array('OWNER',$permission),
											"controller"=>"Owner",
											"options"	 => array("add"=> in_array('OWNER_Create',$permission),"list"=>in_array('OWNER_Admin',$permission)),
										),
						),
				);
				
		}	else	{
		
			$menu = array();
		}
		
		return $menu;
	}
	
	/*
	Rohan
	@getMenus 
	@RETURN : list of all left-menu list for Shop owners
	Please note :If submenu is to be listed under  menu include it in "submenu" array with its corresponding array for 
				name and icon. Submenu key itself is its controller name. if menu is independent menu i.e. it does not
				contains any submenu then add array key "controller" with its corresponding controller name.				  
	*/
		
	public static function getOwnerMenus($opermission = null)
	{
		if($opermission == null){
			$opermission = ApplicationSessions::run()->read('owner_role_permission');
			// print_r($opermission);exit;
		}
		
		$permission = array();
		
		if(!empty($opermission))
		{
			foreach($opermission as $row=>$val)
			{
				if(in_array('Create',$val) || in_array('Admin',$val))
				{
					array_push($permission,$row);
					if(in_array('Create',$val))
					{
						array_push($permission,'Owner_'.$row.'_Create');
					}
					if(in_array('Admin',$val))
					{
						array_push($permission,'Owner_'.$row.'_Admin');
					}
				}			
			}	
		}
		
		if(!empty($permission))
		{
			$master_visibility  = (in_array('OWNERROLE',$permission)) ? true : false;
			$shops_visibility  = (in_array('SHOP',$permission) ||  in_array('PRODUCT',$permission) || in_array('COMMENT',$permission)) ? true : false;
			$deals_visibility  = (in_array('DEAL',$permission) ||  in_array('DEALCOMMENT',$permission)) ? true : false;
						
			$menu = array(
				"menu" =>array(
							'Master' => array(
										"menu-icon"	=> "icon-folder",
										"visibility" => $master_visibility,
										"submenu"   => array(
													"ownerRole" => array(
																	"visibility" => in_array('OWNERROLE',$permission),
																	"icon" => "fa fa-user",
																	"name"=>"Owner Role",
																	"options"	 => array("add"=> in_array('Owner_OWNERROLE_Create',$permission),"list"=>in_array('Owner_OWNERROLE_Admin',$permission)),
																),
													
													),
										),
							'Shops' => array(
										"menu-icon"	=> "fa fa-university",
										"visibility" => $shops_visibility,
										"submenu"   => array(
													"shop"     => array(
																		"visibility" => in_array('SHOP',$permission),
																		"name"=>"Shops Master",
																		"icon"=>"fa fa-university",
																		"options"	 => array("list"=>in_array('Owner_SHOP_Admin',$permission)),
																	),
													"shopComment"  => array(
																		"visibility" => in_array('SHOPCOMMENT',$permission),
																		"name"=>"Shop Comments",
																		"icon"=>"fa fa-comments-o",
																		"options"	 => array("add"=> in_array('Owner_SHOPCOMMENT_Create',$permission),"list"=>in_array('Owner_SHOPCOMMENT_Admin',$permission)),
																	),
													"product"  => array(
																		"visibility" => in_array('PRODUCT',$permission),
																		"name"=>"Products",
																		"icon"=>"fa  fa-pinterest-square",
																		"options"	 => array("add"=> in_array('Owner_PRODUCT_Create',$permission),"list"=>in_array('Owner_PRODUCT_Admin',$permission)),
																	),
													"productComment"  => array(
																		"visibility" => in_array('PRODUCTCOMMENT',$permission),
																		"name"=>"Product Comments",
																		"icon"=>"fa fa-comments-o",
																		"options"	 => array("add"=> in_array('Owner_PRODUCTCOMMENT_Create',$permission),"list"=>in_array('Owner_PRODUCTCOMMENT_Admin',$permission)),
																	),
													),
										),
							'Deals' => array(
										"menu-icon" => "fa fa-thumbs-o-up",
										"visibility" => $deals_visibility,
										"submenu" 	=> array(
															"deal"	=> array(
																			"visibility" => in_array('DEAL',$permission),
																			"name"=>"Deals Master",
																			"icon"=>"fa fa-thumbs-o-up",
																			"options"	 => array("add"=> in_array('Owner_DEAL_Create',$permission),"list"=>in_array('Owner_DEAL_Admin',$permission)),
																		),
															"dealComment"  => array(
																			"visibility" => in_array('DEALCOMMENT',$permission),
																			"name"=>"Deal Comments",
																			"icon"=>"fa fa-comments-o",
																			"options"	 => array("add"=> in_array('Owner_DEALCOMMENT_Create',$permission),"list"=>in_array('Owner_DEALCOMMENT_Admin',$permission)),
																		),
														),
												
										),
							"owner"  => array(
											"visibility" => in_array('OWNER',$permission),
											"controller"=>"Owner",
											"menu-icon"=>"fa icon-user",
											"options"	 => array("add"=> in_array('Owner_OWNER_Create',$permission),"list"=>in_array('Owner_OWNER_Admin',$permission)),
										),
							"Orders" => array(
										"menu-icon" => "fa fa-edit",
										"visibility" => in_array('ORDER',$permission),
										"controller" => "Order",
										"options"	 => array(/*"add"=> in_array('BLOGCOMMENT_Create',$permission),*/"list"=>in_array('Owner_ORDER_Admin',$permission)),
									),
							
				
						),
				);
				
			}	
			else	
			{
				$menu = array();
			}
			
			return $menu;
	}
	
	/*
	Garima
	@getHeaderBreadcrumb for Shop owners
	@RETURN :  return header breadcrumb data corresponding to controller and action	ids
	@PARAM : $controller and $action
	*/
	public static function getOwnerHeaderBreadcrumb($controller,$action)
	{
		$controller_sc = strtolower($controller);
		$menu_array = array(
							'master' => array('name'=>'Master','icon'=>'icon-folder'),
							'shop' => array('name'=>'Shops','icon'=>'fa fa-university'),
							'blog' => array('name'=>'Blogs','icon'=>'fa fa-rss'),
							'deal' => array('name'=>'Deals','icon'=>'fa fa-thumbs-o-up'),
						);
		switch ($action) {
                case 'admin':
                       $breadcrumb_li_2 = array('li_data' => 'List of all '.$controller,'li_icon'=>'fa-reorder','li_link'=>'');
                    break;
                case 'create':
						$breadcrumb_li_2 = array('li_data' => 'Add new '.$controller,'li_icon'=>'fa-plus-square','li_link'=>'');
                    break;
				case 'update':
						$breadcrumb_li_2 = array('li_data' => 'Update '.$controller.' details','li_icon'=>'fa-edit','li_link'=>'');
                    break;
               	case 'view':
						$breadcrumb_li_2 = array('li_data' => 'View '.$controller.' details','li_icon'=>'fa-search','li_link'=>''); 
                    break;	
				case 'changepassword':
						$breadcrumb_li_2 = array('li_data' => 'Change password','li_icon'=>'fa-key','li_link'=>''); 
                    break;
				
               
                default:
                    $breadcrumb_li_2 = array(); 
                    break;
            }
        switch ($controller_sc) {
			
                case 'default':
                    return  $return = array(
								'page_header' => 'Dashboard',
								'menu' => '',
								'breadcrumb_li_1' => array('li_data'=>'Dashboard','li_icon'=>'fa-dashboard','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/index'),
								'breadcrumb_li_2' => $breadcrumb_li_2,
								);
                    break;
				case 'shop':
					return $return = array(
						'page_header'=>' My Shops',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'My Shops','li_icon'=>'fa fa-university','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'deal':
					return $return = array(
						'page_header'=>'My Deals',
						'menu' => $menu_array['deal'],
						'breadcrumb_li_1' =>array('li_data'=>'My Deals','li_icon'=>'fa fa-thumbs-o-up','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				
				case 'dealcomment':
					return $return = array(
						'page_header'=>'Deal Comments',
						'menu' => $menu_array['deal'],
						'breadcrumb_li_1' =>array('li_data'=>'Deal Comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'product':
					return $return = array(
						'page_header'=>'My Products',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'My Products','li_icon'=>'fa fa-pinterest-square','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'productcomment':
					return $return = array(
						'page_header'=>'Product Comments',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Product Comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'shopcomment':
					return $return = array(
						'page_header'=>'Shop Comments',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Shop Comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'ownerrole':
					return $return = array(
						'page_header'=>'Owner Roles',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'Owner Roles','li_icon'=>'fa fa-user','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'owner':
					return $return = array(
						'page_header'=>'Owner',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Owner','li_icon'=>'fa icon-user','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
				case 'order':
				return $return = array(
						'page_header'=>'Orders',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Orders','li_icon'=>'fa fa-edit','li_link'=>Yii::app()->baseUrl.'/owner/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
					
                
				default:
                    return  $return = array();
                    break;
					
            }		
	}
	
	public function getHeaderBreadcrumb($controller,$action)
	{
		if($action!="error")
		{
			if(Yii::app()->controller->module->id=='superadmin'){
				return $this->getSaHeaderBreadcrumb($controller,$action);
			}else{
				return $this->getOwnerHeaderBreadcrumb($controller,$action);
			}
		}
		else
		{
			return array();
		}
		
	}
	
	/*
	Amit
	@getOwnerShops for Owner (O) module
	@RETURN :  return shops of owner
	@PARAM : $owner_id
	*/
	public static function getOwnerShopsIds($owner_id = null,$user_id = null)
	{
		if(!empty($owner_id)){
			$condition = 'owner_id="'.$owner_id.'" and status = "1"';
		}else{
			$condition = 'user_id="'.$user_id.'" and status = "1"';
		}	
		$shops = Shop::model()->findAll(array('condition'=>$condition));
		$shop_ids = array();
		foreach($shops as $shop){
			$shop_ids[] = $shop->shop_id;
		}
		return $shop_ids;
	}
	/* 
		Neha
		@getOwnerShops deals for Owner(O) module
		@RETURN :  return deals of shops
	*/
	public static function getOwnerDealIds()
	{	
		$owner_id = ApplicationSessions::run()->read('owner_id');
		$created_by = ApplicationSessions::run()->read('created_by');
		if(empty($created_by)){
			$user_id = null;
		}else{
			$user_id = $owner_id;
			$owner_id = null;
		}	
		$shop_ids_arr = Controller::getOwnerShopsIds($owner_id,$user_id);
		if(!empty($shop_ids_arr))
		{
			$ids_string = implode(",",$shop_ids_arr);
		}
		if(!empty($ids_string))
		{
			$deals = Deal::model()->findAll(array('condition'=>'status="1" and shop_id in('.$ids_string.')'));
		}else{
			$deals = Deal::model()->findAll(array('condition'=>'status="1" and shop_id in("")'));
		}
		$deal_ids = array();
		foreach($deals as $deal)
		{
			$deal_ids[] = $deal->deal_id;
		}
		return $deal_ids;
	}
	
	/* 
		Neha
		@getOwnerShops products for Owner(O) module
		@RETURN :  return products of shops
	*/
	public static function getOwnerProductIds()
	{	
		$owner_id = ApplicationSessions::run()->read('owner_id');
		$created_by = ApplicationSessions::run()->read('created_by');
		if(empty($created_by)){
			$user_id = null;
		}else{
			$user_id = $owner_id;
			$owner_id = null;
		}	
		$shop_ids_arr = Controller::getOwnerShopsIds($owner_id,$user_id);
		if(!empty($shop_ids_arr)){
			$ids_string = implode(",",$shop_ids_arr);
		}
		if(!empty($ids_string))
		{
			$products = Product::model()->findAll(array('condition'=>'status="1" and shop_id in('.$ids_string.')'));
		}else{
			$products = Product::model()->findAll(array('condition'=>'status="1" and shop_id in("")'));
		}
		$product_ids = array();
		foreach($products as $product)
		{
			$product_ids[] = $product->product_id;
		}
		return $product_ids;
	}
	
	/*
	Garima
	@getHeaderBreadcrumb for SuperAdmin (SA) module
	@RETURN :  return header breadcrumb data corresponding to controller and action	ids
	@PARAM : $controller and $action
	*/
	public function getSaHeaderBreadcrumb($controller,$action)
	{
		$controller_sc = strtolower($controller);
		$action = strtolower($action);
		$menu_array = array(
							'master' => array('name'=>'Master','icon'=>'icon-folder'),
							'shop' => array('name'=>'Shops','icon'=>'fa fa-university'),
							'blog' => array('name'=>'Blogs','icon'=>'fa fa-rss'),
							'deal' => array('name'=>'Deals','icon'=>'fa fa-thumbs-o-up'),
						);
		switch($action)
		{
			case 'admin':
				$breadcrumb_li_2 = array('li_data'=>'List of all '.$controller,'li_icon'=>'fa-reorder','li_link'=>'');
			break;
			
			case 'create':
				$breadcrumb_li_2 = array('li_data'=>'Add new '.$controller,'li_icon'=>'fa-plus-square','li_link'=>'');
			break;
			
			case 'view':
				$breadcrumb_li_2 = array('li_data'=>'View '.$controller.' details','li_icon'=>'fa-search','li_link'=>'');
			break;
			
			case 'update':
				$breadcrumb_li_2 = array('li_data'=>'Update '.$controller. ' details','li_icon'=>'fa-edit','li_link'=>'');
			break;
			
			case 'changepassword':
				$breadcrumb_li_2 = array('li_data' => 'Change password','li_icon'=>'fa-key','li_link'=>''); 
            break;	
			
			case 'manageimages':
				$breadcrumb_li_2 = array('li_data' => 'Manage '.$controller. ' images','li_icon'=>'fa-file-image-o','li_link'=>''); 
            break;
			
			case 'assignpermission':
				$breadcrumb_li_2 = array('li_data' => 'Manage permissions','li_icon'=>'fa-check-square-o','li_link'=>''); 
            break;
			
			default:
                $breadcrumb_li_2 = array(); 
             break;
		}
		
		switch($controller_sc)
		{
			case 'default':
				return $return = array(
						'page_header'=>'Dashboard',
						'menu'=>'',
						'breadcrumb_li_1' =>array('li_data'=>'Dashboard','li_icon'=>'fa-dashboard','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/index'),
						'breadcrumb_li_2' => $breadcrumb_li_2,
						);
			break;
			
			case 'country':
				return $return = array(
						'page_header'=>'Country',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'Country','li_icon'=>'fa fa-globe','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'state':
				return $return = array(
						'page_header'=>'State',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'State','li_icon'=>'fa fa-map-marker','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'city':
				return $return = array(
						'page_header'=>'City',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'City','li_icon'=>'fa fa-building-o','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'locality':
				return $return = array(
						'page_header'=>'Locality',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'Locality','li_icon'=>'fa fa-road','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'role':
				return $return = array(
						'page_header'=>'Roles',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'Roles','li_icon'=>'fa fa-user','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			case 'brand':
				return $return = array(
						'page_header'=>'Brands',
						'menu' => $menu_array['master'],
						'breadcrumb_li_1' =>array('li_data'=>'Brands','li_icon'=>'fa fa-bullseye','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			
			case 'shop':
				return $return = array(
						'page_header'=>'Shops',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Shops Master','li_icon'=>'fa fa-university','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'category':
				return $return = array(
						'page_header'=>'Category',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Category','li_icon'=>'fa fa-sitemap','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			case 'product':
				return $return = array(
						'page_header'=>'Products',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Products','li_icon'=>'fa fa-pinterest-square','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			case 'productcategory':
				return $return = array(
						'page_header'=>'Product categories',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Product categories','li_icon'=>'fa fa-sitemap','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'shopcomment':
				return $return = array(
						'page_header'=>'Shop Comments',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Shop Comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'productcomment':
				return $return = array(
						'page_header'=>'Product Comments',
						'menu' => $menu_array['shop'],
						'breadcrumb_li_1' =>array('li_data'=>'Product Comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'dealcomment':
				return $return = array(
						'page_header'=>'Deal Comments',
						'menu' => $menu_array['deal'],
						'breadcrumb_li_1' =>array('li_data'=>'Deal Comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'blog':
				return $return = array(
						'page_header'=>'Blog Master',
						'menu' => $menu_array['blog'],
						'breadcrumb_li_1' =>array('li_data'=>'Blog Master','li_icon'=>'fa fa-rss-square','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'blogcomment':
				return $return = array(
						'page_header'=>'Blog comments',
						'menu' => $menu_array['blog'],
						'breadcrumb_li_1' =>array('li_data'=>'Blog comments','li_icon'=>'fa fa-comments-o','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'admin':
				return $return = array(
						'page_header'=>'Admin',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Admin','li_icon'=>'fa icon-user','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'user':
				return $return = array(
						'page_header'=>'Users',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Users','li_icon'=>'fa fa-users','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			
			case 'deal':
				return $return = array(
						'page_header'=>'Deals Master',
						'menu' => $menu_array['deal'],
						'breadcrumb_li_1' =>array('li_data'=>'Deals Master','li_icon'=>'fa fa-thumbs-o-up','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			case 'order':
				return $return = array(
						'page_header'=>'Orders',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Orders','li_icon'=>'fa fa-edit','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			case 'banner':
				return $return = array(
						'page_header'=>'Banners',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Banners','li_icon'=>'fa fa-file-image-o','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
			break;
			case 'owner':
					return $return = array(
						'page_header'=>'Owner',
						'menu' => '',
						'breadcrumb_li_1' =>array('li_data'=>'Owner','li_icon'=>'fa icon-user','li_link'=>Yii::app()->baseUrl.'/superadmin/'.$controller.'/admin'),
						'breadcrumb_li_2' =>$breadcrumb_li_2,
						);
					break;
			
			default:
					return $return=array();
			break;
		}
	}
	
	/**garima
	* @getLocationGeometry : location geometry 
	* @PARAMS : $address - full address, $postal - postal code
	* @RETURN : array(lat,lng)
	*/
	public function getLocationGeometry($address,$postal=null)
	{		
		$lat = "";
		$lng = "";
		
		$address = str_replace (" ", "+", $address);
		if($postal!='')
		{
			$postal  = str_replace (" ", "", $postal);
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&postal=".$postal."&sensor=false";
		} else 
		{
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
		}	
		
		$header_resp = $this->get_http_response_code($url);
		
		if($header_resp != "404")
		{
			$ch = curl_init($url);
						   curl_setopt_array($ch, array(
						   CURLOPT_URL            => $url,
						   CURLOPT_RETURNTRANSFER => TRUE,
						   CURLOPT_TIMEOUT        => 30,
						   CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
						   CURLOPT_SSL_VERIFYPEER =>0,
						   CURLOPT_SSL_VERIFYHOST => 0
					 ));
				
			$output = curl_exec($ch);
			
			$geoloc = json_decode($output, true);		
			
			curl_close($ch);
			
			if(!empty($output) && is_array($geoloc) && !empty($geoloc) && $geoloc['status']=='OK')
			{	
				if( is_array($geoloc['results']) && !empty($geoloc['results']) && is_array($geoloc['results'][0]) && !empty($geoloc['results'][0]))
				{
					if( is_array($geoloc['results'][0]['geometry']) && !empty($geoloc['results'][0]['geometry']) && is_array($geoloc['results'][0]['geometry']['location']) && !empty($geoloc['results'][0]['geometry']['location']))
					{
						if( array_key_exists('lat',$geoloc['results'][0]['geometry']['location'] ) && array_key_exists('lng',$geoloc['results'][0]['geometry']['location'] ))
						{
							$lat = $geoloc['results'][0]['geometry']['location']['lat'];
							$lng = $geoloc['results'][0]['geometry']['location']['lng'];
						}						
					}
				}
			}
		}
		
		return array('lat'=>$lat,'lng'=>$lng);
	}
	
	/**garima
	* @get_http_response_code : Sends http status 200 or 404
	* @PARAMS : $url 
	* @RETURN : status
	*/
	private function get_http_response_code($url) 
	{
		if($this->validate_url($url))
		{
			$headers = get_headers($url);
			$return =  substr($headers[0], 9, 3);
		}else
		{
			$return =  false;
		}
		return $return;
	}
	/*
	* @Garima 
	* @validate_url : to validate urls
	* @PARAM : Boolean values true -> valid, false -> invalid
	*/
	public function validate_url($url) 
	{
		if(!filter_var($url, FILTER_VALIDATE_URL))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
       
        
    /**Amit
	* @generateRandomString : To generate random string
	* @PARAMS : $length 
	* @RETURN : $string
	*/
	function generateRandomString($length = 10) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
    /**Amit
	* @autocompleteShopJson : Common auto complete function for shops 
	* @PARAMS : $term 
	* @RETURN : json output
	*/  
    public function autocompleteShopJson($term)
	{
		$output = array(0=>array('id'=>'','label'=>'No records found'));		
		
		if(!empty($term))
		{
			$model = new Shop;			
			$resp = $model->findAll(array('condition'=>'name like "%'.$_GET['term'].'%" and status="1" and active_status="S"','select'=>'shop_id,name'));
			
			if(!empty($resp))
			{
				$i = 0;
				$output = array();		
				
			 	foreach($resp as $val)
				{
					$output[$i]['id'] = $val['shop_id'];
					$output[$i]['label'] = $val['name'];
					$i++;
				}
			}
		}		
		
		return CJSON::encode($output);	
	}
	
	/**Amit
	* @autocompleteOwnerShopJson : Auto complete function for owner shops. 
	* @PARAMS : $term and $shop_ids
	* @RETURN : json output
	*/  
    public function autocompleteOwnerShopJson($term,$shop_ids_string)
	{
		$output = array(0=>array('id'=>'','label'=>'No records found'));		
		
		if(!empty($term))
		{
			$model = new Shop;			
			$resp = $model->findAll(array('condition'=>'name like "%'.$_GET['term'].'%" and status="1" and active_status="S" and shop_id in ('.$shop_ids_string.')','select'=>'shop_id,name'));
			
			if(!empty($resp))
			{
				$i = 0;
				$output = array();		
				
			 	foreach($resp as $val)
				{
					$output[$i]['id'] = $val['shop_id'];
					$output[$i]['label'] = $val['name'];
					$i++;
				}
			}
		}		
		
		return CJSON::encode($output);	
	}
	
	/** Neha
	* @autocompleteUserJson : Common auto complete function for user's email 
	* @PARAMS : $term 
	* @RETURN : json output
	
	*/
	public function autocompleteUserJson($term)
	{
		$output = array();
		if(!empty($term))
		{
			// die($term);
			$model = new User;
			$resp = $model->findAll(array('condition'=>'email like "%'.$_GET['term'].'%" and status="1" and active_status="S"','select'=>'user_id,email'));
			// print_r($resp);exit;
			if(!empty($resp))
			{
				$i = 0;
				$output = array();
				
				foreach($resp as $val)
				{
					$output[$i]['id'] = $val['user_id'];
					$output[$i]['label'] = $val['email'];
					$i++;
				}
			}
		}
		return CJSON::encode($output);
	}
	    
    /**Amit
	* @beforeAction before every action. 
	* @PARAMS : $action 
	* @RETURN : Set public variable user_id with User ID stored in session.
	*/
	public function beforeAction($action) {
		
		$session_user_id = ApplicationSessions::run()->read('user_id');
		if($session_user_id){
			$this->user_id = $session_user_id;
		}
		
		return parent::beforeAction($action);
	}
	
	/**Amit
	* @loggedInStatus . 
	* @PARAMS : null 
	* @RETURN : Check logged-in user status at real time.
	*/
	public static function loggedInStatus()
	{
		$user_id = ApplicationSessions::run()->read('user_id');
		if(!empty($user_id))
		{
			$user = User::model()->find(array('condition'=>'user_id="'.$user_id.'" and active_status="S" and status="1"'));
			if(!empty($user)){
				return true;
			}else{
				Yii::app()->user->logout();
				return false;
			}	
		}else{
			return false;
		}	
	}
	
	/*@Garima 
	* @getUserGeolocation 
	* @RETURN : array() containing user's current location info by ip address 
	*/
	public function getUserGeolocation()
	{
		$user_location_det  = ApplicationSessions::run()->read('user_location_det');
		$return_arr = null;
		if(isset($user_location_det) && !empty($user_location_det))
		{		
			$return_arr = $user_location_det;
		}
		else
		{
			$cgeolocation  = isset(Yii::app()->request->cookies['geolocation']) ? Yii::app()->request->cookies['geolocation']->value : '';
			if(isset($cgeolocation) && $cgeolocation!='')
			{
				$geolocation = get_object_vars(json_decode($cgeolocation));
				if($geolocation['latitude']!='' && $geolocation['longitude']!='' && $geolocation['city']!='' && $geolocation['state']!='' )
				{
					$return_arr = $geolocation;
				}
				
			}
			else
			{		
				$obj = new GeoLocate;				
				$obj->geoLocate('getFromIpInfoDb');
				
				$return_arr = array(
							'latitude'  => $obj->latitude,
							'longitude' => $obj->longitude,
							'city'	    => $obj->city_name,
							'locality'  => $obj->locality,
							'state'	    => $obj->region_name,
							'country'   => $obj->country_name,
						);
				
				$cookiename = new CHttpCookie('geolocation', json_encode($return_arr));
				$cookiename->expire = time()+60*60*24*180; 
				Yii::app()->request->cookies["'".$cookiename."'"] = $cookiename;
			}			
			ApplicationSessions::run()->write('user_location_det', $return_arr);				
		}	
		
		return $return_arr;		
	}
	
	/*@Garima 
	* @formatedAddress 
	* @PARAMS : $shopname,$address,$zip_code,$city,$locality
	* @RETURN : formatted shop address.
	*/
	public static function formatedAddress($shopname,$address,$zip_code,$city=null,$locality=null)
	{
		$formatted_address = "";
		$plain_address = "";
		if($address!='')
		{
			$plain_address = str_replace(" ", "", $address);
			$plain_address = strtoupper(str_replace(",", "", $plain_address));
		}
		if($shopname!='')
		{
			$find_shopname = strtoupper(str_replace(" ", "", trim($shopname)));
		
			if (strpos($plain_address,$find_shopname) === false) {
				$formatted_address .= " ".$shopname.",<br> ";
			}
		}
		if($address!='')
		{
			$formatted_address .= " ".$address.",<br> ";
		}
		
		if($locality!='')
		{
			$find_locality = strtoupper(str_replace(" ", "", trim($locality)));
			
			if (strpos($plain_address,$find_locality) === false) {
				$formatted_address .= " ".$locality.",<br> ";
			}
		}
		
		if($city!='')
		{
			$find_city = strtoupper(str_replace(" ", "", trim($city)));
			
			if (strpos($plain_address,$find_city) === false) {
				$formatted_address .= " ".$city.",<br> ";
			}
		}
		
		if($zip_code!='')
		{
			$find_zip_code = str_replace(" ", "", trim($zip_code));
				
			if (strpos($plain_address,$find_zip_code) === false) {
				$formatted_address .= " Postal code - ".$zip_code;
			}else{
				$formatted_address = str_replace( $zip_code,"", $formatted_address)." Postal code - ".$zip_code;
			}
		}
		
		if($formatted_address!='')
		{
			$formatted_address .= ".";
			
		}		
		$formatted_address = str_replace(array(',.', ', .', ', ,','  '), array('.', '.', ',',' '), $formatted_address);
		$formatted_address = trim($formatted_address);
		return $formatted_address;	
	}
	/**Amit
	* @function to get user info. 
	* @PARAMS : $user_id 
	* @RETURN : user data.
	*/
	public function getUserInfo($user_id) 
	{
		$user = User::model()->findByAttributes(array('user_id'=>$user_id));
		return $user;
	}
	
	/**Amit
	* @function to get user image with img tag. 
	* @PARAMS : $user 
	* @RETURN : img tag.
	*/
	public function getUserImage($user,$height = 50, $width = 50) 
	{
		$img = '';
		if($user->profile_pic)
		{
			if($this->validate_url($user->profile_pic)){
				$img = '<img src="'.$user->profile_pic.'" alt="'.$user->name.'" title="'.$user->name.'" width="'.$width.'" height="'.$height.'" />';
			}else
			{
				
				if(file_exists(Yii::app()->basePath."/../upload/user/".$user->profile_pic))
				{
					$src = Yii::app()->baseUrl.'/upload/user/'.$user->profile_pic;
				}else{
					$src = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
				}
				$img = '<img src="'.$src.'" alt="'.$user->name.'" title="'.$user->name.'" width="'.$width.'" height="'.$height.'" />';
			}
		}else
		{
		   $img = '<img src="'.Yii::app()->baseUrl.'/upload/user/default.png" alt="'.$user->name.'" title="'.$user->name.'" width="'.$width.'" height="'.$height.'" />'; 
		}
		
		return $img;
		
	}
	
	/**Garima
	@setFlashMessage: To set flash messages
	@params: $value and $key ( default->pageMessage(on headerbreadcrumb) )
	*/	 
	public function setFlashMessage($value,$key = 'pageMessage')
	{
		Yii::app()->user->setFlash($key, $value);
		return true;
	}
	
	/**Neha
	@setActiveStatus: To activate and deactive multiple selected rows.
	@params: $model,$key_val,$admin_id_arr.
	*/	 
	public function setActiveStatus($model,$key_val,$id_arr,$pk_id,$flashmsg = false)
	{
		$return = false;
		$model  = new $model;
		
		if(is_array($id_arr) && !empty($id_arr))	
		{
			$id_arr =  implode(",",$id_arr);
			
			$chk_data = $model->findAll(array('condition'=>'active_status !="'.$key_val.'" and '.$pk_id.' in ('.$id_arr.')'));	
			$return = $model->updateAll(array('active_status'=>$key_val),$pk_id.' in ('.$id_arr.')');
			
			if($pk_id == 'shop_id')
			{
				
				$id_arr =  explode(",",$id_arr);
				foreach($id_arr as $id)
				{
					$shop_data[] = Controller::getIndexData($id);
				}	
			
				Yii::app()->shopSolr->updateMany($shop_data);
			}
			
			if($flashmsg)
			{
				if(empty($chk_data))
				{
					if($key_val=="S")
					{
						$msg = 'Selected rows already activated!!';
					}
					else if($key_val=="H")
					{
						$msg = 'Selected rows already deactivated!!';
					}
					else
					{
						$msg = 'Selected rows already deleted!!';
					}
				}
				else if($return)
				{
					$msg = 'Action performed successfully on selected rows!!';
				}
				else
				{
					$msg = 'An error occurred !! Please try again after reloading this page.';
				}
				
				$this->setFlashMessage($msg);
			}
			else
			{
				if(empty($chk_data))
				{
					if($key_val=="S")
					{
						$msg = 'Selected rows already activated!!';
					}
					else if($key_val=="H")
					{
						$msg = 'Selected rows already deactivated!!';
					}
					else
					{
						$msg = 'Selected rows already deleted!!';
					}
					
					$return = 'update';
					$this->setFlashMessage($msg);
				}
			}
		}		
		return $return;
	}
	/**Garima
     * @getBrowser Returns the user's browser/system details
     */
	public function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	
	}
	
	/*
	Garima
	* @getUserIP : to get user's ip address
	*/
	public function getUserIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		
		return $ip;
	}
	/*
	Garima
	* @isAccessAllowedFor : to get access right of single controller action for the user
	* @PARAM : controller name
	* @PARAM : controller action 
	* @RETURN : boolean value for acces right
	*/
	public function isAccessAllowedFor($controller = null,$action = null)
	{
		if(is_null($controller)) {
			$controller = Yii::app()->controller->id;
		}
		if(is_null($action)) {
			$action = Yii::app()->controller->action->id;
		}
		
		$key = strtoupper($controller);
		
		$accessible_actions = $this->getAccessRule($key);
				
		if(is_array($accessible_actions) && in_array($action,$accessible_actions))
		{
			return true;
		}else
		{
			return false;
		}
	}
	/*
	Garima
	* @getAccessRule : to get access right of controller for the user
	* @PARAM : controller name
	* @RETURN : array
	* used in the view files of all controllers to handle permission of view files menu's .
	*/
	public function getAccessRule($controller = null)
	{
		if(is_null($controller)) {
			$controller = Yii::app()->controller->id;
		}
		$key = strtoupper($controller);
		
		if(!empty(Yii::app()->controller->module->id))
		{
			$module = 	Yii::app()->controller->module->id;
		}
		else
		{
			$url = explode("/",$_SERVER['REQUEST_URI']);
			$module = 	$url[2];
		}
		
		if($module=='superadmin')
		{
			$access_permission = ApplicationSessions::run()->read('role_permission');
		}
		else
		{
			$access_permission = ApplicationSessions::run()->read('owner_role_permission');
		}
			
		
		if(is_array($access_permission) && array_key_exists($key,$access_permission))
		{
			return $access_permission[$key];
		}else
		{
			return false;
		}
	}
	/*
	Garima
	* @sendMail : common send mail functionality to be used throughout the project
	* @PARAM : subject,body,to_email,to_name,from_email,from_name,reply_to,attachment
	* @RETURN : boolean ( true or false ) after successful mail send
	*/
	public function sendMail($subject,$body,$to_email,$to_name,$from_email= null,$from_name = null,$attachment = null)
	{
		$content_type="text/html";
		$charset="UTF-8";
		$to_bcc = '';
		$session_var = ApplicationSessions::run()->read('user_email');
		// print_r($from_name);exit;
		if($from_email=='')
		{
			$from_email = (!empty($session_var))?$session_var:'neha.agrawal@sts.in';
		}
		if($from_name=='')
		{
			$from_name = 'Shopnext';
		}else{
			$from_name = $from_name;
		}
	  	$body = nl2br($body);
	    $headers = 	"From:$from_email" . "\r\n" .
	        		"Reply-To: $from_email" . "\r\n" .
	        		'X-Mailer: PHP/' . phpversion()."\r\n";
		$headers  .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		try
		{
			//mail($to_email, $subject, $body, $headers); 
	    	Yii::import('application.extensions.phpmailer.JPhpMailer');
	    	$mail = new JPhpMailer;
			$mail->IsSMTP();
			$mail->Mailer = "smtp";
			$mail->SMTPSecure = "ssl"; 
			$mail->Host='smtp.gmail.com';   
			$mail->Port='465';   
			$mail->SMTPAuth = true;
			$mail->Username = "rohan.kadam@sts.in";
			$mail->Password = "Donald@123";
			$mail->SetFrom($from_email,$from_name);
			if($to_bcc)
				$mail->AddBCC($to_bcc);
			if($attachment)
			{
				if(is_array($attachment))
				{
					foreach ($attachment as $key => $value) 
					{
						$mail->AddAttachment($value);
					}	
				}
				else
					$mail->AddAttachment($attachment);
			}
			$mail->Subject =$subject;
			$mail->MsgHTML($body);
			$mail->AddAddress($to_email,$to_name);
			$mail->Send();
			return true;
	  	}
		catch (phpmailerException $e)
		{
			//echo $e->errorMessage(); //Pretty error messages from PHPMailer
			return false;
		}
		catch (Exception $e) 
		{
			//echo $e->getMessage(); //Boring error messages from anything else!
			return false;
		}
	}
	
	// public function sendMail($subject,$body,$to_email,$to_name,$from_email = null,$from_name = null,$reply_to = null,$attachment = null)
	// {
		// $mailer_settings = Yii::app()->params['MAILER_SETTINGS'];	
		
		// if(empty($mailer_settings) || $mailer_settings['mailer']=='' || $mailer_settings['mailer_secure']=='' || $mailer_settings['mailer_host']=='' || $mailer_settings['mailer_port_number']=='' || $mailer_settings['mailer_auth']==''  || $mailer_settings['mailer_email_id']=='' || $mailer_settings['mailer_password']==''){
			// return false;
		// }
		
		// $content_type="text/html";
		// $charset="UTF-8";
		// $to_bcc = '';
		
		// if(empty($from_email) && !empty($mailer_settings['default_mailfrom_id'])){
			// $from_email = $mailer_settings['default_mailfrom_id'];
		// }
		// if(empty($from_name) && !empty($mailer_settings['default_mailfrom_name'])){
			// $from_name = $mailer_settings['default_mailfrom_name'];
		// }
		
		// if(empty($reply_to)){
			// $reply_to = $from_email;
		// }
		
	  	// $body = nl2br($body);
	    // $headers = 	"From:$from_email" . "\r\n" .
	        		// "Reply-To: $reply_to" . "\r\n" .
	        		// 'X-Mailer: PHP/' . phpversion()."\r\n";
		// $headers  .= 'MIME-Version: 1.0' . "\r\n";
		// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// try
		// {
			// //mail($to_email, $subject, $body, $headers); 
	    	// Yii::import('application.extensions.phpmailer.JPhpMailer');
	    	// $mail = new JPhpMailer;
			// $mail->IsSMTP();
			// $mail->Mailer 	  = $mailer_settings['mailer'];
			// $mail->SMTPSecure = $mailer_settings['mailer_secure'];
			// $mail->Host 	  = $mailer_settings['mailer_host'];   
			// $mail->Port 	  = $mailer_settings['mailer_port_number'];   
			// $mail->SMTPAuth   = $mailer_settings['mailer_auth']; 
			// $mail->Username   = $mailer_settings['mailer_email_id'];
			// $mail->Password   = $mailer_settings['mailer_password'];
			// $mail->SetFrom($from_email,$from_name);
			// if($to_bcc)
				// $mail->AddBCC($to_bcc);
			// if($attachment)
			// {
				// if(is_array($attachment))
				// {
					// foreach ($attachment as $key => $value) 
					// {
						// $mail->AddAttachment($value);
					// }	
				// }
				// else
					// $mail->AddAttachment($attachment);
			// }
			// $mail->Subject =$subject;
			// $mail->MsgHTML($body);
			// $mail->AddAddress($to_email,$to_name);
			// $mail->Send();
			// return true;
	  	// }
		// catch (phpmailerException $e)
		// {
			// //echo $e->errorMessage(); //Pretty error messages from PHPMailer
			// return false;
		// }
		// catch (Exception $e) 
		// {
			// //echo $e->getMessage(); //Boring error messages from anything else!
			// return false;
		// }
	// }
	/*
		Generic function for delete to change status
	*/
	public static function deleteMultiple($model_name,$pk_id_arr)
	{
		$model  = new $model_name;
		$id_arr =  implode(",",$pk_id_arr);
		$pk_id  = $model->tableSchema->primaryKey;
		
		$delete_all = $model->updateAll(array('status'=>'0'),'status="1" and '.$pk_id.' in ('.$id_arr.')');
		
		if($delete_all)
		{
			$model_name = strtoupper($model_name);
			switch ($model_name) {
				case 'SHOP':					
						$images_delete = ShopImage::model()->updateAll(array('status'=>'0'),'status="1" and shop_id in ('.$id_arr.')');					
						$comment_delete = Comment::model()->updateAll(array('status'=>'0'),'status="1" and shop_id in ('.$id_arr.')');					
						$deal_delete = Deal::model()->updateAll(array('status'=>'0'),'status="1" and shop_id in ('.$id_arr.')');					
						$ratings_delete = Rating::model()->updateAll(array('status'=>'0'),'status="1" and shop_id in ('.$id_arr.')');	
						$products = Product::model()->findAll(array('condition'=>'status="1" and shop_id in ('.$id_arr.')' ));
						if(!empty($products))
						{
							$products_in = implode(', ',array_map(function ($object) { return $object->product_id; },$products) );
							$product_delete = Product::model()->updateAll(array('status'=>'0'),'product_id in ('.$products_in.')');					
							$productimages_delete = ProductImage::model()->updateAll(array('status'=>'0'),'product_id in ('.$products_in.')');					
						}
						
						$id_arr =  explode(",",$id_arr);
						foreach($id_arr as $id)
						{
							$shop_data[] = Controller::getIndexData($id);
						}	
					
						Yii::app()->shopSolr->updateMany($shop_data);
				
						$return =  true;	
						
					break;
					
				case 'PRODUCT':
				
						$images_delete = ProductImage::model()->updateAll(array('status'=>'0'),'status="1" and product_id in ('.$id_arr.')');					
						$comment_delete = Comment::model()->updateAll(array('status'=>'0'),'status="1" and product_id in ('.$id_arr.') and deal_id is null');		
						$return =  true;
						
					break;
					
				case 'DEAL':
				
						$comment_delete = Comment::model()->updateAll(array('status'=>'0'),'status="1" and deal_id IN ('.$id_arr.') and product_id is null');			
						$return =  true;
						
					break;
					
				case 'BLOG':
				
						$comment_delete = BlogComment::model()->updateAll(array('status'=>'0'),'status="1" and blog_id IN ('.$id_arr.')');					
						$return =  true;
						
					break;
					
				default:
					$return =  true;
					break;
					
			}
		}
		if($delete_all && $return){
			return true;
		}else{
			return false;
		}
	}
	/* 
	Neha
		* get all category data from database.
	*/
	public function getCategory()
	{
		$model = new Category; 
		$resp = $model->findAll(array('condition'=>'active_status="S" and status=1'));
		return $resp;
				
	}
	/* 
	Neha
	* @PARAM : type , id
	* @RETURN : like table data
	*/
	public function getLikeStatus($type,$id)
	{
		$user_id = ApplicationSessions::run()->read('user_id');
		$model = new Likes;
		$resp = $model->find(array('condition'=>'user_id="'.$user_id.'" and '.$type.'_id='.$id));
		return $resp;
	}
	
	
	/*
	Rohan
	* @PARAM : geodata , searchtext
	* @RETURN : latest 10 deals
	*/
	public function getDeals($geodata,$offset=0,$limit=10,$searchtext = null)
	{
		$model = null;
		$user_search  = ApplicationSessions::run()->read('user_search');
		if(!empty($user_search['latitude']) && !empty($user_search['longitude']))
		{
			$lat = $user_search['latitude'];
			$lng = $user_search['longitude'];
		}
		elseif(is_array($geodata) && !empty($geodata)) {
			$lat = $geodata['latitude'];
			$lng = $geodata['longitude'];
		}
		if(is_array($geodata) && !empty($geodata))
		{	
			// $lat = $geodata['latitude'];
			// $lng = $geodata['longitude'];
			if( ($lat>0 || $lat<0) && ($lng>0 || $lng<0) ) 
			{
				$distance = 100;
				
				$criteria = new CDbCriteria();
				$criteria->select = "d.*,s.*,c.*, SQRT(
									POW(69.1 * (s.latitude - $lat), 2) +
									POW(69.1 * ($lng - s.longitude) * COS(s.latitude / 57.3), 2)) AS distance";
				$criteria->alias ='d';
				$criteria->join = "LEFT OUTER JOIN shop s ON d.shop_id=s.shop_id LEFT OUTER JOIN category c ON s.category_id=c.category_id";
				$criteria->condition = "d.status=1 AND d.active_status='S' AND d.start_date <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) and d.end_date >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())";
				
				if($searchtext!='')
				{
					$criteria->condition = "
											d.name like '%$searchtext%' OR 
											d.description like '%$searchtext%'								
										";
				}
				$criteria->having = "distance < $distance ";
				$criteria->order = "distance, deal_id DESC";
				$criteria->offset = $offset;
				$criteria->limit = $limit;
				$model = Deal::model()->findAll($criteria);
				
			}
		}
		if($model=='' || is_null($model) || empty($model))
		{
			$model = Deal::model()->findAll(array('condition'=>'status="1" and active_status="S"','offset' => $offset,'limit'=>$limit,'order'=>'deal_id desc'));
			// print_r(count($model));exit;
		}
		return $model;
	}
	public function getAllDeals()
	{
		$total = Deal::model()->findAll(array('condition'=>'active_status="S" and status=1 and end_date >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())'));
		$total = count($total);
		return $total;
	}
	public function getAllShops()
	{
		$total = Shop::model()->findAll(array('condition'=>'active_status="S" and status=1'));
		$total = count($total);
		return $total;
	}
	
	public static function getIndexData($id)
	{
		$shop_data = Yii::app()->db->createCommand()
		->select("s.shop_id as id, s.name, s.status, s.active_status, s.description, s.address, c.category, CONCAT(s.latitude,',',s.longitude) AS location, CONCAT(l.locality,', ',ci.city) AS locality, CONCAT(ci.city,', ',st.state) AS city,rating, i.image")
		->from('shop s')
		->join('category c','s.category_id = c.category_id')
		->leftjoin('locality l','s.locality_id = l.locality_id')
		->leftjoin('city ci','s.city_id = ci.city_id')
		->leftjoin('state st','s.state_id = st.state_id')
		->leftjoin('shop_image i','s.shop_image_id = i.shop_image_id')
		->where("s.shop_id = ".$id)
		->queryAll();
		
		if($shop_data[0]['location'] == ','){
			$shop_data[0]['location'] = '0.00,0.00';
		}
		
		$product_data = Product::model()->findAll(array('select'=>'product_id','condition'=>'shop_id='.$shop_data[0]["id"].' and active_status = "S" and status = "1"'));
		if(!empty($product_data))
		{
			foreach($product_data  as $val)
			{
				//$product['product'][]['product_id'] = $val['product_id'];
				$product['products'][] = $val['product_id'];
			}
			
			$shop_data[0] = array_merge($shop_data[0],$product);
		}
		
		
		return $shop_data[0];
	}
	
	public function getNearestShops($post = null,$offset=0,$limit=10)
	{
		$post = array_map(function($obj){ return trim(addslashes($obj)); },$post);
		$criteria = new CDbCriteria();
		$criteria->alias ='s';
		$criteria->join = "LEFT OUTER JOIN category c ON s.category_id = c.category_id";
		$criteria->join .= " LEFT OUTER JOIN locality l on l.locality_id = s.locality_id";
		$criteria->join .= " LEFT OUTER JOIN city ct on ct.city_id = s.city_id";
		$criteria->join .= " LEFT OUTER JOIN state st on st.state_id = s.state_id";
		$criteria->condition = "s.status=1 and s.active_status='S'";
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		if(!is_null($post) && is_array($post) && !empty($post))
		{	
			$lat 		 = (isset($post['latitude'])) ? $post['latitude'] : "";
			$lng 		 = (isset($post['longitude'])) ? $post['longitude'] : "";
			$searchtext  = (isset($post['search_input'])) ? $post['search_input'] : "";
			$location    = (isset($post['searchgeoloc_input'])) ? $post['searchgeoloc_input'] : "";
			$entity_id   = (isset($post['entity_id'])) ? $post['entity_id'] : "";
			$entity_type = (isset($post['entity_type'])) ? $post['entity_type'] : "";
			$category_id = (isset($post['category_id'])) ? $post['category_id'] : "";
			$location    = preg_replace('/[^A-Za-z0-9\-]/', '%', $location);
			$searchtext  = preg_replace('/[^A-Za-z0-9\-]/', '%', $searchtext);
			if($lat=='' || $lng=='')
			{
				$geodata = $this->getUserGeolocation();		
				$lat  = $geodata['latitude'];
				$lng  = $geodata['longitude'];
			}
			
			if($searchtext!='')
			{
				$criteria->condition .= " and( s.name like '%".$searchtext."%' 
										  OR c.category like '%".$searchtext."%') ";
			}
			if($category_id!='')
			{
				$criteria->condition .= " and s.category_id ='".$category_id."' ";
			}
			if($location!='')
			{
				$criteria->condition .= " OR ( s.address like '%".$location."%' 
										  OR l.locality like '%".$location."%'
										  OR ct.city like '%".$location."%'
										  OR st.state like '%".$location."%') ";
			}
			if($entity_type=='city' && $entity_id!='')
			{
				$criteria->condition .= " OR s.city_id='$entity_id'";
			}
			if($entity_type=='locality' && $entity_id!='')
			{
				$criteria->condition .= "OR s.locality_id='$entity_id'";
			}	
			//if lat-lng details are available->formulate the query accordingly
			if($lat!='' && $lng!='')
			{				
				$distance = 10;		
				$criteria1 = clone $criteria;//clone the common criteria n formulate for lat-lng and distance
				$criteria1->select = "s.*,SQRT(
										POW(69.1 * (s.latitude - $lat), 2) +
										POW(69.1 * ($lng - s.longitude) * COS(s.latitude / 57.3), 2)) AS distance";
				$criteria1->order  = "name, distance desc";	
				$criteria1->having = "distance < $distance ";
				$model = Shop::model()->findAll($criteria1);	
				if(empty($model))
				{
					$criteria2 = clone $criteria;
					$criteria2->having = "distance < ".$distance+10;//if not found within 10 distance -> increase 10+10				
					$model = Shop::model()->findAll($criteria2);
				}
				if(empty($model))
				{	
					$criteria3 = clone $criteria;	
					$criteria3->having = "distance < ".$distance+20;//if not found within 10+10 distance -> increase 10+20		
					$model = Shop::model()->findAll($criteria3);
				}
				if(empty($model))
				{
					$criteria4 = clone $criteria;
					$criteria4->having = "distance < ".$distance+100;//if not found within 10+20 distance -> increase 10+100		
					$model = Shop::model()->findAll($criteria4);
				}				
			}						
		}		
		if(!isset($model) || empty($model))
		{		
			$criteria->select = "s.*";			
			$criteria->order = "name";				
			$model = Shop::model()->findAll($criteria);
		}
		return $model;
	}
	
	public static function getCityIndexData($id)
	{
		$cities = Yii::app()->db->createCommand()
			->select("c.city_id as id,concat(c.city,', ',s.state) as name,c.latitude,c.longitude,c.status,c.active_status,'city' as type")
			->from('city c')
			->join('state s','s.state_id=c.state_id')
			->where('c.city_id ='.$id)
			->queryAll();
			
		$city = $cities[0];	
		
		$city['id'] = 'city_'.$city['id'];	
		if(!empty($city['latitude']) && !empty($city['longitude']))
		{
			$cordinates = $city['latitude'].','.$city['longitude'];
		}else{
			$cordinates = '0.00,0.00';
		}
		$city['location'] = $cordinates;
		
		return $city;
		
	}
	
	public static function getLocalityIndexData($id)
	{
		$localities = Yii::app()->db->createCommand()
			->select("l.locality_id as id,concat(l.locality,', ',c.city,', ',s.state) as name,l.latitude,l.longitude,l.status,l.active_status,'locality' as type")
			->from('locality l')
			->join('city c','l.city_id=c.city_id')
			->join('state s','s.state_id=c.state_id')
			->where('l.locality_id ='.$id)
			->queryAll();
			
		$locality = $localities[0];	
		
		$locality['id'] = 'locality_'.$locality['id'];	
		if(!empty($locality['latitude']) && !empty($locality['longitude']))
		{
			$cordinates = $locality['latitude'].','.$locality['longitude'];
		}else{
			$cordinates = '0.00,0.00';
		}
		$locality['location'] = $cordinates;
		
		return $locality;
		
	}
	
	public static function getLocalionSearchIndexData($id)
	{
		$location_search = Yii::app()->db->createCommand()
				->select('id,geo_location as name,latitude,longitude,status,active_status, id as type')
				->from('location_search')
				->order('name')
				->where('id ='.$id)
				->queryAll();
		
			
		$locality = $location_search[0];	
		
		$locality['id'] = 'search_'.$locality['id'];	
		if(!empty($locality['latitude']) && !empty($locality['longitude']))
		{
			$cordinates = $locality['latitude'].','.$locality['longitude'];
		}else{
			$cordinates = '0.00,0.00';
		}
		$locality['location'] = $cordinates;
		$locality['type'] = 'search';
		return $locality;
		
	}
	
	public function generateOrderId()
	{
		$order_id = ApplicationSessions::run()->read('order_id');
		
		if(!empty($order_id))
		{
			return $order_id;
		}
		else
		{
			$order_id = rand(100000,999999);
			
			if($this->chkOrderId($order_id))
			{
				ApplicationSessions::run()->write('order_id',$order_id);
				return $order_id;
			}
			else
			{
				$this->generateOrderId();
			}
		}
	}
	
	public function chkOrderId($order_id)
	{
		$order_data = Order::model()->find(array('condition'=>'order_id='.$order_id));
		
		if(empty($order_data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	 public static function formatShortText($value, $length)
	 {
		$string_length = strlen($value);
		
		if($string_length>$length)
		{
			$count_length = substr($value , 0, $length).'...';
		}
		else
		{
			$count_length = substr($value, 0, $length);
		}
		return $count_length;
	}
	
	/*
	Rohan
	* @PARAM : geodata 
	* @RETURN : latest deals count for deal notification 
	*/
	public function getDealsNotification($geodata)
	{
		$model = null;
		$user = new User;
		$user_id = ApplicationSessions::run()->read('user_id');
		$user_data = $user->findByPk($user_id);
		
		if(!empty($user_data) && !empty($user_data->last_login))
		{
			$time = $user_data->last_login;
			$user->updateByPk($user_id,array('last_login'=>time()));
		}
		else
		{
			$time = 0;
			$user->updateByPk($user_id,array('last_login'=>time()));
		}
		
		if(is_array($geodata) && !empty($geodata))
		{
			
			$lat = $geodata['latitude'];
			$lng = $geodata['longitude'];
			if( ($lat>0 || $lat<0) && ($lng>0 || $lng<0) ) 
			{
				$distance = 100;
				
				$criteria = new CDbCriteria();
				$criteria->select = "SQRT(
									POW(69.1 * (s.latitude - $lat), 2) +
									POW(69.1 * ($lng - s.longitude) * COS(s.latitude / 57.3), 2)) AS distance";
				$criteria->alias ='d';
				$criteria->join = "LEFT OUTER JOIN shop s ON d.shop_id=s.shop_id LEFT OUTER JOIN category c ON s.category_id=c.category_id";
				$criteria->condition = "d.added_on >=".$time." AND d.status=1 AND d.active_status='S' AND d.end_date >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())";
				$criteria->having = "distance < $distance ";
				$criteria->order = "distance, deal_id DESC";
				$model = Deal::model()->count($criteria);
				
			}
		}
		if($model=='' || is_null($model) || empty($model))
		{
			$model = Deal::model()->count(array('condition'=>'status=1 AND added_on >= '.$time,'order'=>'deal_id desc'));
			// print_r(count($model));exit;
		}
		
		ApplicationSessions::run()->write('deal_notification',$model);
		
		return true;
	}
}