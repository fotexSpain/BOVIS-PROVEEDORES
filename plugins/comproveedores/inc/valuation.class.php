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
;
                                        global $CFG_GLPI;
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
                                                    
                                                        $self->showFormValuationPaquete($item, $withtemplate);
                                                }else if($item->getType()=='Project'){
                                                    
                                                        $self->showFormValuationProyecto($item, $withtemplate);
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

		function showFormValuationProveedor($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;
                
			$CvId=$item->fields['id']; 
			$query2 ="SELECT"
                            . "(SELECT projects_id "
                            . "FROM glpi_projecttasks as paquetes "
                            . "WHERE paquetes.id=valoraciones.projecttasks_id) as project_id, valoraciones.* "
                            . "FROM `glpi_plugin_comproveedores_valuations` as valoraciones "
                            . "WHERE cv_id=$CvId order by valoraciones.num_evaluacion desc limit 1";
                        
			$result2 = $DB->query($query2);
                                                
                        
			

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones</th></tr>";
				echo"<br/>";
				echo "<tr><th></th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Calidad')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Plazo')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Costes')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Cultura')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Suministros y Subcontratistas')."</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SyS y Medioambiente')."</th>";
					echo "</tr>";
                                                                        
                                                                        //Ocultar lista, si no existe ninguna valoración
                                                                        if($result2->num_rows!=0){
					while ($data=$DB->fetch_array($result2)) {
							if($data['is_deleted']==""){
								$data['is_deleted']=1;
							}

							echo "<tr style='height:50px;' class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
                                                            echo "<td class='center' style='width:10px; text-align:left; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                echo"<div>".Dropdown::getDropdownName("glpi_projects",$data['project_id'])."</div>";
                                                            echo"</td>";
                                                            echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['calidad']."</td>";
                                                            echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['plazo']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['plazo']."</td>";
                                                            echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['costes']."</td>";
                                                            echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['cultura']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['cultura']."</td>";
                                                            echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['suministros_y_subcontratistas']."</td>";
                                                            echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['sys_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['sys_y_medioambiente']."</td>";
				
								
                                                                                }
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
                
                function showFormValuationProyecto($item, $withtemplate='') {
                                    
                        GLOBAL $DB,$CFG_GLPI;
                        
                        $proyecto_id=$item->fields['id']; 
                        $query2 ="select 
                                        paquetes.code as codigo_paquete, 
                                        paquetes.name as nombre_paquete, 
                                        paquetes.comment as comentario_paquete, 
                                        proveedor.name as nombre_proveedor,
                                        proveedor.cif as nif_proveedor,
                                        valoracion.id as valoracion_id,
                                        valoracion.calidad,
                                        valoracion.plazo,
                                        valoracion.costes, 
                                        valoracion.cultura, 
                                        valoracion.suministros_y_subcontratistas, 
                                        valoracion.sys_y_medioambiente
                                        from glpi_projecttasks as paquetes 
                                        left join glpi_projecttaskteams as projecttaskteams on paquetes.id=projecttaskteams.projecttasks_id
                                        left join glpi_suppliers as proveedor on proveedor.id=projecttaskteams.items_id
                                        left join glpi_projects as proyectos on proyectos.id=paquetes.projects_id
                                        left join glpi_plugin_comproveedores_valuations as valoracion on valoracion.cv_id=proveedor.cv_id
                                        where proyectos.id=$proyecto_id and 
                                        (valoracion.id = (select id from glpi_plugin_comproveedores_valuations as valoracion1 where valoracion1.projecttasks_id=paquetes.id order by valoracion1.num_evaluacion desc limit 1) or valoracion.id IS NULL)";

                        $result2 = $DB->query($query2);

                        echo "<div align='center'><table class='tab_cadre_fixehov'>";
                        echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones</th></tr>";
                        echo"<br/>";
                        echo "<tr><th></th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Código')."</th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Paquetes')."</th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Descripción')."</th>";
		echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Proveedor')."</th>";
		echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('NIF')."</th>";
		echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Q')."</th>";
		echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('PLZ')."</th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('COST')."</th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('CULT')."</th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SUBC')."</th>";
                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SYS')."</th>";
                                
                        echo "</tr>";
                                        
                        //Ocultar lista, si no existe ninguna valoración
                       if($result2->num_rows!=0){
		while ($data=$DB->fetch_array($result2)) {
                                        
                                        echo "<tr style='height:50px;' class='tab_bg_2'>";
                                                echo "<td class='center' style='width:10px; text-align:left;  border: 1px solid #BDBDDB;'>";
                                                        if(!empty($data["valoracion_id"])){
                                                                 echo"<a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php?id=".$data["valoracion_id"]."'><span class='vsubmit' style='margin-right: 15px;'>MODIFICAR</span></a>";
                                                        }                                                       
                                                echo"</td>";
                                                echo "<td class='center' style='width:10px; text-align:left;  border: 1px solid #BDBDDB;'>".$data['codigo_paquete']."</td>";
                                                echo "<td class='center' style='width:10px; text-align:left;  border: 1px solid #BDBDDB;'>".$data['nombre_paquete']."</td>";
                                                echo "<td class='center' style='width:10px; text-align:left;  border: 1px solid #BDBDDB;'>".$data['comentario_paquete']."</td>";
                                                echo "<td class='center' style='width:10px; text-align:left;  border: 1px solid #BDBDDB;'>".$data['nombre_proveedor']."</td>";
                                                echo "<td class='center' style='width:10px; text-align:left;  border: 1px solid #BDBDDB;'>".$data['nif_proveedor']."</td>";
                                                if(!empty($data['calidad'])){
                                                        echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['calidad']."</td>";
                                                }
                                                else{
                                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['plazo'])){
                                                         echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['plazo']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['plazo']."</td>";
                                                }
                                                else{
                                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['costes'])){
                                                        echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['costes']."</td>";
                                                }
                                                else{
                                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['cultura'])){
                                                        echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['cultura']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['cultura']."</td>";
                                                }
                                                else{
                                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['suministros_y_subcontratistas'])){
                                                        echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['suministros_y_subcontratistas']."</td>";
                                                }
                                                else{
                                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['sys_y_medioambiente'])){
                                                        echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['sys_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['sys_y_medioambiente']."</td>";
                                                }
                                                else{
                                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }                                                
                                        echo"</tr>";
		}
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
                
                function showFormValuationPaquete($item, $withtemplate='') {
                                    
                                        GLOBAL $DB,$CFG_GLPI;
                                        
                                        $paquete_id=$item->fields['id'];
                                        $contenido_valoracion=0;
                                        echo"<script type='text/javascript'>
                                            $( function() {
                                                $( '#tabsHorizontal' ).tabs();
                                            });
                                            
                                            var arrayValoracion = [];

                                            for ( var i = 1; i <=3; i++ ) {
                                                arrayValoracion[i] = []; 
                                            }
                                                        
                                        </script>";
                                              
                                         echo"<div id='tabsHorizontal' style='display: inline-block;'>";
                                                echo"<ul style='display: -webkit-box;'>";
                                                        echo"<li><a href='#tabs-1'>Valoración 1</a></li>";
                                                        echo"<li><a href='#tabs-2'>Valoración 2</a></li>";
                                                        echo"<li><a href='#tabs-3'>Valoración 3</a></li>";
                                                echo"</ul>";
                                                echo"<div id='tabs-1'>";
                                                        echo"<div style='-webkit-margin-before: 3em; -webkit-margin-start: -13em;'>";
                                                                $this->modificarValoracionPaquete(1, $paquete_id);
                                                        echo"</div>";
                                                echo"</div>";
                                                echo"<div id='tabs-2'>";
                                                         echo"<div style='-webkit-margin-before: 3em; -webkit-margin-start: -13em;'>";
                                                                $this->modificarValoracionPaquete(2, $paquete_id);
                                                        echo"</div>";
                                                echo"</div>";
                                                 echo"<div id='tabs-3'>";
                                                         echo"<div style='-webkit-margin-before: 3em; -webkit-margin-start: -13em;'>";
                                                                $this->modificarValoracionPaquete(3, $paquete_id);
                                                        echo"</div>";
                                                echo"</div>";
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
			$query2 ="SELECT"
                            . "(SELECT projects_id "
                            . "FROM glpi_projecttasks as paquetes "
                            . "WHERE paquetes.id=valoraciones.projecttasks_id) as project_id, valoraciones.* "
                            . "FROM `glpi_plugin_comproveedores_valuations` as valoraciones "
                            . "WHERE cv_id=$CvId order by valoraciones.num_evaluacion desc limit 1";
                        
			$result2 = $DB->query($query2);

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Evaluaciones</th></tr>";
				echo"<br/>";
				echo "<tr><th></th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Calidad')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Plazo')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Costes')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Cultura')."</th>";
					echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Suministros y subcontratistas')."</th>";
                                                                                echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('SyS y Medioambiente')."</th>";
					echo "</tr>";
                                        
                                                                        //Ocultar lista, si no existe ninguna valoración
                                                                        if($result2->num_rows!=0){
					while ($data=$DB->fetch_array($result2)) {
							if($data['is_deleted']==""){
								$data['is_deleted']=1;
							}

							echo "<tr style='height:50px;' class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
                                                                echo "<td class='center' style='width:10px; text-align:left; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                    echo"<div>".Dropdown::getDropdownName("glpi_projects",$data['project_id'])."</div>";
                                                                echo"</td>";
								echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['calidad']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['plazo']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['plazo']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['costes']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['cultura']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['cultura']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['suministros_y_subcontratistas']."</td>";
                                                                echo "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ;  background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['sys_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['sys_y_medioambiente']."</td>";
								
						}
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

}