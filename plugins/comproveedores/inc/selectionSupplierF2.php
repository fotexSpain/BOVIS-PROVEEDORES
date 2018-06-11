<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

$objCommonDBT=new CommonDBTM();

echo consultaAjax();

echo"<table class='tab_cadre_fixe'><tbody>";
			
			echo"<th colspan='6'>Selección Proveedor</th></tr>";

			echo"<tr class='tab_bg_1 center'>";
                            echo "<td >" . __('Nombre') . "</td>";
                            echo "<td style='text-align:left;'>";
                            Html::autocompletionTextField($objCommonDBT,'name');
                            echo "</td>";

                            echo "<td>" . __('Intervención de BOVIS') . "</td>";
                                    echo "<td id='intervencionBovis'>";
                                    echo"<input type='checkbox' name='intervencion_bovis'/>";
                            echo "</td>";
				
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
                            
                            echo "<td rowspan='5'>" . __('Tipos de experiencias') . "</td>";
				$lista=getTiposExperiencias();
				echo "<td rowspan='5' >";
						echo "<div style='text-align:left; width: 150px; height: 100px; overflow-y: scroll; border: 1px solid #BDBDBD'>";
						foreach ($lista as $key => $value) {
						
							echo "&nbsp&nbsp<input type='checkbox' name='tipos_experiencias[]' value='".$key."' />&nbsp&nbsp".$value."<br />";
						}
						echo "</div>";
                            echo "</td>";
                        
                        echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
                               
				echo "<td>" . __('BIM') . "</td>";
				echo "<td>";
                                    echo"<input type='checkbox' name='bim'/>";
				echo "</td>";

                        echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
                                
				echo "<td>" . __('LEED') . "</td>";
				echo "<td>";
                                echo"<input type='checkbox' name='leed'/>";
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center '>";
				echo "<td>" . __('BREEAM') . "</td>";
				echo "<td>";
                                echo"<input type='checkbox' name='breeam'/>";
				echo "</td>";
                                
                        echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";

				echo "<td>" . __('Otros certificados') . "</td>";
				echo "<td>";
                                echo"<input type='checkbox' name='otros_certificados'/>";
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
				echo "<td colspan='6'><input type='submit' name='search' value='Filtrar' class='submit'/></td>";
			echo "</tr>";
			
echo "</table>";

include 'listSelectionSupplier.php';

function getTiposExperiencias(){
	GLOBAL $DB,$CFG_GLPI;


	$query ="SELECT name FROM `glpi_plugin_comproveedores_experiencestypes` order by id";

	$result = $DB->query($query);
	$i=1;
	while ($data=$DB->fetch_array($result)) {
		$lista[$i]=$data['name'];
		$i++;
	}

	return $lista;
}

function consultaAjax(){

    GLOBAL $DB,$CFG_GLPI;

    $consulta="<script type='text/javascript'>
		
	function cambiarCategorias(valor){

            $.ajax({  
		type: 'GET',        		
		url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/selectCategoriesAndSpecialty.php',
		data: {idRolType:valor, tipo:'categoria'},   		
		success:function(data){
                    $('#IdCategorias').html(data);
		},
		error: function(result) {
                    alert('Data not found');
		}
            });
				
	}
			
	function cambiarEspecialidades(valor){

            $.ajax({  
		type: 'GET',        		
		url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/selectCategoriesAndSpecialty.php',
		data: {idCategories:valor, tipo:'especialidad'},   		
		success:function(data){
                    $('#IdEspecialidades').html(data);
		},
		error: function(result) {
                    alert('Data not found');
		}
            });

	}
        
        function seleccionProvedorFiltro2(paquete_id){
        
            var d = new Date();
            var year = d.getFullYear();
            
            var parametros = {
	'paquete_id':paquete_id,
	'name' : $('input[name=name]').val(),
                'especialidad_id': $('input[name=plugin_comproveedores_specialties_id]').val(),
	'facturacion_year_1' : $('input[name=facturacion_'+year+']').val(),
                'facturacion_year_2' : $('input[name=facturacion_'+(year-1)+']').val(),
                'facturacion_year_3' : $('input[name=facturacion_'+(year-2)+']').val()
            };

            $.ajax({ 
                async: false, 
                type: 'GET',
            	data: parametros,                 
           	url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/selectionSupplierF2.php',                    
           	success:function(data){
                    $('#selector_proveedor').html(data);
                },
                error: function(result) {
                    alert('Data not found');
                }
            });
        };

	</script>";

    return $consulta;
}
