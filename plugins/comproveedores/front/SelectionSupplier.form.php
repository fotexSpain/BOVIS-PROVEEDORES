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

	} else if (isset($_GET["actualizar_lista"])) {
            
                        $quitar_proveedores='';
		
                        $query ="select proveedor.id
                                    from glpi_suppliers as proveedor
                                    left join glpi_plugin_comproveedores_experiences as experiencia on proveedor.cv_id=experiencia.cv_id";
                        $where='';
                        
                        //Todos los parametros
                        if($_GET['nombre_proveedor']!='' 
                                || !empty($_GET['arrayProveedoresElegidos'])
                                || !empty($_GET['experiencia_id'])){
                                $where=" where ";
                        }   
                        
                        if($_GET['nombre_proveedor']!=''){
                            $where=$where."UPPER(proveedor.name) NOT  LIKE UPPER('%".$_GET['nombre_proveedor']."%') and ";
                            //$where=$where."UPPER(proveedor.name) NOT  LIKE UPPER('%cons%') and ";
                        }
                        
                          //añadimos a la consulta los id de los proveedores elegidos
                       if(!empty($_GET['experiencia_id'])){
                           
                            $experiencias_elegidas='';
                                                                
                            foreach ($_GET['experiencia_id'] as $value) {
                                if(!empty($value)){
                                     $experiencias_elegidas=$experiencias_elegidas.$value.",";
                                }
                            }
                            $experiencias_elegidas = substr($experiencias_elegidas, 0, -1);
                            $where=$where."experiencia.plugin_comproveedores_experiencestypes_id NOT IN(".$experiencias_elegidas.") and ";
                            
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
		$quitar_proveedores=$quitar_proveedores.$data['id'].",";
                           }
                        }
                        $quitar_proveedores = substr($quitar_proveedores, 0, -1);
                        //retornamos  los ids
                        echo $quitar_proveedores;
                        
                        
                        /*$arrayjjj=$_GET['arrayProveedoresElegidos'];
                       $quitar_proveedores='';
                                                                
                        foreach ($arrayjjj as $value) {
                            $quitar_proveedores=$quitar_proveedores.$value.",";
                        }
                        echo $quitar_proveedores;
                        echo $query;*/
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