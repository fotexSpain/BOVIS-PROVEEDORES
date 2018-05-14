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
	$PluginFeaturedCompany= new PluginComproveedoresFeaturedcompany();
	

	if(isset($_POST['add'])){
		$PluginComproveedores->check(-1, CREATE, $_POST);
		if($newID = $PluginComproveedores->add($_POST)){
			
			$query="UPDATE glpi_suppliers SET cv_id=$newID WHERE id=$_POST[supplier_id]";
			$DB->query($query);

			$supplier=new Supplier();
			$_POST['id']=$_POST['supplier_id'];
			
			//$supplier->check($_POST['supplier_id'],UPDATE);
			$supplier->update($_POST);

			//Empresas destacadas
			InsertAndUpdateFeaturedCompany($DB, $PluginFeaturedCompany, $newID);

			if($_SESSION['glpibackcreated']) {
				Html::redirect($PluginComproveedores->getFormURL()."?id=".$newID);
			}
		};		 
		Html::back();
	} else if(isset($_POST['update'])){

		$PluginComproveedores->check($_POST['id'], UPDATE);
		$PluginComproveedores->update($_POST);

		//Empresas destacadas
		InsertAndUpdateFeaturedCompany($DB, $PluginFeaturedCompany, $_POST['id']);

		$supplier=new Supplier();
		
		$_POST['id']=$_POST['supplier_id'];


		$supplier->check($_POST['id'],UPDATE);
		$supplier->update($_POST);
		
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

		//Eliminamos el cv_id de la tabla glpi_ssupliers
		$query ="UPDATE glpi_suppliers SET cv_id = NULL WHERE glpi_suppliers.id =".$_POST['supplier_id'];

		$DB->query($query);

		//Elmiminar Empresas destacadas
		$query2 ="SELECT id FROM glpi_plugin_comproveedores_featuredcompanies WHERE cv_id=".$_POST['id'];

		$result2 = $DB->query($query2);

		if($result2->num_rows!=0){

			while ($data=$DB->fetch_array($result2)) {
				$PluginFeaturedCompany->check($data['id'], PURGE);
				$PluginFeaturedCompany->delete($data, 1);
			}
		}
		/////

		$query="UPDATE glpi_suppliers SET cv_id= null WHERE id=$_POST[supplier_id];
		DELETE  FROM `glpi_plugin_comproveedores_experiences`  WHERE cv_id=$_POST[id];
		DELETE  FROM `glpi_plugin_comproveedores_listspecialties`  WHERE cv_id=$_POST[id];
		DELETE  FROM `glpi_plugin_comproveedores_empleados`  WHERE cv_id=$_POST[id];";
		$DB->query($query);

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
	function InsertAndUpdateFeaturedCompany($DB, $PluginFeaturedCompany, $cv_id){

		for($i=1; $i<=6; $i++){
			$query ="SELECT * FROM glpi_plugin_comproveedores_featuredcompanies WHERE cv_id=".$cv_id." and puesto=".$i;

			$result = $DB->query($query);

			if($result->num_rows!=0){

				while ($data=$DB->fetch_array($result)) {
					
					$data['nombre_empresa_destacada']=$_POST['nombre_empresa_destacada'.$i];
					$data['puesto']=$i;
					$data['cv_id']=$_POST['id'];

					$PluginFeaturedCompany->check($data['id'], UPDATE);
					$PluginFeaturedCompany->update($data);
				}
			}
			else{

				$data['nombre_empresa_destacada']=$_POST['nombre_empresa_destacada'.$i];
				$data['puesto']=$i;
				$data['cv_id']=$cv_id;

				$PluginFeaturedCompany->check(-1, CREATE, $data);
				$PluginFeaturedCompany->add($data);
			}
		}
	}




?>