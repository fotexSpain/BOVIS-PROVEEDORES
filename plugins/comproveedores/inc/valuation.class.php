<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresValuation extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Evaluación','Evaluaciones',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
                    
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Evaluaciones');
			}
                                                
			return 'Evaluaciones';
		}

                                static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

                                        global $CFG_GLPI,$DB;
                                        $self = new self();
                                        
                                                if($item->getType()=='Supplier'){	

                                                        if(isset($item->fields['cv_id'])){

                                                                $self->showFormItemValuationProveedor($item, $withtemplate);
                                                        }else{

                                                                $self->showFormNoCV($item, $withtemplate);
                                                        }

                                                }else if($item->getType()=='PluginComproveedoresCv'){
                                                    
                                                        $self->showFormValuationProveedor($item, $withtemplate);
                                                }else if($item->getType()=='ProjectTask'){
                                                    
                                                        
                                                         //Çomprobamos que tiene un proveedor asignado,
                                                         //si el contrato tiene un proveedor asignado, que pase a la pantalla de valoraciones,
                                                         //si no tiene un proveedor asignado, visualizara la pantalla en la que le explica que el contrato tiene que tener un proveedor asignado
                                                        $query ="select items_id from glpi_projecttaskteams where projecttasks_id=".$item->fields['id'];
                        
                                                        $result = $DB->query($query);
                                                        
                                                        if($result->num_rows!=0 || $_SESSION['glpiactiveprofile']['id']==4){
                                                                $self->showFormValuationPaquete($item, $withtemplate);
                                                        }else{
                                                                $self->showFormNoAsignadoProveedor($item, $withtemplate);
                                                        }
                                                }else if($item->getType()=='Project'){
                                                        $id_usuario=$_SESSION['glpiID'];
                                                           
                                                        $query = "select distinct projectteams.items_id 
                                                                        from glpi_projectteams as projectteams 
                                                                        where projectteams.projects_id=".$item->fields['id']." and projectteams.items_id=".$id_usuario;
                                                                       
                                                        $result = $DB->query($query);
                                                       
                                                        //Si un usuario de equipo de proyecto o tiene premisos de super-Admin, que entre
                                                        if($result->num_rows!=0 || $_SESSION['glpiactiveprofile']['id']==4){
                                                                $self->showFormValuationProyecto($item, $withtemplate);
                                                        }
                                                        else{
                                                                $self->showFormNoPermiso($item, $withtemplate);
                                                        }
                                                }            
                                }

		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('Valoraciones');

			$tab[1]['table']	=$this->getTable();
			$tab[1]['field']	='name';
			$tab[1]['name']		=__('Name');
			$tab[1]['datatype']		='itemlink';
			$tab[1]['itemlink_type']	=$this->getTable();

			return $tab;

		}

		function registerType($type){
			if(!in_array($type, self::$types)){
				self::$types[]= $type;
			}		
		}

		static function getTypes($all=false) {
			if ($all) {
				return self::$types;
			}
    // Only allowed types
			$types = self::$types;
			foreach ($types as $key => $type) {
				if (!($item = getItemForItemtype($type))) {
					continue;
				}

				if (!$item->canView()) {
					unset($types[$key]);
				}
			}
			return $types;
		}
                                 // Visualizar Evaluaciones de un proveedor desde gestion de curriculum(Gestión curriculum/Evaluaciones)
		function showFormValuationProveedor($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;
                
			$CvId=$item->fields['id']; 
                        
                                                $query ="Select 
                                                                contratos.id as contrato_id, 
                                                                contratos.tipo_especialidad, 
                                                                valoraciones.* 
                                                                from glpi_projecttasks as contratos
                                                                left join glpi_plugin_comproveedores_valuations as valoraciones on valoraciones.projecttasks_id=contratos.id 
                                                                where valoraciones.id in(Select 
                                                                MAx(valoraciones.id) from glpi_plugin_comproveedores_valuations as valoraciones
                                                                where valoraciones.cv_id=$CvId group by valoraciones.projecttasks_id)";
                                                
			$result = $DB->query($query);
                                                
                                                //Nos creamos 2 array, uno para la tabla Servicios profesionales y otro para Contratistas
                                                $arrayServicioProfesionales=[];
                                                $arrayContratistas=[];
                                                while ($data=$DB->fetch_array($result)) {

                                                        if($data['tipo_especialidad']==1){
                                                                 $arrayServiciosProfesionales[]=$data;
                                                        }
                                                        if($data['tipo_especialidad']==2){
                                                                $arrayContratistas[]=$data;
                                                        }
                                                }
                                                
			echo "<div align='center'><table class='tab_cadre_fixehov'>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones Contratista</th></tr>";
			echo"<br/>";
			echo "<tr><th style='min-width: 80px;'></th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Q')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PLZ')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COST')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CULT')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SUBC')."</th>";
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SYS')."</th>";                                                                                
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";                                                                                
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CERT')."</th>";
                                                echo "</tr>";
                                                                        
                                                foreach ($arrayContratistas as $contratista) {
                                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                                echo "<td class='center' style='width:10px; text-align:left; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                        echo"<div>".Dropdown::getDropdownName("glpi_projecttasks",$contratista['contrato_id'])."</div>";
                                                                echo"</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['calidad']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['planificacion']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['planificacion']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['costes']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['cultura_empresarial']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['gestion_de_suministros_y_subcontratistas']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['seguridad_y_salud_y_medioambiente']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['bim']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['certificacion_medioambiental']."</td>";
                                                        echo"</tr>";

                                                }
								
                                                echo"<br/>";
			echo "</table></div>";
                        
                                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones Servicios Profesionales</th></tr>";
			echo"<br/>";
			echo "<tr><th style='min-width: 80px;'></th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PROY BÁSICO')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CAP EMPRESA')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COLABORADOR')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CAPACIDAD')."</th>";
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('ACTITUD')."</th>";                                                                                
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                                echo "</tr>";
                                                                        
                                                foreach ($arrayServiciosProfesionales as $servicioProfesional) {
                                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                                echo "<td class='center' style='width:10px; text-align:left; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                        echo"<div>".Dropdown::getDropdownName("glpi_projecttasks",$servicioProfesional['contrato_id'])."</div>";
                                                                echo"</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['proyecto_basico']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['proyecto_de_ejecucion']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['capacidad_de_la_empresa']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['colaboradores']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['colaboradores']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['capacidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['capacidad']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['actitud']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['actitud']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['bim']."</td>";
                                                        echo"</tr>";

                                                }
								
                                                echo"<br/>";
			echo "</table></div>";
                                                        
                                                echo "<br><br>";
                                                echo "<div align='center'><table>";
                                                        echo "<tr>";
                                                                    
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_1.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación MALA</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_2.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación POBRE</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_3.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación ACEPTABLE</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_4.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación BUENA</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_5.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación EXCELENTE</td>";
                                                                        
                                                        echo "</tr>";
                                                echo "</table></div>";
			echo"<br>";
						
		}
                // Visualizar Evaluaciones de un proyecto(Proyecto/Evaluaciones)
                function showFormValuationProyecto($item, $withtemplate='') {
                                    
                        GLOBAL $DB,$CFG_GLPI;
                        
                        echo"<script type='text/javascript'>
                                                function  abrirValoracionContrato(valoracion_id, tipo_especialidad){
                                                        var parametros = {
                                                            'id': valoracion_id,
                                                             'tipo_especialidad': tipo_especialidad
                                                        };
                                                
                                                        $.ajax({ 
                                                             type: 'GET',
                                                             data: parametros,                  
                                                             url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/valuation_subcriterio.php',                    
                                                             success:function(data){
                                                                 $('#valoraciones').html(data);

                                                             },
                                                             error: function(result) {
                                                                 alert('Data not found');
                                                             }
                                                         });
                                                }
                                        
                        </script>";
                        
                        $proyecto_id=$item->fields['id']; 
                        $query2 ="select 
                                        paquetes.code as codigo_paquete, 
                                        paquetes.name as nombre_paquete, 
                                        paquetes.tipo_especialidad, 
                                        proveedor.name as nombre_proveedor,
                                        proveedor.cif as nif_proveedor,
                                        valoracion.*
                                        from glpi_projecttasks as paquetes 
                                        left join glpi_projecttaskteams as projecttaskteams on paquetes.id=projecttaskteams.projecttasks_id
                                        left join glpi_suppliers as proveedor on proveedor.id=projecttaskteams.items_id
                                        left join glpi_projects as proyectos on proyectos.id=paquetes.projects_id
                                        left join glpi_plugin_comproveedores_valuations as valoracion on valoracion.cv_id=proveedor.cv_id and 
                                        (valoracion.id = (select id from glpi_plugin_comproveedores_valuations as valoracion1 where valoracion1.projecttasks_id=paquetes.id order by valoracion1.id desc limit 1))
                                        where proyectos.id=$proyecto_id 
                                        order by paquetes.tipo_especialidad asc ";

                        $result2 = $DB->query($query2);
                        
                        //Nos creamos 2 array, uno para la tabla Servicios profesionales y otro para Contratistas
                        $arrayServicioProfesionales=[];
                        $arrayContratistas=[];
                        while ($data=$DB->fetch_array($result2)) {
                            
                                if($data['tipo_especialidad']==1){
                                         $arrayServiciosProfesionales[]=$data;
                                }
                                if($data['tipo_especialidad']==2){
                                        $arrayContratistas[]=$data;
                                }
                        }

                        echo "<div align='center' id='valoraciones'>";
                        
                        //Tabla Servicios profesionales     
                        echo"<table class='tab_cadre_fixehov'>";
                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='15' >Evaluaciones Contratistas</th></tr>";
                                echo"<br/>";
                                echo "<tr><th style='min-width: 80px;'></th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Código')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Contrato')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Proveedor')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('NIF')."</th>";
                                        echo "<th style='min-width: 70px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Fecha')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Q')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PLZ')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COST')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CULT')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SUBC')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SYS')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CERT')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Evaluación Final')."</th>";

                                echo "</tr>";
                                
                                
                                foreach ($arrayContratistas as  $contratista) {
                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                echo "<td class='center' style='width:10px; text-align:center;  border: 1px solid #BDBDDB;'>";
                                                        if(!empty($contratista["id"])){
                                                                echo"<a onclick='abrirValoracionContrato(".$contratista["id"].", 2)' ><span class='vsubmit'>MODIFICAR</span></a>";
                                                        }                                                       
                                                        echo"</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$contratista['codigo_paquete']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$contratista['nombre_paquete']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$contratista['nombre_proveedor']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$contratista['nif_proveedor']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".substr($contratista['fecha'], 0,10)."</td>";
                                                        if(!empty($contratista['calidad'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['calidad']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['calidad']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($contratista['planificacion'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['planificacion']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['planificacion']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($contratista['costes'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['costes']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['costes']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($contratista['cultura_empresarial'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['cultura_empresarial']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($contratista['gestion_de_suministros_y_subcontratistas'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['gestion_de_suministros_y_subcontratistas']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($contratista['seguridad_y_salud_y_medioambiente'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['seguridad_y_salud_y_medioambiente']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }                
                                                        if(!empty($contratista['bim'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['bim']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['bim']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }           
                                                        if(!empty($contratista['certificacion_medioambiental'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$contratista['certificacion_medioambiental']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }     
                                                        if($contratista['evaluacion_final']==1){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                                        }
                                                        else{
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                                        }  
                                        echo"</tr>";
                                }
                        echo"<br/>";
                        echo "</table>";
                        
                        //Tabla Servicios Profesionales       
                        echo"<table class='tab_cadre_fixehov'>";
                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones Servicios Profesionales</th></tr>";
                                echo"<br/>";
                                echo "<tr><th style='min-width: 80px;'></th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Código')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Contrato')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Proveedor')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('NIF')."</th>";
                                        echo "<th style='min-width: 70pxmin-width: 70px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Fecha')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PROY BÁSICO')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CAP EMPRESA')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COLABORADOR')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CAPACIDAD')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('ACTITUD')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                        echo "<th style='min-width: 70pxwidth: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Evaluación Final')."</th>";

                                echo "</tr>";
                                
                                foreach ($arrayServiciosProfesionales as  $servicio_profesional) {
                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                echo "<td class='center' style='width:10px; text-align:center;  border: 1px solid #BDBDDB;'>";
                                                        if(!empty($servicio_profesional["id"])){
                                                                echo"<a onclick='abrirValoracionContrato(".$servicio_profesional["id"].", 1)' ><span class='vsubmit'>MODIFICAR</span></a>";
                                                        }                                                       
                                                        echo"</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$servicio_profesional['codigo_paquete']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$servicio_profesional['nombre_paquete']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$servicio_profesional['nombre_proveedor']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".$servicio_profesional['nif_proveedor']."</td>";
                                                        echo "<td class='center' style='text-align:left;  border: 1px solid #BDBDDB;'>".substr($servicio_profesional['fecha'], 0,10)."</td>";
                                                        if(!empty($servicio_profesional['proyecto_basico'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['proyecto_basico']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($servicio_profesional['proyecto_de_ejecucion'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['proyecto_de_ejecucion']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($servicio_profesional['capacidad_de_la_empresa'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['capacidad_de_la_empresa']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($servicio_profesional['colaboradores'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['colaboradores']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['colaboradores']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($servicio_profesional['capacidad'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['capacidad']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['capacidad']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                        if(!empty($servicio_profesional['actitud'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['actitud']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['actitud']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }                
                                                        if(!empty($servicio_profesional['bim'])){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicio_profesional['bim']).".png); background-repeat: no-repeat;  background-position: center; padding: 10px;'>".$servicio_profesional['bim']."</td>";
                                                        }
                                                        else{
                                                                echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                        }
                                                         if($servicio_profesional['evaluacion_final']==1){
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                                        }
                                                        else{
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                                        }  
                                                        
                                        echo"</tr>";
                                } 
                                
                       
                        echo"</div>";
                                                        
                        echo "<br><br>";
                        echo "<div align='center'><table>";
                                echo "<tr>";
                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_1.png></td>";                                                            
                                        echo "<td  style='width: 50px;'>Calificación MALA</td>";

                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_2.png></td>";                                                            
                                        echo "<td  style='width: 50px;'>Calificación POBRE</td>";

                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_3.png></td>";                                                            
                                        echo "<td  style='width: 50px;'>Calificación ACEPTABLE</td>";

                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_4.png></td>";                                                            
                                        echo "<td  style='width: 50px;'>Calificación BUENA</td>";

                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_5.png></td>";                                                            
                                        echo "<td  style='width: 50px;'>Calificación EXCELENTE</td>";

                                echo "</tr>";
                        echo "</table></div>";
                        echo"<br>";
		
	}
                // Visualizar Evaluaciones de un contrato(Proyecto/contrato/Evaluaciones)
                function showFormValuationPaquete($item, $withtemplate='') {
                                    
                                        GLOBAL $DB,$CFG_GLPI;
                                        
                                        $contrato_id=$item->fields['id'];
                                        $contenido_valoracion=0;
                                        echo"<script type='text/javascript'>
                                            var arrayValoracion = [];

                                            for ( var i = 1; i <=3; i++ ) {
                                                arrayValoracion[i] = []; 
                                            }
                                            
                                            function  abrirValoracionContrato(valoracion_id, tipo_especialidad){
                                                var parametros = {
                                                    'id': valoracion_id,
                                                     'tipo_especialidad': tipo_especialidad
                                                };
                                                
                                               $.ajax({ 
                                                    type: 'GET',
                                                    data: parametros,                  
                                                    url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/valuation_subcriterio.php',                    
                                                    success:function(data){
                                                        $('#valoraciones').html(data);
                                                      
                                                    },
                                                    error: function(result) {
                                                        alert('Data not found');
                                                    }
                                                });
                                            }
                                             
                                            function  nuevaValoracionContrato(contrato_id, tipo_especialidad){
                                                var parametros = {
                                                    'contrato_id': contrato_id,
                                                    'tipo_especialidad': tipo_especialidad
                                                };
                                                
                                               $.ajax({ 
                                                    type: 'GET',
                                                    data: parametros,                  
                                                    url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/valuation_subcriterio.php',                    
                                                    success:function(data){
                                                        $('#valoraciones').html(data);
                                                      
                                                    },
                                                    error: function(result) {
                                                        alert('Data not found');
                                                    }
                                                });
                                            }
                                        </script>";
                                       
                                        echo "<div id='valoraciones' align='center'><table class='tab_cadre_fixehov'>";

                                       
                                        
                                                $query ="SELECT valoracion.* , contrato.tipo_especialidad, proveedor.cv_id as proveedor_cv_id
                                                                FROM glpi_projecttasks as contrato 
                                                                left join glpi_plugin_comproveedores_valuations as valoracion  on contrato.id=valoracion.projecttasks_id
                                                                left join glpi_projecttaskteams as projecttaskteams  on projecttaskteams.projecttasks_id=valoracion.projecttasks_id
                                                                left join glpi_suppliers as proveedor  on proveedor.id=projecttaskteams.items_id
                                                                where contrato.id=".$contrato_id." order by valoracion.id asc";
                        
                                                $result = $DB->query($query);
                                                
                                                $visualizar_cabecera=true;
                                                $visualizar_boton_nueva_evaluacion=true;
                                                $num_evaluación=1;
                                                while ($data=$DB->fetch_array($result)) {
                                                    
                                                        $tipo_especialidad=$data['tipo_especialidad'];
                                                        
                                                        
                                                        //Si existe una valoración final, quitar el boton nueva evaluación
                                                        if($data['evaluacion_final']==1){
                                                                $visualizar_boton_nueva_evaluacion=false;
                                                        }
                                                        
                                                        if($visualizar_cabecera){
                                                            
                                                                $visualizar_cabecera=false;
                                                                echo "<tr class='tab_cadre_fixehov nohover'><th colspan='14' >Lista de evaluaciones</th></tr>";
                                                                echo"<br/>";
                                                                        echo "<th style='width: 160px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>EVALUACIÓN</th>";
                                                                        echo "<th style='width: 200px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>FECHA</th>";
                                                                        if($tipo_especialidad==2){
                                                                            //Contratista
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>Q</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>PLZ</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>COST</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>CULT</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>SUBC</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>SyS</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>BIM</th>";
                                                                            echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>CERT</th>";
                                                                        }else{
                                                                                //Servicios Profesioneales

                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>PROY BÁSICO</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>PROY EJECUCIÓN</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>CAP EMPRESA</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>COLABORADOR</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>CAPACIDAD</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>ACTITUD</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>BIM</th>";
                                                                                
                                                                        }
                                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>TOTAL</th>";
                                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>APTO</th>";
                                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>ELIMINAR</th>";
                                                                echo "</tr>";
                                                        }
                                                        if(!empty($data['id'])){
                                                                echo"<tr style='height: 45px;'>";
                                                                echo "<td style=' border: 1px solid #BDBDDB;'><a href='#' onclick='abrirValoracionContrato(".$data['id'].", ".$tipo_especialidad.")' >Evaluación ".$num_evaluación."</a></td>";
                                                                $num_evaluación++;
                                                                
                                                                 echo "<td style='border: 1px solid #BDBDDB;'>".substr($data['fecha'], 0,10)."</td>";

                                                                if($tipo_especialidad==2){

                                                                         //Contratista
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['calidad'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['planificacion']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['planificacion'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['costes'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['cultura_empresarial'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['gestion_de_suministros_y_subcontratistas'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['seguridad_y_salud_y_medioambiente'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['bim'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['certificacion_medioambiental'], '.0')."</td>";
                                                                        $total=$data['calidad']+$data['planificacion']+$data['costes']+$data['cultura_empresarial']+$data['gestion_de_suministros_y_subcontratistas']+$data['seguridad_y_salud_y_medioambiente']+$data['bim']+$data['certificacion_medioambiental'];
                                                                }else{

                                                                        //Servicios Profesioneales
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['proyecto_basico'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['proyecto_de_ejecucion'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['capacidad_de_la_empresa'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['colaboradores']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['colaboradores'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['capacidad']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['capacidad'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['actitud']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['actitud'], '.0')."</td>";
                                                                        echo "<td style='text-align: center; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".rtrim($data['bim'], '.0')."</td>";
                                                                        $total=$data['proyecto_basico']+$data['proyecto_de_ejecucion']+$data['capacidad_de_la_empresa']+$data['colaboradores']+$data['capacidad']+$data['actitud']+$data['bim'];
                                                                }
                                                                
                                                                echo "<td style='border: 1px solid #BDBDDB;'>".$total."</td>";
                                                                echo "<td style='border: 1px solid #BDBDDB;'>".$this->recomendacionesEvaluacion($total)."</td>";
                                                                   
                                                                echo "<td style='border: 1px solid #BDBDDB;'>";
                                                                        echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php method='post'>";
                                                                                echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
                                                                                echo Html::hidden('id', array('value' =>$data['id']));
                                                                                echo"<input type='submit' class='submit' name='delete_evaluacion' value='ELIMINAR' />";
                                                                        echo"</form>";
                                                                echo "</td>";
                                                                
                                                                echo"</tr>";
                                                        }
                                                }
                                                
                                        echo "<tr>";
                                        echo "</tr>";                                       
                                        echo "</table>";
                                        echo "<br>";
                                        
                                        if($visualizar_boton_nueva_evaluacion){
                                                echo "<div>";
                                                        echo "<span onclick='nuevaValoracionContrato(".$contrato_id.", ".$tipo_especialidad.")' class='vsubmit' style='margin-right: 15px;'>NUEVA EVALUACIÓN</span>";
                                                echo "</div>";
                                        }
                                        echo "</div>";
                                        
                                        echo "<br><br>";
                                        echo "<div align='center'><table>";
                                                echo "<tr>";
                                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_1.png></td>";                                                            
                                                        echo "<td  style='width: 50px;'>Calificación MALA</td>";

                                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_2.png></td>";                                                            
                                                        echo "<td  style='width: 50px;'>Calificación POBRE</td>";

                                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_3.png></td>";                                                            
                                                        echo "<td  style='width: 50px;'>Calificación ACEPTABLE</td>";

                                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_4.png></td>";                                                            
                                                        echo "<td  style='width: 50px;'>Calificación BUENA</td>";

                                                        echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_5.png></td>";                                                            
                                                        echo "<td  style='width: 50px;'>Calificación EXCELENTE</td>";

                                                echo "</tr>";
                                        echo "</table></div>";
                                        
                                        //Obtenemos el CV_Id del contrato
                                        $cv_id='';
                                        
                                        $query2 ="select proveedor.cv_id 
                                                        from glpi_projecttaskteams as projecttaskteams
                                                        inner join glpi_suppliers as proveedor on projecttaskteams.items_id=proveedor.id 
                                                        where  projecttasks_id=".$contrato_id;
                        
                                        $result2 = $DB->query($query2);
                                                
                                        while ($data=$DB->fetch_array($result2)) {
                                                $cv_id=$data['cv_id'];
                                        }
                                        
                                        echo"<div id='evaluacion'>";
                                                echo Html::hidden('cv_id', array('value' =>$cv_id));
                                        echo"</div>";
                                        
	}
           
                function modificarValoracionPaquete($valoracion, $paquete_id){
                    GLOBAL $DB,$CFG_GLPI;
                   
                    echo $this->consultaAjax();     
                    
                    //formato de fecha yyyy-mm-dd
                    $_SESSION['glpidate_format']=0;
                    echo "<div id='fecha_valoracion_".$valoracion."' style='text-align:left; display: -webkit-box;'>";
                                echo"<div style='margin-right:10px; position: relative; top: 3px;'>Fecha de valoración</div>";
                                echo"<div>";
                                Html::showDateTimeField("fecha");
                                echo"</div>";
                    echo"</div>";
                    echo "<div align='center'><table class='tab_cadre_fixehov'>";
                   
                    echo "<tr class='tab_bg_$valoracion tab_cadre_fixehov nohover'><th colspan='14' >Evaluación ".$valoracion."</th></tr>";
                    echo"<br/>";
                    echo "<tr><th></th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Mal')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Pobre')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Adecuado')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Bien')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Excelente')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Comentario')."</th>";
                    echo "</tr>";

                        $arrayValoraciones[0]=['calidad', 'Calidad'];
                        $arrayValoraciones[1]=['plazo', 'Plazo'];
                        $arrayValoraciones[2]=['costes', 'Costes'];
                        $arrayValoraciones[3]=['cultura', 'Cultura'];
                        $arrayValoraciones[4]=['suministros_y_subcontratistas', 'Suministros y Subcontratistas'];
                        $arrayValoraciones[5]=['sys_y_medioambiente', 'Sys y Medioambiente'];
                                                
                                                
                                                    

                    foreach ($arrayValoraciones as $key => $value) {
                                                    
                        echo "<tr class='tab_bg_2' style='height:60px;'>";
                            echo"<td  class='center' id='criterio_".$key."_0_valoracion_$valoracion' style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>$value[1]</td>";
                            echo"<td class='center' id='criterio_".$key."_1_valoracion_$valoracion' style='width: 100px; font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(1,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_2_valoracion_$valoracion' style='width: 100px; font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(2, $key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_3_valoracion_$valoracion'' style='width: 100px; font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(3,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_4_valoracion_$valoracion' style='width: 100px; font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(4,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_5_valoracion_$valoracion' style='width: 100px; font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(5,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_comentario_valoracion_$valoracion' style='width: 100px; font-weight:bold; border: 1px solid #BDBDDB;'><textarea rows='4' cols='45' style='resize: none'></textarea></td>";
                        echo "</tr>";    
                    }

                    echo"<br/>";
                    echo "</table></div>";
                                      
                        $query ="SELECT * 
                            FROM glpi_plugin_comproveedores_valuations as valoracion
                            where valoracion.projecttasks_id=".$paquete_id." and valoracion.num_evaluacion=".$valoracion;
                        
                            $result = $DB->query($query);
                            $contenido_valoracion=1;             
                            while ($data=$DB->fetch_array($result)) {
                                            
                                //Creamos un script donde se cagarán los valores de la consulta
                                echo"<script type='text/javascript'>      
                                       $( function() {";
                                                
                                            foreach ($arrayValoraciones as $key => $value) {
                                                $valor=$data[$value[0]];                                                
                                                echo "$('#criterio_".$key."_comentario_valoracion_".$valoracion."').find('textarea').html('".$data[$value[0].'_coment']."');";
                                                echo"valorElegido($valor, $key, $valoracion);";
                                            } 
                                            
                                        //Les pasamos el valor a los input de fecha de valoracion
                                        echo"$('#fecha_valoracion_".$valoracion."').find('input[name=_fecha]').val('".$data['fecha']."');";    
                                        echo"$('#fecha_valoracion_".$valoracion."').find('input[name=fecha]').val('".$data['fecha']."');";     
                                echo"});</script>";
                                                 
                                 
                                $valoracion_id=$data['id'];   
                            }
                                        
                            echo "<br><br>";
                            echo"<div  id='boton_guardar_$valoracion'>";
                                if($result->num_rows!=0){ 

                                    echo "<span onclick='guardarYModificarValoracion($paquete_id,$valoracion,$valoracion_id,\"update_valoracion\")' class='vsubmit' style='margin-right: 15px;'>MODIFICAR VALORACIÓN</span>";
                                }  
                                else{

                                    echo "<span onclick='guardarYModificarValoracion($paquete_id,$valoracion,-1,\"add_valoracion\")'class='vsubmit' style='margin-right: 15px;'>GUARDAR VALORACIÓN</span>";      
                                }    
                                echo"</div>";
                                echo"<br>";
                                echo"<br>";
                }
                
                function showForm(){
                    GLOBAL $DB,$CFG_GLPI;
                                   
                    echo"<script type='text/javascript'>                                           
                               
                        var arrayValoracion = [];

                        for ( var i = 1; i <=3; i++ ) {
                            arrayValoracion[i] = []; 
                        }
                    </script>";
                    
                    echo $this->consultaAjax();
                        
                    $valoracion=0;
                    $paquete_id=0;
                    $query2 ="SELECT valoracion.num_evaluacion, valoracion.projecttasks_id 
                            FROM glpi_plugin_comproveedores_valuations as valoracion
                            where valoracion.id=".$_GET['id'];
                        
                    $result2 = $DB->query($query2);
                                      
                    while ($data=$DB->fetch_array($result2)) {
                        $valoracion=$data['num_evaluacion'];
                        $paquete_id=$data['projecttasks_id'];
                    }
                    
                    //formato de fecha yyyy-mm-dd
                    $_SESSION['glpidate_format']=0;
                    echo "<div id='fecha_valoracion_".$valoracion."' style='text-align:left; display: -webkit-box;'>";
                                echo"<div style='margin-right:10px; position: relative; top: 3px;'>Fecha de valoración</div>";
                                echo"<div>";
                                Html::showDateTimeField("fecha");
                                echo"</div>";
                    echo"</div>";
                                                                       
                    echo "<div align='center'><table class='tab_cadre_fixehov'>";
                    echo "<tr class='tab_bg_$valoracion tab_cadre_fixehov nohover'><th colspan='14' >Evaluación ".$valoracion."</th></tr>";
                    echo"<br/>";
                    echo "<tr><th></th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Mal')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Pobre')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Adecuado')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Bien')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Excelente')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Comentario')."</th>";
                    echo "</tr>";

                        $arrayValoraciones[0]=['calidad', 'Calidad'];
                        $arrayValoraciones[1]=['plazo', 'Plazo'];
                        $arrayValoraciones[2]=['costes', 'Costes'];
                        $arrayValoraciones[3]=['cultura', 'Cultura'];
                        $arrayValoraciones[4]=['suministros_y_subcontratistas', 'Suministros y Subcontratistas'];
                        $arrayValoraciones[5]=['sys_y_medioambiente', 'Sys y Medioambiente'];
                                                
                                                
                                                    

                    foreach ($arrayValoraciones as $key => $value) {
                                                    
                        echo "<tr class='tab_bg_2' style='height:60px;'>";
                            echo"<td class='center' id='criterio_".$key."_0_valoracion_$valoracion' style='background-color:#D8D8D8; border: 1px solid #BDBDDB;'>$value[1]</td>";
                            echo"<td class='center' id='criterio_".$key."_1_valoracion_$valoracion' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(1,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_2_valoracion_$valoracion' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(2, $key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_3_valoracion_$valoracion'' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(3,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_4_valoracion_$valoracion' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(4,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_5_valoracion_$valoracion' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(5,$key,$valoracion)'></td>";
                            echo"<td class='center' id='criterio_".$key."_comentario_valoracion_$valoracion' style='font-weight:bold; border: 1px solid #BDBDDB;'><textarea rows='4' cols='45' style='resize: none'></textarea></td>";
                        echo "</tr>";    
                    }

                    echo"<br/>";
                    echo "</table></div>";
                                      
                        $query ="SELECT * 
                            FROM glpi_plugin_comproveedores_valuations as valoracion
                            where valoracion.projecttasks_id=".$paquete_id." and valoracion.num_evaluacion=".$valoracion;
                       
                        
                            $result = $DB->query($query);
                            $contenido_valoracion=1;             
                            while ($data=$DB->fetch_array($result)) {
                                            
                                //Creamos un script donde se cagarán los valores de la consulta
                                echo"<script type='text/javascript'>      
                                       $( function() {";
                                                
                                            foreach ($arrayValoraciones as $key => $value) {
                                                $valor=$data[$value[0]];
                                                echo "$('#criterio_".$key."_comentario_valoracion_".$valoracion."').find('textarea').html('".$data[$value[0].'_coment']."');";
                                                echo"valorElegido($valor, $key, $valoracion);";
                                            } 
                                               
                                         //Les pasamos el valor a los input de fecha de valoración
                                        echo"$('#fecha_valoracion_".$valoracion."').find('input[name=_fecha]').val('".$data['fecha']."');";    
                                        echo"$('#fecha_valoracion_".$valoracion."').find('input[name=fecha]').val('".$data['fecha']."');";    
                                echo"});</script>";
                                                 
                                        
                                $valoracion_id=$data['id'];   
                            }
                                        
                            echo "<br><br>";
                            echo"<div  id='boton_guardar_$valoracion'>";
                                if($result->num_rows!=0){ 

                                    echo "<span onclick='guardarYModificarValoracion($paquete_id,$valoracion,$valoracion_id,\"update_valoracion\")' class='vsubmit' style='margin-right: 15px;'>MODIFICAR VALORACIÓN</span>";
                                }  
                                else{

                                    echo "<span onclick='guardarYModificarValoracion($paquete_id,$valoracion,-1,\"add_valoracion\")'class='vsubmit' style='margin-right: 15px;'>GUARDAR VALORACIÓN</span>";      
                                }    
                                echo"</div>";
                                echo"<br>";
                                echo"<br>";
                }    
                           
                    function showFormItemValuationProveedor($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;
                        
			$CvId=$item->fields['cv_id']; 
                        
			$query ="Select 
                                                                contratos.id as contrato_id, 
                                                                contratos.tipo_especialidad, 
                                                                valoraciones.* 
                                                                from glpi_projecttasks as contratos
                                                                left join glpi_plugin_comproveedores_valuations as valoraciones on valoraciones.projecttasks_id=contratos.id 
                                                                where valoraciones.id in(Select 
                                                                MAx(valoraciones.id) from glpi_plugin_comproveedores_valuations as valoraciones
                                                                where valoraciones.cv_id=$CvId group by valoraciones.projecttasks_id)";
                                                
			$result = $DB->query($query);
                        
                                                //Nos creamos 2 array, uno para la tabla Servicios profesionales y otro para Contratistas
                                                $arrayServicioProfesionales=[];
                                                $arrayContratistas=[];
                                                while ($data=$DB->fetch_array($result)) {

                                                        if($data['tipo_especialidad']==1){
                                                                 $arrayServiciosProfesionales[]=$data;
                                                        }
                                                        if($data['tipo_especialidad']==2){
                                                                $arrayContratistas[]=$data;
                                                        }
                                                }
                                                
			echo "<div align='center'><table class='tab_cadre_fixehov'>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones Contratista</th></tr>";
			echo"<br/>";
			echo "<tr><th style='min-width: 80px;'></th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Q')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PLZ')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COST')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CULT')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SUBC')."</th>";
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SYS')."</th>";                                                                                
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";                                                                                
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CERT')."</th>";
                                                echo "</tr>";
                                                                        
                                                foreach ($arrayContratistas as $contratista) {
                                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                                echo "<td class='center' style='width:10px; text-align:left; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                        echo"<div>".Dropdown::getDropdownName("glpi_projecttasks",$contratista['contrato_id'])."</div>";
                                                                echo"</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['calidad']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['planificacion']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['planificacion']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['costes']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['cultura_empresarial']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['gestion_de_suministros_y_subcontratistas']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['seguridad_y_salud_y_medioambiente']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['bim']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($contratista['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['certificacion_medioambiental']."</td>";
                                                        echo"</tr>";

                                                }
								
                                                echo"<br/>";
			echo "</table></div>";
                        
                                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones Servicios Profesionales</th></tr>";
			echo"<br/>";
			echo "<tr><th style='min-width: 80px;'></th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PROY BÁSICO')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CAP EMPRESA')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COLABORADOR')."</th>";
				echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CAPACIDAD')."</th>";
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('ACTITUD')."</th>";                                                                                
                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                                echo "</tr>";
                                                                        
                                                foreach ($arrayServiciosProfesionales as $servicioProfesional) {
                                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                                echo "<td class='center' style='width:10px; text-align:left; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                        echo"<div>".Dropdown::getDropdownName("glpi_projecttasks",$servicioProfesional['contrato_id'])."</div>";
                                                                echo"</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['proyecto_basico']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['proyecto_de_ejecucion']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['capacidad_de_la_empresa']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['colaboradores']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['colaboradores']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['capacidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['capacidad']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['actitud']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['actitud']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($servicioProfesional['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$servicioProfesional['bim']."</td>";
                                                        echo"</tr>";

                                                }
								
                                                echo"<br/>";
			echo "</table></div>";
                                                        
                                                echo "<br><br>";
                                                echo "<div align='center'><table>";
                                                        echo "<tr>";
                                                                    
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_1.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación MALA</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_2.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación POBRE</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_3.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación ACEPTABLE</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_4.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación BUENA</td>";
                                                                        
                                                                echo "<td class='center'><img style='vertical-align:middle; margin: 5px 0px;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_5.png></td>";                                                            
                                                                echo "<td  style='width: 50px;'>Calificación EXCELENTE</td>";
                                                                        
                                                        echo "</tr>";
                                                echo "</table></div>";
			echo"<br>";
		}
                       
	function showFormNoCV($ID, $options=[]) {
                        //Aqui entra cuando no tien gestionado el curriculum

                        echo "<div>Necesitas gestionar el CV antes de ver las evaluaciones</div>";
                        echo "<br>";
	}
                
                function showFormNoAsignadoProveedor($ID, $options=[]) {
                        //Aqui entra cuando no tien gestionado el curriculum

                        echo "<div>Necesitas seleccionar un proveedor para este contrato, antes de evaluar</div>";
                        echo "<br>";
	}
        
                function showFormNoPermiso($ID, $options=[]) {
                        //Aqui entra cuando no tien gestionado el curriculum

                        echo "<div>Solo pueden acceder los usuarios que este en Equipo de proyecto o sean Administrador</div>";
                        echo "<br>";
	}
                
                function getColorValoracion($valor){
	           
                    switch ($valor) {
                        case $valor<=1:

                            $color=1;
                            break;
                        case $valor<=2 && $valor>1:

                            $color=2;
                            break;
                        case $valor<=3 && $valor>2:

                            $color=3;
                            break;
                        case $valor<=4 && $valor>3:

                            $color=4;
                            break;
                        case $valor<=5 && $valor>4:

                            $color=5;
                            break;
                        default:
                            break;
                    }

                    return $color;
                }
                
                function recomendacionesEvaluacion($total){
                      
                    switch ($total) {
                        case $total<7:

                            $valor="NO APTO";
                            break;
                        case $total<15:

                            $valor="POCO RECOMENDABLE";
                            break;
                        case $total<26:

                            $valor="RECOMENDABLE";
                            break;
                        case $total>26:

                            $valor="MUY RECOMENDABLE";
                            break;
                        default:
                            break;
                    }
                    
                    return $valor;
                }
                
            function consultaAjax(){
                GLOBAL $DB,$CFG_GLPI;
                $resultado="<script type='text/javascript'>  
                    
                        function valorElegido(valor_criterio, tipo_criterio, valoracion){
                                                                
                            for(i=1;i<=5;i++){
                                if(valor_criterio==i){
                                                                            
                                    $('#criterio_'+tipo_criterio+'_'+i+'_valoracion_'+valoracion).css({
                                        'background-image':'url(".$CFG_GLPI["root_doc"]."/pics/valoracion_'+valor_criterio+'.png)',
                                        'background-repeat':'no-repeat',
                                        'background-position':'center'});
                                    $('#criterio_'+tipo_criterio+'_'+i+'_valoracion_'+valoracion).html(valor_criterio);
                                                                                
                                    //añadimos el valor elegido a arrayValoracion
                                    arrayValoracion[valoracion][tipo_criterio]=valor_criterio;                                                                        
                                }
                                else{
                                    $('#criterio_'+tipo_criterio+'_'+i+'_valoracion_'+valoracion).css({'background-image':'none'});
                                    $('#criterio_'+tipo_criterio+'_'+i+'_valoracion_'+valoracion).html('');
                                }                                       
                            }                                      
                        }
                        function guardarYModificarValoracion(paquete_id, numero_valoracion, valoracion_id, metodo){
                                                                
                            var valoracionesCompletada=true;
                            var arrayComentarios= [];
                            //Si arrayValoracion no tiene todo los campos completado, no se añadira la valoración
                            if( arrayValoracion[numero_valoracion].length==6){
                                                                    
                                for(i=0;i<arrayValoracion[numero_valoracion].length;i++){

                                    if(arrayValoracion[numero_valoracion][i]==null ){
                                        valoracionesCompletada=false; 
                                    }
                                    arrayComentarios[i]=$('#criterio_'+i+'_comentario_valoracion_'+numero_valoracion).find('textarea').val();
                                }
                                                                        
                                if(valoracionesCompletada){

                                    var parametros = {
                                        'metodo': metodo,
                                        'arrayComentarios':arrayComentarios,
                                        'valoracion_id': valoracion_id,
                                        'paquete_id':paquete_id,
                                        'numero_valoracion' : numero_valoracion,
                                        'fecha':$('#fecha_valoracion_'+numero_valoracion).find('input[name=fecha]').val(), 
                                        'arrayValoracion': arrayValoracion[numero_valoracion]    
                                    };
                                                                              
                                    $.ajax({ 
                                        type: 'GET',
                                        data: parametros,                  
                                        url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php',                    
                                        success:function(data){
                                            if(metodo=='add_valoracion'){
                                                                                     
                                                $('#boton_guardar_'+numero_valoracion).html(
                                                    '<span onclick=\'guardarYModificarValoracion('+paquete_id+','+numero_valoracion+','+data+',\"update_valoracion\")\' class=\'vsubmit\' style=\'margin-right: 15px;\'>MODIFICAR VALORACIÓN</span>'
                                                );
                                            }
                                        },
                                        error: function(result) {
                                            alert('Data not found');
                                        }
                                    });
                                }
                            }
                        }   
                 </script>";
                
                return $resultado;
            }
            
        static function cronEvaluacionesRecordatorio($task = null) {
               GLOBAL $DB,$CFG_GLPI;
               
               $email = new NotificationMailing();
               
               $meses=4;
                
               //Evaluaciones en la que la fecha de evaluación tiene mas de X meses de antiguedad
               $query="select 
                        (select configuracion.value from glpi_configs as configuracion where configuracion.name='asunto_correo') as asunto_correo,
                        (select configuracion.value from glpi_configs as configuracion where configuracion.name='cuerpo_correo') as cuerpo_correo,
                        (select configuracion.value from glpi_configs as configuracion where configuracion.name='firma_correo') as firma_correo,
                        (select configuracion.value from glpi_configs as configuracion where configuracion.name='remitente_correo') as remitente_correo,
                        (select configuracion.value from glpi_configs as configuracion where configuracion.name='remitente_nombre') as remitente_nombre,
                        group_concat(contratos.id) as contratos_id,
                        group_concat(contratos.name) as contratos_name,
                        usuarios.name as nombre_usuario,
                        evaluaciones.id as evaluacion_id,
                        evaluaciones.fecha as fecha,
                        email.email as email
                        from glpi_projectteams as projectteams 
                        left join glpi_projecttasks as contratos on contratos.projects_id=projectteams.projects_id
                        left join glpi_plugin_comproveedores_valuations as evaluaciones on evaluaciones.projecttasks_id 
                        and evaluaciones.id=(Select max(valoraciones.id) from glpi_plugin_comproveedores_valuations as valoraciones
                        where valoraciones.projecttasks_id=contratos.id) 
                        left join glpi_users as usuarios on usuarios.id=projectteams.items_id
                        left join glpi_useremails as email on email.users_id=usuarios.id 
                        where evaluaciones.fecha is not null 
                        and email.email is not null
                        and DATE(evaluaciones.fecha) <= DATE(NOW() - INTERVAL (select configuracion.value from glpi_configs as configuracion where configuracion.name='meses_valoraciones') month)
                        group by usuarios.id";
               
                $result = $DB->query($query);
                
                while ($data=$DB->fetch_array($result)) {

                        $contratos_name=explode( ',', $data['contratos_name']);

                        //Servicio de correo y puerto
                        $CFG_GLPI["smtp_host"]='aspmx.l.google.com';
                        $CFG_GLPI["smtp_port"]=25;

                        //Correo destinatario
                        $CFG_GLPI['admin_email']=$data['email'];

                        //Nombre destinatario
                        $CFG_GLPI["admin_email_name"]=$data['nombre_usuario'];

                        //Firma de los correos
                        $CFG_GLPI["mailing_signature"]=$data['firma_correo'];

                        //Titulo del correo
                        $subject=$data['asunto_correo'];

                        //Mensaje del correo
                        $body=$data['cuerpo_correo']." \n";
                        foreach ($contratos_name as $value) {
                                    
                                $body .=$value." \n";
                        }
                        

                        //Correo remitente
                        $remitente_correo=$data['remitente_correo'];

                        //Nombre Remitente
                        $remitente_nombre=$data['remitente_nombre'];

                        $email->sendCorreoEvaluaciones($subject, $body, $remitente_correo, $remitente_nombre);
                    
                }
                
        }
    
}