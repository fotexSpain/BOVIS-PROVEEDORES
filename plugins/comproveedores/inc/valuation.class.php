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
			
                                                                        $self->showFormItem($item, $withtemplate);

				}else{
				
					$self->showFormNoCV($item, $withtemplate);
				}
				
			}else if($item->getType()=='PluginComproveedoresCv'){
				$self->showFormItemValuation($item, $withtemplate);
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

		function showFormItemValuation($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;

			$CvId=$item->fields['supplier_id']; 
			$query2 ="SELECT"
                                                                         . "(SELECT projects_id "
                                                                        . "FROM glpi_projecttasks as paquetes "
                                                                        . "WHERE paquetes.id=valoraciones.projecttasks_id) as project_id, valoraciones.* "
                                                . "FROM `glpi_plugin_comproveedores_valuations` as valoraciones "
                                                 . "WHERE supplier_id=$CvId";
                        
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
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['calidad'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['plazo'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['costes'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['cultura'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['suministros_y_subcontratistas'])."; border:1px solid #BDBDDB;'></td>";	
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['sys_y_medioambiente'])."; border: 1px solid #BDBDDB;'></td>";
								
                                                                                }
                                                                        }
							echo"<br/>";
							echo "</table></div>";
                                                        
                                                                echo "<br><br>";
                                                                echo "<div align='center'><table>";
                                                                    echo "<tr>";
                                                                        echo "<td style='width: 50px; height:40px; background-color:#FF0000'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación MALA</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#FF8000'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación POBRE</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#FFFF00'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación ACEPTABLE</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#64FE2E'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación BUENA</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#04B404'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación EXCELENTE</td>";
                                                                        
                                                                    echo "</tr>";
                                                                echo "</table></div>";
							echo"<br>";
				
					
		}
                
                                function showFormItem($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;

			$CvId=$item->fields['id']; 
			$query2 ="SELECT"
                                                                         . "(SELECT projects_id "
                                                                        . "FROM glpi_projecttasks as paquetes "
                                                                        . "WHERE paquetes.id=valoraciones.projecttasks_id) as project_id, valoraciones.* "
                                                . "FROM `glpi_plugin_comproveedores_valuations` as valoraciones "
                                                 . "WHERE supplier_id=$CvId";
                        
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
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['calidad'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['plazo'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['costes'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['cultura'])."; border: 1px solid #BDBDDB;'></td>";
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['suministros_y_subcontratistas'])."; border:1px solid #BDBDDB;'></td>";	
								echo "<td class='center' style='background-color:".$this->colorValoracion($data['sys_y_medioambiente'])."; border: 1px solid #BDBDDB;'></td>";
								
						}
                                                                        }
							echo"<br/>";
							echo "</table></div>";
                                                        
                                                                echo "<br><br>";
                                                                echo "<div align='center'><table>";
                                                                    echo "<tr>";
                                                                        echo "<td style='width: 50px; height:40px; background-color:#FF0000'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación MALA</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#FF8000'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación POBRE</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#FFFF00'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación ACEPTABLE</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#64FE2E'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación BUENA</td>";
                                                                        
                                                                        echo "<td style='width: 50px; height:50px; background-color:#04B404'></td>";
                                                                        echo "<td  style='width: 50px;'>Calificación EXCELENTE</td>";
                                                                        
                                                                    echo "</tr>";
                                                                echo "</table></div>";
							echo"<br>";
				
					
		}
                
                                function colorValoracion($num){
                                    $color;
                                    switch ($num) {
                                        case 1:

                                            $color='#FF0000';
                                            break;
                                        case 2:

                                             $color='#FF8000';
                                            break;
                                        case 3:

                                             $color='#FFFF00';
                                            break;
                                        case 4:

                                             $color='#64FE2E';
                                            break;
                                        case 5:

                                             $color='#04B404';
                                            break;

                                        default:
                                              $color='#FFFFFF';
                                            break;
                                    }
                                    
                                    return $color;
                                }


		function showFormNoCV($ID, $options=[]) {
			//Aqui entra cuando no tien gestionado el curriculum

			echo "<div>Necesitas gestionar el CV antes de ver las valoraciones</div>";
			echo "<br>";
		}		

}