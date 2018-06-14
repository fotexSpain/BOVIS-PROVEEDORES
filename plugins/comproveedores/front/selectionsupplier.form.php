<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	use Glpi\Event;

	include ("../../../inc/includes.php");
	global $DB;
	Session::checkLoginUser();
        
                $team = new ProjectTaskTeam();
	
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
		
		Html::back();

	} else if (isset($_POST["restore"])) {
		$PluginSelectionSupplier->check($_POST['id'], PURGE);
		$PluginSelectionSupplier->restore($_POST);
		Html::back();

	} else if (isset($_POST["purge"])) {
		$PluginSelectionSupplier->check($_POST['id'], PURGE);
		$PluginSelectionSupplier->delete($_POST, 1);
		
		Html::back();

	} else if (isset($_GET["actualizar_lista"])) {
            
                        $proveedores_aptos='';
		
                        $query ="select distinct proveedor.id
                                    from glpi_suppliers as proveedor
                                    left join glpi_plugin_comproveedores_experiences as experiencia on proveedor.cv_id=experiencia.cv_id";
                        $where='';
                        
                        //Todos los parametros
                        if($_GET['nombre_proveedor']!='' 
                                || !empty($_GET['arrayProveedoresElegidos'])
                                || !empty($_GET['experiencia_id'])
                                || !empty($_GET['intervencion_bovis'])
                                || !empty($_GET['bim'])
                                || !empty($_GET['breeam'])
                                || !empty($_GET['leed'])
                                || !empty($_GET['otros_certificados'])){
                            
                                $where=" where ";
                        }   
                        
                        if($_GET['nombre_proveedor']!=''){
                            
                            $where=$where."UPPER(proveedor.name)  LIKE UPPER('%".$_GET['nombre_proveedor']."%') and ";
                        }
                        
                          //añadimos a la consulta el tipo de experiencia
                       if(!empty($_GET['experiencia_id'])){
                           
                            $experiencias_elegidas='';
                                                                
                            foreach ($_GET['experiencia_id'] as $value) {
                                if(!empty($value)){
                                     $experiencias_elegidas=$experiencias_elegidas.$value.",";
                                }
                            }
                            $experiencias_elegidas = substr($experiencias_elegidas, 0, -1);
                            $where=$where."experiencia.plugin_comproveedores_experiencestypes_id IN(".$experiencias_elegidas.") and ";
                            
                       }
                        if(!empty($_GET['intervencion_bovis'])){
                         
                            $where=$where."experiencia.intervencion_bovis=".$_GET['intervencion_bovis']." and ";
                            
                       }
                        if(!empty($_GET['bim'])){
                         
                            $where=$where."experiencia.bim=".$_GET['bim']." and ";
                            
                       }
                        if(!empty($_GET['breeam'])){
                         
                            $where=$where."experiencia.breeam=".$_GET['breeam']." and ";
                            
                       }
                        if(!empty($_GET['leed'])){
                         
                            $where=$where."experiencia.leed=".$_GET['leed']." and ";
                            
                       }
                        if(!empty($_GET['otros_certificados'])){
                         
                            $where=$where."experiencia.otros_certificados=".$_GET['otros_certificados']." and ";
                            
                       }
                        
                            //añadimos a la consulta los id de los proveedores elegidos
                       if(!empty($_GET['arrayProveedoresElegidos'])){
                           
                            $proveedores_elegidos='';
                                                                
                            foreach ($_GET['arrayProveedoresElegidos'] as $value) {
                                if(!empty($value)){
                                     $proveedores_elegidos=$proveedores_elegidos.$value.",";
                                }
                            }
                            $proveedores_elegidos = substr($proveedores_elegidos, 0, -1);
                            $where=$where."proveedor.id in (".$proveedores_elegidos.") and ";
                            
                       }
                       
                        $posicion= strripos($where, ' and');
                        $where = substr($where, 0, $posicion);
                            
                        $query=$query.$where;
                        
                       $result = $DB->query($query);
		
                        while ($data=$DB->fetch_array($result)) {
                           if(!empty($data['id'])){
		$proveedores_aptos=$proveedores_aptos.$data['id'].",";
                           }
                        }
                        $proveedores_aptos = substr($proveedores_aptos, 0, -1);
                        //retornamos  los ids
                        echo $proveedores_aptos;
                        
                      
	}else if (isset($_GET["add_proveedor_al_paquete"])) {
                        $proveedores=$_GET['arrayProveedoresElegidos'];
 
                       foreach ($proveedores as $key => $value) {
                                
                                if(!empty($value)){

                                        $add['projecttasks_id']=$_GET["paquete_id"];
                                        $add['itemtype']="Supplier";
                                        $add['items_id']=$value;

                                        $team->check(-1, CREATE, $add);
                                        if ($team->add($add)) {
                                                 Event::log($_GET["paquete_id"], "projecttask", 4, "maintain",
                                                   //TRANS: %s is the user login
                                                sprintf(__('%s adds a team member'), $_SESSION["glpiname"]));
                                        }
                                }        
                        }
                         
                      
                        Html::back();

	}else {
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