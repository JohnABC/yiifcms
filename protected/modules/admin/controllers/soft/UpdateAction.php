<?php
/**
 * 软件编辑
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2015. All rights reserved.
 */

class UpdateAction extends CAction
{	
	public function run($id){
		$model = Soft::model()->findByPk($id);    	
    	if(isset($_POST['Soft']))
    	{
    		$model->attributes=$_POST['Soft'];
            
    		//封面、图标、 文件
    		$model->cover_image = isset($_POST['cover_image']) ? $_POST['cover_image'] : '';
            $model->soft_icon = isset($_POST['soft_icon']) ? $_POST['soft_icon'] : ''; 
            $model->soft_file = isset($_POST['soft_file']) ? $_POST['soft_file'] : ''; 
    			  	
    		//摘要
    		$model->introduce = trim($_POST['Soft']['introduce'])?$_POST['Soft']['introduce']:Helper::truncate_utf8_string(preg_replace('/\s+/',' ',$_POST['Soft']['content']), 200);    		
    		
    		$model->update_time = time();    		
    		if($model->save()){    			
    			$this->controller->message('success',Yii::t('admin','Update Success'),$this->controller->createUrl('index'));
    		}
    	}        
    	$this->controller->render('update',array(
    			'model'=>$model   			
    	));    	
	}
}