<?php
/**
 * 
 * 后端控制类  所有控制器需要验证
 * @author GoldHan.zhao <326196998@qq.com>
 * @copyright Copyright (c) 2014-2016 Personal. All rights reserved.
 * @version v1.5
 * 
 */
class Backend extends BackendBase
{	
    public $model;
    public $_catalog;
	public function init(){		
		parent::init();			
		parent::auth();
	}
	/**
	 * 所有后台权限控制
	 * 
	 * @param key Controller Name
	 * @pamra value Action Name
	 * 
	 * @return array
	 */
	public function acl(){
		$acl = array(			
			'setting' => array('seo','upload','cache', 'template','email', 'access','custom'), 		//网站设置
			'catalog' => array('index','create','update','delete','batch'),                         //栏目管理
			'menu' => array('index','create','update','delete','batch'),                            //导航管理
			'special' => array('index','create','update','delete','batch'),                         //专题管理
			'post' => array('index','create','update','delete','batch'),                            //文章管理	
			'image' => array('index','create','update','delete','batch'),                           //图集管理
			'soft' => array('index','create','update','delete','batch'),                            //软件管理
			'video' => array('index','create','update','delete','batch'),                           //视频管理			
			'page' => array('index','create','update','delete','batch'),                            //单页管理
			'comment' => array('index','update','batch'),   				                        //评论管理
			'reply' => array('index','update','batch'),   					                        //回复管理
			'tag' => array('index','reset'),   					                                    //标签管理
			'recommendPosition' => array('index','create','update','delete', 'view','batch'),       //推荐位管理
			'user' => array('index','create','update','delete','batch'), 	                        //用户管理
            'usergroup' => array('index','create','update'), 	                                    //用户组管理
			'question' => array('index','update','batch'), 					                        //留言管理
			'link' => array('index','create','update','delete','batch'), 	                        //链接管理
			'adPosition' => array('index','create','update','delete','batch'),                      //广告位管理
			'ad' => array('index','create','update','delete','batch'), 		                        //广告管理				
			'modeltype' => array('index','create','update','batch'),                                //内容模型管理
			'database' => array('index','query','doQuery','execute','export', 'database','operate','installtestdata'), 		//数据库管理
			'cache' => array('index','cacheUpdate'),                                                //缓存管理
			'maillog' => array('index','batch'),                                                    //邮件日志管理
			'oAuth' => array('index','update','batch'),                                             //第三方登录管理
		
		);
		return $acl;
	}
    
    /**
	 * 动作权限控制
     * 
     * @see CController::beforeAction()
	 */
	public function beforeAction($action){
		$controller = $this->id;        
		$action_id  = $action->id;
		if(!$this->checkAcl($controller.'/'.$action_id)){
			$this->message('error',Yii::t('common','Access Deny'),$this->createUrl('index'),'',true);
			return false;
		}
		return true;
	}
	
	/**
	 * 校验权限
	 * 
	 * @param string $acl
	 * @return boolean
	 */
	public function checkAcl($acl=''){
		$bool = false;
		$groupid = Yii::app()->user->groupid;
		if($groupid && $acl){
			$group = UserGroup::model()->findByPk($groupid);			
			if($group->acl){
				if($groupid == User::AdminGroupID && $group->acl == 'Administrator'){
					$bool = true;
				}else {
					$acl  = str_replace('/', '|', $acl);
                    $bool = strstr($group->acl, $acl) ? true: false;					
				}			
			}
		}
		return $bool;		
	}
}
