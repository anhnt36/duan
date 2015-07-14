<?php
class FT_Model_Loader{
	public function load($model){
		if(!file_exists(PATH_APPLICATION.'/models/'.$model.'_Model.php')){
			die('Model not found !!!');
		}
		require_once PATH_APPLICATION.'/models/'.$model.'_Model.php';
	}
	public function act($id,$activate) {
		$query= self::$pdo->query("UPDATE ".self::$_table." SET activate={$activate} where id='{$id}'");
	}
	
}