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

	$PluginExperience= new PluginComproveedoresExperience();

	
	if(isset($_POST['add'])){
		$PluginExperience->check(-1, CREATE, $_POST);
		$newID = $PluginExperience->add($_POST);
	
		if($_SESSION['glpibackcreated']) {
			Html::redirect($PluginExperience->getFormURL()."?id=".$newID);
		}

		Html::back();
	} else if(isset($_POST['update'])){
		$PluginExperience->check($_POST['id'], UPDATE);
		$PluginExperience->update($_POST);

		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginExperience->check($_POST['id'], DELETE);
		$PluginExperience->delete($_POST);
		Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php");

	} else if (isset($_POST["restore"])) {
		$PluginExperience->check($_POST['id'], PURGE);
		$PluginExperience->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginExperience->check($_POST['id'], PURGE);
		$PluginExperience->delete($_POST, 1);

		Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php");

	} else {
		$PluginExperience->checkGlobal(READ);


		/*//////////////////////////////////////////////////////////
		// MUESTRA LA CABECERA DE LA PAGINA DEL . FROM EXPERIENCIAS
		//////////////////////////////////////////////////////////*/

		$plugin = new Plugin();
		if ($plugin->isActivated("environment")) {
			Html::header(PluginComproveedoresExperience::getTypeName(2),
				'',"management","pluginenvironmentdisplay","comproveedores");
		} else {
			Html::header(PluginComproveedoresExperience::getTypeName(2), '', "management",
				"plugincomproveedorescvmenu");	
		}

		/*//////////////////////////////////////////////////////////
		// MUESTRA EL REGISTRO CORRESPONDIENTE AL ID SI SE LE MANDA
		//	O LA LISTA DE TODOS LOS REGISTROS SI NO SE LE PASA EL PARAMETRO ID
		//////////////////////////////////////////////////////////*/

		if(empty($_GET['id'])){
			Search::show('PluginComproveedoresExperience');
		}else{			
			$options['id']=$_GET['id'];
			$PluginExperience->display($options);
		}

		Html::footer();
	} 
	?>