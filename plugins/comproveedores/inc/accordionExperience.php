<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

$objExperiencia=new PluginComproveedoresExperience;

echo consultaJquery();

echo "<div id='accordion'>";

	///////Intervencion Bovis			 	
	$cadena= "select count(*) as numero, intervencion_bovis as bovis from glpi_plugin_comproveedores_experiences where cv_id={$_GET['cv_id']} and intervencion_bovis=1 group by intervencion_bovis";

	$result = $DB->query($cadena);

	foreach ($result as $fila){
	    echo"<h3 name='intervencion_bovis' class='tipo_experiencia_intervencion_bovis'>Intervención Bovis (".$fila['numero'].")</h3>";
	  	echo"<div style='max-height: 350px; min-height: 350px;' class='tipo_experiencia_intervencion_bovis'>";  
	  	echo"</div>";
	}
	                
	//////Tipos de experiencias
	$cadena= "select e.plugin_comproveedores_experiencestypes_id as id, t.descripcion, count(*) as numero
	                            from glpi_plugin_comproveedores_experiences   as e
	                            LEFT join glpi_plugin_comproveedores_experiencestypes  as t on e.plugin_comproveedores_experiencestypes_id = t.id
	                            where cv_id={$_GET['cv_id']} and e.plugin_comproveedores_experiencestypes_id!='0' and intervencion_bovis=0 
	                            group by plugin_comproveedores_experiencestypes_id";
	                        
	$result = $DB->query($cadena);
	                       
	foreach ($result as $fila){

		echo"<h3 name='".$fila['id']."' class='tipo_experiencia_".$fila['id']."' style='height:auto;'>".
	                    $fila['descripcion']." (".$fila['numero'].")"
	                    ."</h3>";

		echo"<div style='max-height:350px;min-height:50px;background-color: rgb(244, 245, 245);' class='tipo_experiencia_".$fila['id']."'>";  
	  	echo"</div>";
	}
	                   
	//////Sin experiencias    
	$cadena= "select count(*) as numero from glpi_plugin_comproveedores_experiences where cv_id={$_GET['cv_id']} and intervencion_bovis=0 and plugin_comproveedores_experiencestypes_id=0 group by intervencion_bovis";

	$result = $DB->query($cadena);

	foreach ($result as $fila){

	    echo"<h3 name='sin_experiencia' class='tipo_experiencia_sin_experiencia'>Sin Experiencias (".$fila['numero'].")</h3>";
		echo"<div style='max-height: 350px; min-height: 350px;' class='tipo_experiencia_sin_experiencia'>";  
		echo"</div>";	
	}
echo"</div>";	
			


function consultaJquery(){

	GLOBAL $CFG_GLPI;

	$consulta="<script type='text/javascript'>

		$(document).ready(function() {

			//Añadimos la función acordeon a las listas 
			$( '#accordion' ).accordion({collapsible:true, active: false});
			$( '.accordion_header .ui-accordion-header .ui-helper-reset .ui-state-default .ui-accordion-icons .ui-accordion-header-active .ui-state-active .ui-corner-top' ).css('background', '#1b2f62');
			   					
			//Añadimos onclick a las lista para que se cargen a elegirlas
			$('h3[class*=tipo_experiencia_]').click(function() {
		  		actualizarLista($(this).attr('name'));	
			});
								
		});

	</script>";

	return $consulta;

}
