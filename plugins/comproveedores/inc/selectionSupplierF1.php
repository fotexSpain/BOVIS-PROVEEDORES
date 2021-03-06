<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

$objCommonDBT=new CommonDBTM();

$tipo_especialidad=$_GET['tipo_especialidad'];

$opt_categoria['condition']='glpi_plugin_comproveedores_categories.glpi_plugin_comproveedores_roltypes_id='.$tipo_especialidad;
$opt_categoria['specific_tags']=array('onchange' => 'cambiarEspecialidades(value)');
$opt_categoria['comments']= false;
$opt_categoria['width']= 155;

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
    
                echo "<td>Categorias</td>";
                echo "<td>";
                    echo "<div id='IdCategorias'>";
                            Dropdown::show('PluginComproveedoresCategory',$opt_categoria);              
                echo "</td>";

                echo "<td>Facturación ".(date("Y")-1)."</td>";
                echo "<td>";
                    Html::autocompletionTextField($objCommonDBT,'facturacion_'.(date("Y")-1));
                echo "</td>";
                echo "<td style='text-align:left'>x1000€</td>";
        echo "</tr>";
        
        echo"<tr class='tab_bg_1 center'>";
           
                 echo"<tr class='tab_bg_1 center'>";
                echo "<td>" . __('Especialidades') . "</td>";
                echo "<td>";
                    echo "<div id='IdEspecialidades'>";
                    echo "<span  class='no-wrap'>
                                                    <div class='select2-container'>
                                                    <a class='select2-choice'>
                                                    <span class='select2-chosen' style='width:140px;'>------</span>
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
                        echo "<td colspan='6'>";
                                echo "<span class='vsubmit' style='margin-right: 15px;' onClick='location.reload();'>ATRAS</span>";
                                echo"<span  class='vsubmit' style='margin-right: 15px;' onclick='seleccionProvedorFiltro(".$_GET['paquete_id'].")'>SIGUIENTE</span>";
                        echo "</td>";
            echo "</tr>";
			
echo "</table>";


function consultaAjax(){

    GLOBAL $DB,$CFG_GLPI;

    $consulta="<script type='text/javascript'>
		
        function cambiarEspecialidades(valor){

            $.ajax({  
		type: 'GET',        		
		url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/selectCategoriesAndSpecialty.php',
		data: {idCategories:valor, tipo:'especialidad', width:'155'},   		
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
                'PrimerFiltro':true,
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
