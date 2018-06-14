<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresValuation extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Valoraciones','Valoraciones',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
                    
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Valoraciones');
			}
                                                
			return 'Valoraciones';
		}

		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

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
                                                 . "WHERE cv_id=$CvId";
                        
			$result2 = $DB->query($query2);
                                                
                        
			

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Valoraciones</th></tr>";
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

							echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
                                                                                                                                echo "<td class='center' style='background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                                                                                    echo"<div>".Dropdown::getDropdownName("glpi_projects",$data['project_id'])."</div>";
                                                                                                                                     echo"<br>";
                                                                                                                                    echo"<div>".Dropdown::getDropdownName("glpi_projecttasks",$data['projecttasks_id'])."</div>";
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
                
                                function showFormValuationPaquete($item, $withtemplate='') {
                                    
                                        GLOBAL $DB,$CFG_GLPI;
                                        
                                        $paquete_id=$item->fields['id'];
                                                                                
                                        echo"<script>
                                            
                                                        var arrayValoracion= new Array();
                                                        
                                                        $( function() {
                                                                $( '#tabsHorizontal' ).tabs();
                                                        });
                                                        function valorElegido(valor_criterio, tipo_criterio){
                                                                
                                                                for(i=1;i<=5;i++){
                                                                        if(valor_criterio==i){
                                                                            
                                                                                $('#'+tipo_criterio+'_'+valor_criterio+'').css({
                                                                                        'background-image':'url(".$CFG_GLPI["root_doc"]."/pics/valoracion_'+valor_criterio+'.png)',
                                                                                        'background-repeat':'no-repeat',
                                                                                        'background-position':'center'});
                                                                                $('#'+tipo_criterio+'_'+valor_criterio+'').html(valor_criterio);
                                                                                
                                                                                //añadimos el valor elegido a arrayValoracion
                                                                                arrayValoracion[tipo_criterio]=valor_criterio;
                                                                               
                                                                        }
                                                                        else{
                                                                                $('#'+tipo_criterio+'_'+i+'').css({'background-image':'none'});
                                                                                $('#'+tipo_criterio+'_'+i+'').html('');
                                                                        }
                                                                       
                                                                }
                                                                
                                                        }
                                                        function guardarValoracion(paquete_id, numero_valoracion){
                                                                
                                                                var valoracionesCompletada=true;
                                                               
                                                                //Si arrayValoracion no tiene todo los campos completado, no se añadira la valoración
                                                                if(arrayValoracion.length==6){
                                                                
                                                                        for(i=0;i<arrayValoracion.length;i++){

                                                                                if(arrayValoracion[i]==null ){
                                                                                        valoracionesCompletada=false;
                                                                                }
                                                                        }

                                                                        if(valoracionesCompletada){

                                                                                var parametros = {
                                                                                       'guardar_valoracion': 'guardar_valoracion',
                                                                                       'paquete_id':paquete_id,
                                                                                       'numero_valoracion' : numero_valoracion,
                                                                                       'arrayValoracion': arrayValoracion     
                                                                                };

                                                                                $.ajax({ 
                                                                                       type: 'GET',
                                                                                       data: parametros,                  
                                                                                       url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php',                    
                                                                                       success:function(data){
                                                                                                alert(data);
                                                                                       },
                                                                                       error: function(result) {
                                                                                           alert('Data not found');
                                                                                       }
                                                                                });
                                                                        }
                                                                }
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
                        
			
                                       /* $query2 ="SELECT"
                                                                         . "(SELECT projects_id "
                                                                        . "FROM glpi_projecttasks as paquetes "
                                                                        . "WHERE paquetes.id=valoraciones.projecttasks_id) as project_id, valoraciones.* "
                                                . "FROM `glpi_plugin_comproveedores_valuations` as valoraciones "
                                                 . "WHERE cv_id=$CvId";
                        
                                        $result2 = $DB->query($query2);*/
                                                
                                        echo "<div align='center'><table class='tab_cadre_fixehov'>";
                                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Valoración ".$valoracion."</th></tr>";
                                                echo"<br/>";
                                                echo "<tr><th></th>";
                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Mal')."</th>";
                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Pobre')."</th>";
                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Adecuado')."</th>";
                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Bien')."</th>";
                                                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Excelente')."</th>";
                                                echo "</tr>";

                                                $arrayValoraciones=['Calidad', 'Plazo', 'Costes', 'Cultura', 'Suministros y subcontratistas', 'Sys y Medioambiente'];     

                                                foreach ($arrayValoraciones as $key => $value) {
                                                    
                                                        echo "<tr class='tab_bg_2' style='height:60px;'>";
                                                                echo "<td class='center' style='background-color:#D8D8D8; border: 1px solid #BDBDDB;'>$value</td>";
                                                                echo"<td  class='center' id='".$key."_1' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(1, \"$key\")'></td>";
                                                                echo"<td class='center' id='".$key."_2' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(2, \"$key\")'></td>";
                                                                echo"<td class='center' id='".$key."_3' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(3, \"$key\")'></td>";
                                                                echo"<td class='center' id='".$key."_4' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(4, \"$key\")'></td>";
                                                                echo"<td class='center' id='".$key."_5'' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(5, \"$key\")'></td>";
                                                        echo "</tr>";    
                                                }

                                                echo"<br/>";
                                        echo "</table></div>";
                                                        
                                        echo "<br><br>";
                                               
                                        echo "<span onclick='guardarValoracion(".$paquete_id.",".$valoracion.")' class='vsubmit' style='margin-right: 15px;'>AÑADIR PROVEEDOR</span>";
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
                                                 . "WHERE cv_id=$CvId";
                        
			$result2 = $DB->query($query2);
                                                
                        
			
			

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Valoraciones</th></tr>";
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

							echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
                                                                                                                                echo "<td class='center' style='background-color:#D8D8D8; border: 1px solid #BDBDDB;'>";
                                                                                                                                    echo"<div>".Dropdown::getDropdownName("glpi_projects",$data['project_id'])."</div>";
                                                                                                                                     echo"<br>";
                                                                                                                                    echo"<div>".Dropdown::getDropdownName("glpi_projecttasks",$data['projecttasks_id'])."</div>";
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

			echo "<div>Necesitas gestionar el CV antes de ver las valoraciones</div>";
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

}