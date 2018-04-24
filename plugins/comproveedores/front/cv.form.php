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

	$PluginComproveedores= new PluginComproveedoresCv();


	if(isset($_POST['add'])){
		$PluginComproveedores->check(-1, CREATE, $_POST);
		if($newID = $PluginComproveedores->add($_POST)){
				

			$query="UPDATE glpi_suppliers SET cv_id=$newID WHERE id=$_POST[supplier_id]";
			$DB->query($query);

			if($_SESSION['glpibackcreated']) {
				Html::redirect($PluginComproveedores->getFormURL()."?id=".$newID);
			}
		};		 
		Html::back();
	} else if(isset($_POST['update'])){
		$PluginComproveedores->check($_POST['id'], UPDATE);
		$PluginComproveedores->update($_POST);
		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginComproveedores->check($_POST['id'], DELETE);
		$PluginComproveedores->delete($_POST);
		Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");

	} else if (isset($_POST["restore"])) {
		$PluginComproveedores->check($_POST['id'], PURGE);
		$PluginComproveedores->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginComproveedores->check($_POST['id'], PURGE);
		$PluginComproveedores->delete($_POST, 1);

		Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");

	} else if (isset($_POST["addToSupplier"])){

		$supplier_id=$_POST['supplier_id'];
		$user_id=$_POST['user_id'];

		
		$query ="UPDATE `glpi_users` SET supplier_id=$supplier_id WHERE id=$user_id";
		$DB->query($query);
		Html::back();

	}else if (isset($_POST["delUser"])){

		$supplier_id=0;
		$user_id=$_POST['users_id'];
		
		$query ="UPDATE `glpi_users` SET supplier_id=$supplier_id WHERE id=$user_id";
		$DB->query($query);
		Html::back();

	}else{




		$PluginComproveedores->checkGlobal(READ);
		
		//check environment meta-plugin installtion for change header
		$plugin = new Plugin();
		if ($plugin->isActivated("environment")) {
			Html::header(PluginComproveedoresCv::getTypeName(2),
				'',"management","pluginenvironmentdisplay","comproveedores");
		} else {
			Html::header(PluginComproveedoresCv::getTypeName(2), '', "management",
				"plugincomproveedorescvmenu");
		}

		$user_Id=$_SESSION['glpiID'];
		$options = array();


		$query ="SELECT cvs.id as cv FROM glpi_plugin_comproveedores_cvs cvs INNER JOIN glpi_users u ON u.supplier_id=cvs.supplier_id WHERE u.id=$user_Id";

		$result=$DB->query($query);
		$id=$DB->fetch_array($result);

		if($id['cv']<>''){
			$options['id']=$id['cv'];
		}

		$loggedUser = $PluginComproveedores->getSupplierByUserID($user_Id);	

		if($loggedUser<>0){
			$PluginComproveedores->display($options);
			//echo"distinto de 0";
			//exit();
		}else{	
			$profileUser = $PluginComproveedores->getProfileByUserID($user_Id);
			if($profileUser!=9){
				if(empty($_GET['id'])){
					//echo"distinto de proveedor";
					//exit();
					Search::show('PluginComproveedoresCv');
				}else{
					
					$options['id']=$_GET['id'];
					$PluginComproveedores->display($options);
				}
				
			}else{
				echo"Contacte con su administrador...";
			}	
		}
		

		Html::footer();
	} 
	?>