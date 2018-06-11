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

	$PluginSelectionSupplier= new PluginComproveedoresSelectionSupplier();

	
	if(isset($_POST['add'])){
		$PluginSelectionSupplier->check(-1, CREATE, $_POST);
		$newID = $PluginSelectionSupplier->add($_POST);
	
		if($_SESSION['glpibackcreated']) {
			Html::redirect($PluginSelectionSupplier->getFormURL()."?id=".$newID);
		}

		Html::back();
	} else if(isset($_POST['update'])){
		$PluginSelectionSupplier->check($_POST['id'], UPDATE);
		$PluginSelectionSupplier->update($_POST);

		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginSelectionSupplier->check($_POST['id'], DELETE);
		$PluginSelectionSupplier->delete($_POST);
		//Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");
		Html::back();

	} else if (isset($_POST["restore"])) {
		$PluginSelectionSupplier->check($_POST['id'], PURGE);
		$PluginSelectionSupplier->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginSelectionSupplier->check($_POST['id'], PURGE);
		$PluginSelectionSupplier->delete($_POST, 1);
		
		Html::back();

	} else {
		$PluginSelectionSupplier->checkGlobal(READ);

		$plugin = new Plugin();
		if ($plugin->isActivated("environment")) {
			Html::header(PluginComproveedoresSelectionSupplier::getTypeName(2),
				'',"management","pluginenvironmentdisplay","comproveedores");
		} else {
			Html::header(PluginComproveedoresSelectionSupplier::getTypeName(2), '', "management",
				"plugincomproveedorescvmenu");	
		}

		if(empty($_GET['id'])){
			Search::show('PluginComproveedoresSelectionSupplier');
		}else{			
			$options['id']=$_GET['id'];
			$PluginSelectionSupplier->display($options);
		}

		Html::footer();
	} 
	?>