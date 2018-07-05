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
				return self::createTabEntry('Contratos');
			}
			return 'Contratos';
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
                                                (Select count(contratos1.id) from glpi_projecttasks as contratos1  
                                                left join glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos1.id
                                                where contratos1.projects_id=proyectos.id and projecttaskteams.items_id=proveedor.id and contratos1.tipo_especialidad=2) as num_rep_proyecto,
                                                proyectos.id as proyecto_id,
                                                proyectos.name as nombre_proyecto,
                                                contratos.name as nombre_contrato,
                                                contratos.valor_contrato,
                                                contratos.plan_start_date as fecha_inicio,
                                                contratos.plan_end_date as fecha_fin,
                                                (select round(avg(calidad),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as calidad,
                                                (select round(avg(planificacion),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as planificacion,
                                                (select round(avg(costes),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as costes,
                                                (select round(avg(cultura_empresarial),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as cultura_empresarial,
                                                (select round(avg(gestion_de_suministros_y_subcontratistas),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as gestion_de_suministros_y_subcontratistas,
                                                (select round(avg(seguridad_y_salud_y_medioambiente),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as seguridad_y_salud_y_medioambiente,
                                                (select round(avg(bim),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as bim,
                                                (select round(avg(certificacion_medioambiental),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as certificacion_medioambiental
                                                
                                                FROM glpi_projecttaskteams as projecttaskteams 
                                                LEFT JOIN glpi_projecttasks as contratos on contratos.id=projecttaskteams.projecttasks_id 
                                                LEFT JOIN glpi_projects as proyectos on proyectos.id=contratos.projects_id
                                                LEFT JOIN glpi_suppliers as proveedor on proveedor.id=projecttaskteams.items_id
                                                where proveedor.cv_id=$cv_id and contratos.tipo_especialidad=2";     
                
			$result = $DB->query($query);

                                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
                                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Historial Contratista</th></tr>";
                                                echo"<br/>";
                                                echo "<tr><th>".__('Proyectos')."</th>";
                                                        echo "<th>".__('Subcontratistas')."</th>";
                                                        echo "<th>".__('Valor contrato')."</th>";
                                                        echo "<th style='min-width: 70px;'>".__('Fecha inicio')."</th>";
                                                        echo "<th style='min-width: 70px;'>".__('Fecha fin')."</th>";
                                                        echo "<th>".__('Q')."</th>";
                                                        echo "<th>".__('PLZ')."</th>";
                                                        echo "<th>".__('COST')."</th>";
                                                        echo "<th>".__('CULT')."</th>";
                                                        echo "<th>".__('SUBC')."</th>";
                                                        echo "<th>".__('SYS')."</th>";
                                                        echo "<th>".__('BIM')."</th>";
                                                        echo "<th>".__('CERT')."</th>";
                                                        
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
                                                                            echo "<td style=' border: 1px solid #BDBDDB;' class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php?id=".$data["valoracion_id"]."'>".$data["nombre_contrato"]."</a></td>";
                                                                        }else{
                                                                            echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data["nombre_contrato"]."</td>";
                                                                        }
                                                                        
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data['valor_contrato']."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".substr($data['fecha_inicio'], 0,10)."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".substr($data['fecha_fin'], 0,10)."</td>";
                                                                        if(!empty($data['calidad'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['calidad']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['planificacion'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['planificacion']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['planificacion']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['costes'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['costes']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['cultura_empresarial'])){
                                                                             echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['cultura_empresarial']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['gestion_de_suministros_y_subcontratistas'])){
                                                                             echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['gestion_de_suministros_y_subcontratistas']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                        if(!empty($data['seguridad_y_salud_y_medioambiente'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['seguridad_y_salud_y_medioambiente']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                        if(!empty($data['bim'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['bim']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                        if(!empty($data['certificacion_medioambiental'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['certificacion_medioambiental']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                echo "</tr>";

                                                        }
                                                }
                                                echo"<br/>";
			echo "</table></div>";
                                                echo"<br>";
                                
		$query ="SELECT 
                                                (Select count(contratos1.id) from glpi_projecttasks as contratos1  
                                                left join glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos1.id
                                                where contratos1.projects_id=proyectos.id and projecttaskteams.items_id=proveedor.id and contratos1.tipo_especialidad=1) as num_rep_proyecto,
                                                proyectos.id as proyecto_id,
                                                proyectos.name as nombre_proyecto,
                                                contratos.name as nombre_contrato,
                                                contratos.valor_contrato,
                                                contratos.plan_start_date as fecha_inicio,
                                                contratos.plan_end_date as fecha_fin,
                                                (select round(avg(bim),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as bim,
                                                (select round(avg(proyecto_basico),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as proyecto_basico,
                                                (select round(avg(proyecto_de_ejecucion),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as proyecto_de_ejecucion,
                                                (select round(avg(capacidad_de_la_empresa),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as capacidad_de_la_empresa,
                                                (select round(avg(colaboradores),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as colaboradores,
                                                (select round(avg(capacidad),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as capacidad,
                                                (select round(avg(actitud),2) from glpi_plugin_comproveedores_valuations where projecttasks_id=contratos.id) as actitud
                                                
                                                FROM glpi_projecttaskteams as projecttaskteams 
                                                LEFT JOIN glpi_projecttasks as contratos on contratos.id=projecttaskteams.projecttasks_id 
                                                LEFT JOIN glpi_projects as proyectos on proyectos.id=contratos.projects_id
                                                LEFT JOIN glpi_suppliers as proveedor on proveedor.id=projecttaskteams.items_id
                                                where proveedor.cv_id=$cv_id and contratos.tipo_especialidad=1";     
                
			$result = $DB->query($query);

                                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
                                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Historial Servicios Profesionales</th></tr>";
                                                echo"<br/>";
                                                echo "<tr><th>".__('Proyectos')."</th>";
                                                        echo "<th>".__('Subcontratistas')."</th>";
                                                        echo "<th>".__('Valor contrato')."</th>";
                                                        echo "<th style='min-width: 70px;'>".__('Fecha inicio')."</th>";
                                                        echo "<th style='min-width: 70px;'>".__('Fecha fin')."</th>";
                                                        echo "<th>".__('PROY BÁSICO')."</th>";
                                                        echo "<th>".__('PROY EJECUCIÓN')."</th>";
                                                        echo "<th>".__('CAP EMPRES')."</th>";
                                                        echo "<th>".__('COLABORADOR')."</th>";
                                                        echo "<th>".__('CAPACIDAD')."</th>";
                                                        echo "<th>".__('ACTITUD')."</th>";
                                                        echo "<th>".__('BIM')."</th>";
                                                        
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
                                                                            echo "<td style=' border: 1px solid #BDBDDB;' class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php?id=".$data["valoracion_id"]."'>".$data["nombre_contrato"]."</a></td>";
                                                                        }else{
                                                                            echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data["nombre_contrato"]."</td>";
                                                                        }
                                                                        
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".$data['valor_contrato']."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".substr($data['fecha_inicio'], 0,10)."</td>";
                                                                        echo "<td style=' border: 1px solid #BDBDDB;' class='center'>".substr($data['fecha_fin'], 0,10)."</td>";
                                                                        if(!empty($data['proyecto_basico'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['proyecto_basico']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['proyecto_de_ejecucion'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['proyecto_de_ejecucion']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['capacidad_de_la_empresa'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['capacidad_de_la_empresa']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['colaboradores'])){
                                                                             echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['colaboradores']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['colaboradores']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                         if(!empty($data['capacidad'])){
                                                                             echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['capacidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['capacidad']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                        if(!empty($data['actitud'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['actitud']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['actitud']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
                                                                        }
                                                                        if(!empty($data['bim'])){
                                                                            echo "<td class='center' style='padding: 10px; border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".$this->getColorValoracion($data['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['bim']."</td>";
                                                                        }
                                                                        else{
                                                                            echo"<td class='center' style='padding: 10px; border: 1px solid #BDBDDB;'></td>";
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