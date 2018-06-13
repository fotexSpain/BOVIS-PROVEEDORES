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
                echo "<td class='selector_proveedor' style='text-align:left;'>";
                        Html::autocompletionTextField($objCommonDBT,'nombre_proveedor');
                echo "</td>";

                echo "<td>" . __('Intervención de BOVIS') . "</td>";
                echo "<td class='selector_proveedor'>";
                        echo"<input type='checkbox' name='intervencion_bovis'/>";
                echo "</td>";			
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";         
                echo "<td rowspan='5'>" . __('Tipos de experiencias') . "</td>";
                        $lista=getTiposExperiencias();
                        echo "<td rowspan='5' class='selector_proveedor' >";
		echo "<div style='text-align:left; width: 150px; height: 100px; overflow-y: scroll; border: 1px solid #BDBDBD'>";
                                        foreach ($lista as $key => $value) {
			echo "&nbsp&nbsp<input type='checkbox' name='tipos_experiencias_$key' value=$key />&nbsp&nbsp".$value."<br />";
                                        }
		echo "</div>";
                echo "</td>";               
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo "<td>" . __('BIM') . "</td>";
                        echo "<td class='selector_proveedor'>";
                                echo"<input type='checkbox' name='bim'/>";
                        echo "</td>";
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo "<td>" . __('LEED') . "</td>";
	echo "<td class='selector_proveedor'>";
                        echo"<input type='checkbox' name='leed'/>";
	echo "</td>";
        echo "</tr>";

        echo"<tr class='tab_bg_1 center '>";
                echo "<td>" . __('BREEAM') . "</td>";
                echo "<td class='selector_proveedor'>";
                        echo"<input type='checkbox' name='breeam'/>";
                echo "</td>";                       
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo "<td>" . __('Otros certificados') . "</td>";
	echo "<td class='selector_proveedor'>";
                        echo"<input type='checkbox' name='otros_certificados'/>";
	echo "</td>";
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo"<td colspan='6'>";
                        echo "<span onclick='filtrarListaProveedores(".$_GET['paquete_id'].")' class='vsubmit' style='margin-right: 15px;'>FILTRAR</span>";
                 echo"</td>";
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
        
                var arrayProveedoresElegidos= new Array();

                function setListaProveedorfiltro(supplier_id){
                   
                        if($('#proveedor_'+supplier_id).prop('checked')){
                           
                           arrayProveedoresElegidos[supplier_id]=supplier_id;
                        }
                        else{
                           
                            delete  arrayProveedoresElegidos[supplier_id];
                        }
	           }
        
                function filtrarListaProveedores(paquete_id){
                
                        var experiencias_id=new Array();
                        for(var i=0; i<12; i++){
                            if($('.selector_proveedor').find('input[name=tipos_experiencias_'+i+']').prop('checked')){
                                experiencias_id.push($('.selector_proveedor').find('input[name=tipos_experiencias_'+i+']').val());
                            }
                        }
                        
                       var parametros = {
                               'actualizar_lista': 'actualizar_lista',
                               'paquete_id':paquete_id,
                               'arrayProveedoresElegidos' : arrayProveedoresElegidos,
                               'nombre_proveedor': $('.selector_proveedor > input[name=nombre_proveedor]').val(),
                               'experiencia_id': experiencias_id
                        };

                        $.ajax({  
                            type: 'GET',        		
                            url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/selectionsupplier.form.php',
                            data: parametros,   		
                            success:function(data){
                               alert(data);
                                var proveedores_quitar=data.split(',');
                                                                
                                for(var i=0;i<=proveedores_quitar.length;i++){
                                   
                                    delete  arrayProveedoresElegidos[proveedores_quitar[i]];
                                    experiencias_id.length=0;
                                    $('#proveedor_'+proveedores_quitar[i]).prop('checked', false); 
                                    
                                }
                                
                            },
                            error: function(result) {
                                alert('Data not found');
                            }
                        });
				
	}
	
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
        
                </script>";

    return $consulta;
}
