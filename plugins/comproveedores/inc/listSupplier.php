<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

	$tablas='';
	$where='';
	$select='';
	$repeticion_empresa='';

	// el elemento tipo experiencia no es necesario, ya que no esta en la consulta
	$elementos_consulta=['name', 'intervencion_bovis', 'bim', 'leed', 'breeam', 'otros_certificados', 'cpd_tier'];

	//Comprobamos si hay algun filtro, en el caso de haber un filtro, añadimos el WHERE a la consulta
	foreach ($elementos_consulta as $key => $value) {
		if(isset($_GET[$value]) and $_GET[$value]!=''){
			$where=" where ";
		}
	}
	
	//Añadimos la tabla y la referencia
	
	$tablas=' glpi_suppliers as suppliers LEFT JOIN glpi_plugin_comproveedores_experiences as experiences on suppliers.cv_id=experiences.cv_id';

	/////////

	if(isset($_GET['tipos_experiencias'])){
		$filtro_tipo_experiencia=$_GET['tipos_experiencias'];
	}

	///////tabla Experiencias////////

	//Los campos del filtro

	if(isset($_GET['name']) and $_GET['name']!=''){

		$where=$where." UPPER(suppliers.name) LIKE UPPER('%".$_GET['name']."%') and";
	}
	if(isset($_GET['intervencion_bovis']) and $_GET['intervencion_bovis']!=''){
		$where=$where." experiences.intervencion_bovis=".$_GET['intervencion_bovis']." and";
	}
	if(isset($_GET['bim']) and $_GET['bim']!=''){
		$where=$where." experiences.bim=".$_GET['bim']." and";
	}
	if(isset($_GET['leed']) and $_GET['leed']!=''){
		$where=$where." experiences.leed=".$_GET['leed']." and";
	}
	if(isset($_GET['breeam']) and $_GET['breeam']!=''){
		$where=$where." experiences.breeam=".$_GET['breeam']." and";
	}
	if(isset($_GET['otros_certificados']) and $_GET['otros_certificados']!=''){
		$where=$where." experiences.otros_certificados=".$_GET['otros_certificados']." and";
	}
	if(isset($_GET['cpd_tier']) and $_GET['cpd_tier']!=''){
		$where=$where." experiences.cpd_tier=".$_GET['cpd_tier']." and";
	}

	//////Eliminamos el ultimo AND del WHERE
	$posicion= strpos($where, ' and');
	$where = substr($where, 0, $posicion);

	//Los campos que se visualizaran en la tabla
	$select=$select."(SELECT COUNT(cv_id) from glpi_plugin_comproveedores_experiences as a where a.cv_id=suppliers.cv_id GROUP by cv_id) as numRepeticiones, ";
	$select=$select."suppliers.id as is_supplier, 
	 suppliers.cv_id as cv_id, 
	 suppliers.name as empresa,
	 experiences.id as id_experiencia, 
	 experiences.name as nombre_experiencia, 
	 experiences.estado, 
	 experiences.intervencion_bovis, 
	 experiences.plugin_comproveedores_experiencestypes_id as tipo_experiencia";

	//Consulta
	$query ="SELECT ".$select." FROM ".$tablas.$where." order by suppliers.id asc";

	$result = $DB->query($query);

		//Ocultar lista, si no existe ninguna expeciencia
		if($result->num_rows!=0){

			echo"<table class='tab_cadre_fixehov'><tbody>";
			
				echo"<th colspan='14'>Lista de Proveedores</th></tr>";
				echo"<tr class='tab_bg_1 center'>";
					echo "<th>Empresa</th>";
					echo "<th>Cartera actual de trabajo</th>";
					echo "<th>Intervención BOVIS</th>";
					echo "<th>Edificios de oficinas</th>";
					echo "<th>Centros comerciales/locales comerciales</th>";
					echo "<th>Proyectos de hospitales/Centros sanitarios</th>";
					echo "<th>Proyectos de hoteles/Residencias 3ª edad/Residencias estudiantes</th>";
					echo "<th>Proyectos de equipamiento-museos, Centros culturales, ...</th>";
					echo "<th>Centros docentes(Universidades,Institutos de enseñanza,...)</th>";
					echo "<th>Complejos deportivos(Estadios de fútbol,...)</th>";
					echo "<th>Proyectos industriales/Logísticos</th>";
					echo "<th>Proyectos de vivienda residenciales</th>";
					echo "<th>Obras de rehabilitación de edificios</th>";
					echo "<th>Centro de procesos de datos(CPD) y otros proyectos</th>";

				echo "</tr>";
				
			while ($data=$DB->fetch_array($result)) {
			
				echo"<tr class='tab_bg_2'>";
					
					if($repeticion_empresa!=$data['empresa']){
						echo "<td rowspan=".$data['numRepeticiones']." class='center'>
							<a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$data["is_supplier"]."'>".$data['empresa']."
						</td>";
					}else{
						echo "<td></td>";
					}
					
					$repeticion_empresa=$data['empresa'];

					//comprueba que el provedor tiene alguna experiencia, el el caso de no tener solo montara el nombre del proveedor
					if(isset($data['nombre_experiencia'])){

						if(isset($data['estado']) and $data['estado']==1){
							echo "<td  class='center'>
								<a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php?id=".$data["id_experiencia"]."'>".$data['nombre_experiencia']."
							</td>";
						}else{
							echo "<td></td>";
						}


						if(isset($data['intervencion_bovis']) and $data['intervencion_bovis']==1){
							echo "<td  class='center'>
								<a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php?id=".$data["id_experiencia"]."'>".$data['nombre_experiencia']."
							</td>";
						}else{
							echo "<td></td>";
						}

						//Creamos las 11 columnas de tipos de experiencias
						for($i=1; $i<=11; $i++){

							$ocultar_experiencia=true;

							//Si existe la experiencia y el id es igual a la de la columna que tiene que aparecer 
							if(isset($data['tipo_experiencia']) and $data['tipo_experiencia']==$i){

								//Si Existe el filtro de tipos de experiencia, Sino que muestre todo los tipos. 
								if(isset($filtro_tipo_experiencia)){
									foreach ($filtro_tipo_experiencia as $key => $value) {
										
										//Comprueba que el tipo a mostar esta en el filtro
										if($data["tipo_experiencia"]==$value){
											echo "<td  class='center'>
											<a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php?id=".$data["id_experiencia"]."'>".$data['nombre_experiencia']."
											</td>";
											$ocultar_experiencia=false;
										}
									}
									if($ocultar_experiencia){
										echo "<td></td>";
									}
								}
								else{
									echo "<td  class='center'>
											<a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php?id=".$data["id_experiencia"]."'>".$data['nombre_experiencia']."
											</td>";
								}
								
							}
							else{
								echo "<td></td>";
							}

						}
						
					}
					else{
						for($i=1; $i<=13; $i++){
							echo"<td  class='center'></td>";
						}
					}

				echo "</tr>";


			}

			echo "</table>";				
		}