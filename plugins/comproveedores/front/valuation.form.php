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
        $PluginSubValuation= new PluginComproveedoresSubvaluation();

	
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

	/*}else if(isset($_GET['metodo']) && $_GET['metodo']=='add_valoracion'){
            
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
                        	
                       

                        Html::back();*/
	}else if(isset($_GET['guardarSubvaloraciones']) && $_GET['metodo']=='update_valoracion'){
            
                        
                        $subvaloraciones=[];
                        $valoraciones=[];
                        $subvaloraciones_valor=$_GET['arraySubValoracionValor'];
                        $subvaloraciones_comentario=$_GET['arraySubValoracionComentario'];
                        
                       //Guardamos las subvaloraciones
                       $query ="select subvaloracion.*,criterios.criterio_padre, criterios.ponderacion from glpi_plugin_comproveedores_subvaluations as subvaloracion
                                left join glpi_plugin_comproveedores_criterios as criterios on criterios.id=subvaloracion.criterio_id
                                where subvaloracion.valuation_id=".$_GET['valoracion_id'];

                        $result = $DB->query($query);

                        while ($data=$DB->fetch_array($result)) {
                            $subvaloracion['id']=$data['id'];
                            $subvaloracion['valuation_id']=$data['valuation_id'];
                            $subvaloracion['criterio_id']=$data['criterio_id'];
                            $subvaloracion['valor']=$subvaloraciones_valor[$data['criterio_id']];
                            $subvaloracion['comentario']=$subvaloraciones_comentario[$data['criterio_id']];
                            
                            //Sumamos el valor de los subcriterio
                            $valoracion[$data['criterio_padre']]+=(($subvaloraciones_valor[$data['criterio_id']]/100)*$data['ponderacion']);
                            
                            
                            $PluginSubValuation->check($subvaloracion['id'],UPDATE);
                            $PluginSubValuation->update($subvaloracion);
                        }
                        
                        //Guardamos la valoracion
                        $query2 ="select distinct criterio_padre from glpi_plugin_comproveedores_criterios as criterio where criterio.tipo_especialidad=".$_GET['tipo_especialidad'];
                        $result2 = $DB->query($query2);

                        while ($data=$DB->fetch_array($result2)) {
                          $valoracion[$data['criterio_padre']]=round($valoracion[$data['criterio_padre']], 2);
                        }
                        
                        $valoracion['cv_id']=$_GET['cv_id'];
                        $valoracion['evaluacion_final']=$_GET['eval_final'];
                        $valoracion['fecha']=$_GET['fecha'];
                        $valoracion['id']=$_GET['valoracion_id'];
                        
                        $PluginValuation->check($valoracion['id'], UPDATE);
                        $PluginValuation->update($valoracion);
                                   
	}else if(isset($_GET['guardarSubvaloraciones']) && $_GET['metodo']=='add_valoracion'){
            
                        $subvaloraciones=[];
                        $valoraciones=[];
                        $subvaloraciones_valor=$_GET['arraySubValoracionValor'];
                        $subvaloraciones_comentario=$_GET['arraySubValoracionComentario'];
                        
                        //Guardamos la valoracion
                        $query ="select distinct(select num_evaluacion from glpi_plugin_comproveedores_valuations where projecttasks_id=".$_GET['contrato_id']." order by num_evaluacion desc limit 1) as num_evaluacion, 
                                criterio_padre from glpi_plugin_comproveedores_criterios as criterio 
                                where criterio.tipo_especialidad=".$_GET['tipo_especialidad'];
                        $result = $DB->query($query);

                        while ($data=$DB->fetch_array($result)) {
                          $valoracion[$data['criterio_padre']]=0;
                          $valoracion['num_evaluacion']=($data['num_evaluacion']+1);
                        }
                        
                        $valoracion['projecttasks_id']=$_GET['contrato_id'];
                        
                        $PluginValuation->check(-1, CREATE, $valoracion);
                        $newID = $PluginValuation->add($valoracion);

                        //Guardamos las subvaloraciones

                        $query2 ="select id, criterio_padre, ponderacion from glpi_plugin_comproveedores_criterios as criterio where criterio.tipo_especialidad=".$_GET['tipo_especialidad'];
                        $result2 = $DB->query($query2);

                        while ($data=$DB->fetch_array($result2)) {
                        
                            //Se debe crear la valoracion vacia primero
                            $subvaloracion['valuation_id']=$newID;
                            $subvaloracion['criterio_id']=$data['id'];
                            $subvaloracion['valor']=$subvaloraciones_valor[$data['id']];
                            $subvaloracion['comentario']=$subvaloraciones_comentario[$data['id']];
                            
                            //Sumamos el valor de los subcriterio
                            $valoracion[$data['criterio_padre']]+=(($subvaloraciones_valor[$data['id']]/100)*$data['ponderacion']);
                            
                            $PluginSubValuation->check(-1, CREATE, $subvaloracion);
                            $PluginSubValuation->add($subvaloracion);
                        }
                        //Modificamos la valoración
                        $query3 ="select distinct criterio_padre from glpi_plugin_comproveedores_criterios as criterio where criterio.tipo_especialidad=".$_GET['tipo_especialidad'];
                        $result3 = $DB->query($query3);

                        while ($data=$DB->fetch_array($result3)) {
                          $valoracion[$data['criterio_padre']]=round($valoracion[$data['criterio_padre']], 2);
                        }
                        
                        $valoracion['cv_id']=$_GET['cv_id'];
                        $valoracion['evaluacion_final']=$_GET['eval_final'];
                        $valoracion['fecha']=$_GET['fecha'];
                        $valoracion['id']=$newID;
                        
                        $PluginValuation->check($valoracion['id'], UPDATE);
                        $PluginValuation->update($valoracion);

	}else {
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