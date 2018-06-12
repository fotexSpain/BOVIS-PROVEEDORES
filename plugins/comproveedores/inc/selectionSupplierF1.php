<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

$objCommonDBT=new CommonDBTM();
$opt['specific_tags']=array('onchange' => 'cambiarCategorias(value)');
$opt['comments']= false;
$opt['addicon']= false;

echo consultaAjax();

echo"<table class='tab_cadre_fixe'>";
			
    echo"<th colspan='6'>Filtro Proveedores</th></tr>";

	echo"<tr class='tab_bg_1 center'>";
            echo "<td>Nombre</td>";
            echo "<td>";
                Html::autocompletionTextField($objCommonDBT,'nombre_proveedor');
            echo "</td>";

            echo "<td>Facturación ".(date("Y"))."</td>";
            echo "<td>";
                Html::autocompletionTextField($objCommonDBT,'facturacion_'.(date("Y")));
            echo "</td>";
            echo "<td style='text-align:left'>x1000€</td>";
               
        echo "</tr>";

	echo"<tr class='tab_bg_1 center'>";
            echo "<td>Contratista/Proveedor</td>";
            echo "<td>";
            Dropdown::show('PluginComproveedoresRoltype',$opt);
            echo "</td>";

            echo "<td>Facturación ".(date("Y")-1)."</td>";
            echo "<td>";
                Html::autocompletionTextField($objCommonDBT,'facturacion_'.(date("Y")-1));
            echo "</td>";
            echo "<td style='text-align:left'>x1000€</td>";
	echo "</tr>";
        
        echo"<tr class='tab_bg_1 center'>";
           echo "<td>Categorias</td>";
            echo "<td>";
                echo "<div id='IdCategorias'>";
                echo "<span class='no-wrap'>
                    <div class='select2-container'>
                    <a class='select2-choice'>
                    <span class='select2-chosen'>------</span>
                    </a>
                    </div>
                    </span>";
                echo "</div>";
            echo "</td>";

            echo "<td>Facturación ".(date("Y")-2)."</td>";
            echo "<td>";
                Html::autocompletionTextField($objCommonDBT,'facturacion_'.(date("Y")-2));
            echo "</td>";
            echo "<td style='text-align:left'>x1000€</td>";
	echo "</tr>";
        
        echo"<tr class='tab_bg_1 center'>";
            echo "<td>" . __('Especialidades') . "</td>";
            echo "<td>";
                echo "<div id='IdEspecialidades'>";
		echo "<span class='no-wrap'>
                    <div class='select2-container'>
                    <a class='select2-choice'>
                    <span class='select2-chosen'>------</span>
                    </a>
                    </div>
                    </span>";
		echo "</div>";
            echo "</td>";
	echo "</tr>";
        
        
        
            echo"<tr class='tab_bg_1 center'>";
		echo "<td colspan='6'onclick='seleccionProvedorFiltro(".$_GET['paquete_id'].")'><span  class='vsubmit' style='margin-right: 15px;'>SIGUIENTE</span></td>";
            echo "</tr>";
			
echo "</table>";


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
        
        function seleccionProvedorFiltro(paquete_id){
        
            var d = new Date();
            var year = d.getFullYear();
            
            var parametros = {
	'paquete_id':paquete_id,
	'nombre_proveedor' : $('input[name=nombre_proveedor]').val(),
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
