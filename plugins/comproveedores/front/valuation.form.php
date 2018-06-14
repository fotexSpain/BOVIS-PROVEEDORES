<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	use Glpi\Event;

	include ("../../../inc/includes.php");
	global $DB;
	Session::checkLoginUser();
	
	if(!isset($_GET["id"])) {
		$_GET["id"] = "";
	}
	if (!isset($_GET["withtemplate"])) {
		$_GET["withtemplate"] = "";
	}

	$PluginValuation= new PluginComproveedoresValuation();

	
	if(isset($_POST['add'])){
		$PluginValuation->check(-1, CREATE, $_POST);
		$newID = $PluginValuation->add($_POST);
	
		if($_SESSION['glpibackcreated']) {
			Html::redirect($PluginValuation->getFormURL()."?id=".$newID);
		}

		Html::back();
	} else if(isset($_POST['update'])){
		$PluginValuation->check($_POST['id'], UPDATE);
		$PluginValuation->update($_POST);

		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginValuation->check($_POST['id'], DELETE);
		$PluginValuation->delete($_POST);
		//Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");
		Html::back();

	} else if (isset($_POST["restore"])) {
		$PluginValuation->check($_POST['id'], PURGE);
		$PluginValuation->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginValuation->check($_POST['id'], PURGE);
		$PluginValuation->delete($_POST, 1);
		
		Html::back();

	}else if(isset($_GET['guardar_valoracion'])){
            
                         
            var_dump($_GET);
		/*$PluginValuation->check($_POST['id'], UPDATE);
		$PluginValuation->update($_POST);

		Html::back();*/
	}  else {
		$PluginValuation->checkGlobal(READ);

		$plugin = new Plugin();
		if ($plugin->isActivated("environment")) {
			Html::header(PluginComproveedoresValuation::getTypeName(2),
				'',"management","pluginenvironmentdisplay","comproveedores");
		} else {
			Html::header(PluginComproveedoresValuation::getTypeName(2), '', "management",
				"plugincomproveedorescvmenu");	
		}

		if(empty($_GET['id'])){
			Search::show('PluginComproveedoresValuation');
		}else{			
			$options['id']=$_GET['id'];
			$PluginValuation->display($options);
		}

		Html::footer();
	} 
	?>