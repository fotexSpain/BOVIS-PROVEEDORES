<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresHistory extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Historial','Historial',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Historial');
			}
			return 'Historial';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

                    global $CFG_GLPI;
                    $self = new self();

                     $self->showFormItem($item, $withtemplate);
                          
		}

		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('Historial');

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

		function showFormItem($item, $withtemplate='') {	

			GLOBAL $DB,$CFG_GLPI;
                        
                        if($item-> getType()=="Supplier"){
                            $cv_id=$item->fields['cv_id']; 
                        }else{
                            $cv_id=$item->fields['id']; 
                        }
			

		$query ="SELECT 
                                (Select count(paquetes1.id) from glpi_projecttasks as paquetes1  
                                left join glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=paquetes1.id
                                where paquetes1.projects_id=proyectos.id and projecttaskteams.items_id=proveedor.id) as num_rep_proyecto,
                                proyectos.id as proyecto_id,
                                proyectos.name as nombre_proyecto,
                                paquetes.name as nombre_paquete,
                                paquetes.valor_contrato,
                                paquetes.real_start_date as fecha_inicio,
                                paquetes.real_end_date as fecha_fin,
                                valoracion.id as valoracion_id,
                                valoracion.calidad,
                                valoracion.plazo,
                                valoracion.costes,
                                valoracion.cultura,
                                valoracion.suministros_y_subcontratistas,
                                valoracion.sys_y_medioambiente
                                FROM glpi_projecttaskteams as projecttaskteams 
                                LEFT JOIN glpi_projecttasks as paquetes on paquetes.id=projecttaskteams.projecttasks_id 
                                LEFT JOIN glpi_projects as proyectos on proyectos.id=paquetes.projects_id
                                LEFT JOIN glpi_suppliers as proveedor on proveedor.id=projecttaskteams.items_id
                                LEFT JOIN glpi_plugin_comproveedores_valuations as valoracion on valoracion.projecttasks_id=paquetes.id
                                where proveedor.cv_id=".$cv_id." and (valoracion.id = (select id from glpi_plugin_comproveedores_valuations as valoracion1 where valoracion1.projecttasks_id=paquetes.id order by valoracion1.num_evaluacion desc limit 1) or valoracion.id IS NULL)";
                                               
			$result = $DB->query($query);

                                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
                                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Historial</th></tr>";
                                                echo"<br/>";
                                                echo "<tr><th>".__('Proyectos')."</th>";
                                                        echo "<th>".__('Subcontratistas')."</th>";
                                                        echo "<th>".__('Valor contrato')."</th>";
                                                        echo "<th>".__('Fecha inicio')."</th>";
                                                        echo "<th>".__('Fecha fin')."</th>";
                                                        echo "<th>".__('Calidad')."</th>";
                                                        echo "<th>".__('Plazo')."</th>";
                                                        echo "<th>".__('Coste')."</th>";
                                                        echo "<th>".__('Cultura')."</th>";
                                                        echo "<th>".__('Suministros y subcontratistas')."</th>";
                                                        echo "<th>".__('SyS y Medioambiente')."</th>";
                                                        
                                                echo "</tr>";
                                                
                                                if($result->num_rows!=0){
                                                    
                                                        $proyecto_id=0;
                                                    
                                                        while ($data=$DB->fetch_array($result)) {
                                                            
                                                                echo "<tr style='height:50px;'>";
                                                                        if($proyecto_id!=$data['proyecto_id']){
                                                                            $proyecto_id=$data['proyecto_id'];
                                                                            echo "<td rowspan='".$data['num_rep_proyecto']."' style=' border: 1px solid #BDBDDB;' class='center'>".$data['nombre_proyecto']."</td>";
                                                                            //echo "<td rowspan='".$data['num_rep_proyecto']."' style=' border: 1px solid #BDBDDB;' class='center'><a href='".$CFG_GLPI["root_doc"]."/front/project.form.php?id=".$data["proyecto_id"]."'>".$data["nombre_proyecto"]."</a></td>";
                                                                        }
                                                                        if($item-> getType()=="Supplier" && !empty($data["valoracion_id"])){
                                                                            echo "<td style=' border: 1px solid #BDBDDB;' class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php?id=".$data["valoracion_id"]."'>".$data["nombre_paquete"]."</a></td>";
                                                                        }else{
                                                                            echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data["nombre_paquete"]."</td>";
                                                                        }
                                                                        //echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data['nombre_paquete']."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data['valor_contrato']."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data['fecha_inicio']."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data['fecha_fin']."</td>";
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
                                                                echo "</tr>";

                                                        }
                                                }
                                                echo"<br/>";
			echo "</table></div>";
                                                echo"<br>";
                                              
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