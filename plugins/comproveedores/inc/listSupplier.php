<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

	$tablas='';
	$where='';
	$select='';

	if(isset($_GET['bim']) and !empty($_GET['bim'])){
		$where=" where ";
	}

	///////Experiencia tabla////////

	//Añadimos la tabla y la referencia
	if(isset($_GET['bim']) and !empty($_GET['bim'])){
		$tablas=$tablas.", glpi_plugin_comproveedores_experiences as experiences";
		$where=$where."  suppliers.cv_id=experiences.cv_id";
	}
	
	//los campos del filtro
	if(isset($_GET['bim']) and !empty($_GET['bim'])){
		$where=$where." and experiences.bim=".$_GET['bim']."";
		$select=$select.", experiences.name as nombreExperiencia";
	}



	//Consulta
	
	$query ="SELECT suppliers.name as empresa".$select." FROM glpi_suppliers as suppliers".$tablas.$where."";


	//////Visualizar consulta(Quitar al terminar)
	
	echo "Consulta:";
	echo "<br><br>";

	echo $query;
	echo "<br><br>";

	////////////

			$result = $DB->query($query);

			//Ocultar lista, si no existe ninguna expeciencia
		if($result->num_rows!=0){

			echo"<table class='tab_cadre_fixe'><tbody>";
			
				echo"<th colspan='4'>Lista de Proveedores</th></tr>";
				echo"<tr class='tab_bg_1 center'>";
					echo "<td>Empresa</td>";
					echo "<td>Experiencia</td>";
				echo "</tr>";
				
			while ($data=$DB->fetch_array($result)) {
							
				echo"<tr class='tab_bg_1 center'>";
					echo "<td>".$data['empresa']."</td>";

					if(isset($data['nombreExperiencia']))
					echo "<td>".$data['nombreExperiencia']."</td>";
				echo "</tr>";

			}

			echo "</table>";				
		}