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

		//Comprobar si la variable es null, y si lo es eliminarla para que se guarde null en la base de datos
		/*$cambiarValor=array('bim', 'breeam', 'leed', 'otros_certificados', 'cpd_tier');

		foreach ($cambiarValor as $key => $value) {

			if($_POST[$value]==null){
				unset($_POST[$value]);
			}
			
		}*/

		//quitar los puntos y sustituir la coma por un punto para que el valor se guarde correctamente
		$_POST['importe']= str_replace(".", "", $_POST['importe']);
		$_POST['importe']= str_replace(",", ".", $_POST['importe']);
		
		//si a intervenido bovis el tipo de experiencia sera 0
		if($_POST['intervencion_bovis']==1){
			$_POST['plugin_comproveedores_experiencestypes_id']=0;
		}
		$PluginExperience->check(-1, CREATE, $_POST);
		$newID = $PluginExperience->add($_POST);
	
		if($_SESSION['glpibackcreated']) {
			Html::redirect($PluginExperience->getFormURL()."?id=".$newID);
		}

		Html::back();
	} else if(isset($_POST['update'])){

		//Comprobar si la variable es null, y si lo es eliminarla para que se guarde null en la base de datos
		/*$cambiarValor=array('bim', 'breeam', 'leed', 'otros_certificados', 'cpd_tier');

		foreach ($cambiarValor as $key => $value) {

			if($_POST[$value]==null){
				unset($_POST[$value]);
			}
			
		}*/

		//quitar los puntos y sustituir la coma por un punto para que el valor se guarde correctamente
		$_POST['importe']= str_replace(".", "", $_POST['importe']);
		$_POST['importe']= str_replace(",", ".", $_POST['importe']);

		//si a intervenido bovis el tipo de experiencia sera 0
		if($_POST['intervencion_bovis']==1){
			$_POST['plugin_comproveedores_experiencestypes_id']=0;
		}
		$PluginExperience->check($_POST['id'], UPDATE);
		$PluginExperience->update($_POST);

		Html::back();
	} else if (isset($_POST["delete"])) {
		$_POST['fecha_fin']=date('Y-m-d H:i:s');
		$PluginExperience->check($_POST['id'], DELETE);
		$PluginExperience->delete($_POST);
		//Html::redirect($CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php");
		Html::back();

	} else if (isset($_POST["restore"])) {
		$PluginExperience->check($_POST['id'], PURGE);
		$PluginExperience->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginExperience->check($_POST['id'], PURGE);
		$PluginExperience->delete($_POST, 1);
		
		Html::back();

	}else if(isset($_GET['addNoDelete'])){

		//Comprobar si la variable es null, y si lo es eliminarla para que se guarde null en la base de datos
		/*$cambiarValor=array('bim', 'breeam', 'leed', 'otros_certificados', 'cpd_tier');

		foreach ($cambiarValor as $key => $value) {

			if($_GET[$value]==null){
				unset($_GET[$value]);
			}
			
		}*/

		//quitar los puntos y sustituir la coma por un punto para que el valor se guarde correctamente
		$_GET['importe']= str_replace(".", "", $_GET['importe']);
		$_GET['importe']= str_replace(",", ".", $_GET['importe']);

		//si a intervenido bovis el tipo de experiencia sera 0
		if($_GET['intervencion_bovis']==1){
			$_GET['plugin_comproveedores_experiencestypes_id']=0;
		}

		$PluginExperience->check(-1, CREATE, $_GET);

		
		$newID = $PluginExperience->add($_GET);

		$query ="SELECT id FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id']." ORDER BY id DESC LIMIT 1";

		$result = $DB->query($query);
			
		while ($data=$DB->fetch_array($result)) {
			$idExpeciencia=$data["id"];
		}

		echo $idExpeciencia;
	} else if(isset($_GET['update'])){

		//Comprobar si la variable es null, y si lo es eliminarla para que se guarde null en la base de datos
		/*$cambiarValor=array('bim', 'breeam', 'leed', 'otros_certificados', 'cpd_tier');

		foreach ($cambiarValor as $key => $value) {

			if($_GET[$value]==null){
				unset($_GET[$value]);
			}
			
		}*/

		//quitar los puntos y sustituir la coma por un punto para que el valor se guarde correctamente
		$_GET['importe']= str_replace(".", "", $_GET['importe']);
		$_GET['importe']= str_replace(",", ".", $_GET['importe']);

		//si a intervenido bovis el tipo de experiencia sera 0
		if($_GET['intervencion_bovis']==1){
			$_GET['plugin_comproveedores_experiencestypes_id']=0;
		}

		$PluginExperience->check($_GET['id'], UPDATE);
		$PluginExperience->update($_GET);

	}else {
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