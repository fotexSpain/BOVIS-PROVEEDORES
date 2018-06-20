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

	}else if(isset($_GET['metodo']) && $_GET['metodo']=='add_valoracion'){
            
                        $valoracion=array();
            
                       $query ="SELECT proveedores.cv_id as cv_id FROM glpi_projecttaskteams as projecttaskteams LEFT JOIN glpi_suppliers proveedores on projecttaskteams.items_id=proveedores.id WHERE projecttasks_id=".$_GET['paquete_id'];
                                   
                        $result = $DB->query($query);
		
                        while ($data=$DB->fetch_array($result)) {
                                $valoracion['cv_id']=$data['cv_id'];
                        }
                        
                        $valoracion['fecha']=$_GET['fecha'];
                        $valoracion['projecttasks_id']=$_GET['paquete_id'];
                        $valoracion['projecttasks_id']=$_GET['paquete_id'];
                        $valoracion['num_evaluacion']=$_GET['numero_valoracion'];
                        foreach ($_GET['arrayValoracion'] as $key => $value) {
                                switch ($key) {
                                        case 0:
                                                $valoracion['calidad']=$value;
                                                break;
                                        case 1:
                                                $valoracion['plazo']=$value;
                                                break;
                                        case 2:
                                                $valoracion['costes']=$value;
                                                break;
                                        case 3:
                                                $valoracion['cultura']=$value;
                                                break;
                                        case 4:
                                                $valoracion['suministros_y_subcontratistas']=$value;
                                                break;
                                        case 5:
                                                $valoracion['sys_y_medioambiente']=$value;
                                                break;

                                        default:
                                            break;
                                }
                        }
                        
                          foreach ($_GET['arrayComentarios'] as $key => $value) {
                                switch ($key) {
                                        case 0:
                                                $valoracion['calidad_coment']=$value;
                                                break;
                                        case 1:
                                                $valoracion['plazo_coment']=$value;
                                                break;
                                        case 2:
                                                $valoracion['costes_coment']=$value;
                                                break;
                                        case 3:
                                                $valoracion['cultura_coment']=$value;
                                                break;
                                        case 4:
                                                $valoracion['suministros_y_subcontratistas_coment']=$value;
                                                break;
                                        case 5:
                                                $valoracion['sys_y_medioambiente_coment']=$value;
                                                break;

                                        default:
                                            break;
                                }
                        }
            
                        $PluginValuation->check(-1, CREATE, $valoracion);
                        $newID = $PluginValuation->add($valoracion);
	
                        echo $newID;
	}else if(isset($_GET['metodo']) && $_GET['metodo']=='update_valoracion'){
            
                       
                        
                       $valoracion=array();
            
                       $query ="SELECT proveedores.cv_id as cv_id FROM glpi_projecttaskteams as projecttaskteams LEFT JOIN glpi_suppliers proveedores on projecttaskteams.items_id=proveedores.id WHERE projecttasks_id=".$_GET['paquete_id'];
                                   
                        $result = $DB->query($query);
		
                        while ($data=$DB->fetch_array($result)) {
                                $valoracion['cv_id']=$data['cv_id'];
                        }
                       
                        $valoracion['fecha']=$_GET['fecha'];
                        $valoracion['projecttasks_id']=$_GET['paquete_id'];
                        $valoracion['projecttasks_id']=$_GET['paquete_id'];
                        $valoracion['num_evaluacion']=$_GET['numero_valoracion'];
                        $valoracion['id']=$_GET['valoracion_id'];
                        foreach ($_GET['arrayValoracion'] as $key => $value) {
                                switch ($key) {
                                        case 0:
                                                $valoracion['calidad']=$value;
                                                break;
                                        case 1:
                                                $valoracion['plazo']=$value;
                                                break;
                                        case 2:
                                                $valoracion['costes']=$value;
                                                break;
                                        case 3:
                                                $valoracion['cultura']=$value;
                                                break;
                                        case 4:
                                                $valoracion['suministros_y_subcontratistas']=$value;
                                                break;
                                        case 5:
                                                $valoracion['sys_y_medioambiente']=$value;
                                                break;

                                        default:
                                            break;
                                }
                        }
                        
                         foreach ($_GET['arrayComentarios'] as $key => $value) {
                                switch ($key) {
                                        case 0:
                                                $valoracion['calidad_coment']=$value;
                                                break;
                                        case 1:
                                                $valoracion['plazo_coment']=$value;
                                                break;
                                        case 2:
                                                $valoracion['costes_coment']=$value;
                                                break;
                                        case 3:
                                                $valoracion['cultura_coment']=$value;
                                                break;
                                        case 4:
                                                $valoracion['suministros_y_subcontratistas_coment']=$value;
                                                break;
                                        case 5:
                                                $valoracion['sys_y_medioambiente_coment']=$value;
                                                break;

                                        default:
                                            break;
                                }
                        }
            
                        $PluginValuation->check($valoracion['id'], UPDATE);
                        $PluginValuation->update($valoracion);
                        	
                       

                        Html::back();
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