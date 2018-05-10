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

	$PluginSIG= new PluginComproveedoresIntegratedmanagementsystem();
	$PluginLOSS_RATIO= new PluginComproveedoresLossratio();
	
	if(isset($_POST['add'])){

		$PluginSIG->check(-1, CREATE, $_POST);
		$newID = $PluginSIG->add($_POST);
	
		//comprobamos si existe o no, los años de sinisestralidad, y en funcion de que exista o no, añadimos o modificamos
		for($i=0; $i<3; $i++){
			$query ="SELECT * FROM glpi_plugin_comproveedores_lossratios WHERE cv_id=".$_POST['cv_id']." and anio=".$_POST['anio'.$i]."-00-00";

			$result = $DB->query($query);

			if($result->num_rows!=0){

				while ($data=$DB->fetch_array($result)) {
					
					$data['anio']=$_POST['anio'.$i]."-00-00";
					$data['incidencia']=$_POST['incidencia'.$i];
					$data['frecuencia']=$_POST['frecuencia'.$i];
					$data['gravedad']=$_POST['gravedad'.$i];

					var_dump($data);
					$PluginLOSS_RATIO->check($data['id'], UPDATE);
					$PluginLOSS_RATIO->update($data);
				}
			}
			else{

				$data['anio']=$_POST['anio'.$i]."-00-00";
				$data['incidencia']=$_POST['incidencia'.$i];
				$data['frecuencia']=$_POST['frecuencia'.$i];
				$data['gravedad']=$_POST['gravedad'.$i];

				$PluginLOSS_RATIO->check(-1, CREATE, $data);
				$PluginLOSS_RATIO->add($data);
			}
		}

		
		if($_SESSION['glpibackcreated']) {
			Html::redirect($PluginSIG->getFormURL()."?id=".$newID);
		}

		Html::back();
	} else if(isset($_POST['update'])){
		
		$PluginSIG->check($_POST['id'], UPDATE);
		$PluginSIG->update($_POST);

		//comprobamos si existe o no, los años de sinisestralidad, y en funcion de que exista o no, añadimos o modificamos
		for($i=0; $i<3; $i++){
			$query ="SELECT * FROM glpi_plugin_comproveedores_lossratios WHERE cv_id=".$_POST['cv_id']." and anio=".$_POST['anio'.$i]."-00-00";

			$result = $DB->query($query);

			if($result->num_rows!=0){

				while ($data=$DB->fetch_array($result)) {
					
					$data['anio']=$_POST['anio'.$i]."-00-00";
					$data['incidencia']=$_POST['incidencia'.$i];
					$data['frecuencia']=$_POST['frecuencia'.$i];
					$data['gravedad']=$_POST['gravedad'.$i];

					var_dump($data);
					$PluginLOSS_RATIO->check($data['id'], UPDATE);
					$PluginLOSS_RATIO->update($data);
				}
			}
			else{

				$data['anio']=$_POST['anio'.$i]."-00-00";
				$data['incidencia']=$_POST['incidencia'.$i];
				$data['frecuencia']=$_POST['frecuencia'.$i];
				$data['gravedad']=$_POST['gravedad'.$i];

				$PluginLOSS_RATIO->check(-1, CREATE, $data);
				$PluginLOSS_RATIO->add($data);
			}
		}
		
		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginSIG->check($_POST['id'], DELETE);
		$PluginSIG->delete($_POST);
		//Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");
		Html::back();

	} else if (isset($_POST["restore"])) {
		$PluginSIG->check($_POST['id'], PURGE);
		$PluginSIG->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginSIG->check($_POST['id'], PURGE);
		$PluginSIG->delete($_POST, 1);
		
		Html::back();

	} else {
		$PluginSIG->checkGlobal(READ);


		/*//////////////////////////////////////////////////////////
		// MUESTRA LA CABECERA DE LA PAGINA DEL . FROM EXPERIENCIAS
		//////////////////////////////////////////////////////////*/

		$plugin = new Plugin();
		if ($plugin->isActivated("environment")) {
			Html::header(PluginComproveedoresIntegratedmanagementsystem::getTypeName(2),
				'',"management","pluginenvironmentdisplay","comproveedores");
		} else {
			Html::header(PluginComproveedoresIntegratedmanagementsystem::getTypeName(2), '', "management",
				"plugincomproveedorescvmenu");	
		}

		/*//////////////////////////////////////////////////////////
		// MUESTRA EL REGISTRO CORRESPONDIENTE AL ID SI SE LE MANDA
		//	O LA LISTA DE TODOS LOS REGISTROS SI NO SE LE PASA EL PARAMETRO ID
		//////////////////////////////////////////////////////////*/

		if(empty($_GET['id'])){
			Search::show('PluginComproveedoresIntegratedmanagementsystem');
		}else{			
			$options['id']=$_GET['id'];
			$PluginSIG->display($options);
		}

		Html::footer();
	} 
	?>