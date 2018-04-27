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

	$PluginInsurence= new PluginComproveedoresInsurence();

	
	if(isset($_POST['add'])){
		$PluginInsurence->check(-1, CREATE, $_POST);
		$newID = $PluginInsurence->add($_POST);
	
		if($_SESSION['glpibackcreated']) {
			Html::redirect($PluginInsurence->getFormURL()."?id=".$newID);
		}

		Html::back();
	} else if(isset($_POST['update'])){
		$PluginInsurence->check($_POST['id'], UPDATE);
		$PluginInsurence->update($_POST);

		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginInsurence->check($_POST['id'], DELETE);
		$PluginInsurence->delete($_POST);
		//Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");
		Html::back();

	} else if (isset($_POST["restore"])) {
		$PluginInsurence->check($_POST['id'], PURGE);
		$PluginInsurence->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginInsurence->check($_POST['id'], PURGE);
		$PluginInsurence->delete($_POST, 1);
		
		Html::back();

	} else {
		$PluginExperience->checkGlobal(READ);


		/*//////////////////////////////////////////////////////////
		// MUESTRA LA CABECERA DE LA PAGINA DEL . FROM EXPERIENCIAS
		//////////////////////////////////////////////////////////*/

		$plugin = new Plugin();
		if ($plugin->isActivated("environment")) {
			Html::header(PluginComproveedoresInsurence::getTypeName(2),
				'',"management","pluginenvironmentdisplay","comproveedores");
		} else {
			Html::header(PluginComproveedoresInsurence::getTypeName(2), '', "management",
				"plugincomproveedorescvmenu");	
		}

		/*//////////////////////////////////////////////////////////
		// MUESTRA EL REGISTRO CORRESPONDIENTE AL ID SI SE LE MANDA
		//	O LA LISTA DE TODOS LOS REGISTROS SI NO SE LE PASA EL PARAMETRO ID
		//////////////////////////////////////////////////////////*/

		if(empty($_GET['id'])){
			Search::show('PluginComproveedoresInsurence');
		}else{			
			$options['id']=$_GET['id'];
			$PluginInsurence->display($options);
		}

		Html::footer();
	} 
	?>