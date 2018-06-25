<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

$objCommonDBT=new CommonDBTM();
$preselecionIds='';

echo consultaAjax();

echo"<table  class='tab_cadre_fixe'>";
			
        echo"<th colspan='6'>Selección Proveedor</th></tr>";
        echo"<tr class='tab_bg_1 center' >";
                echo "<td style='width:150px; text-align: center;'>" . __('Nombre') . "</td>";
                echo "<td style='width:300px; text-align: left;' class='selector_proveedor' style='text-align:left;'>";
                        Html::autocompletionTextField($objCommonDBT,'nombre_proveedor');
                echo "</td>";

                echo "<td>" . __('Intervención de BOVIS') . "</td>";
                echo "<td class='selector_proveedor' style='text-align: left;'>";
                        echo"<input type='checkbox' name='intervencion_bovis'/>";
                echo "</td>";			
        echo "</tr>";

        echo"<tr class='tab_bg_1 center' style='vertical-align: top;'>";         
                echo "<td rowspan='5'>" . __('Tipos de experiencias') . "</td>";
                        $lista=getTiposExperiencias();
                        echo "<td rowspan='5' class='selector_proveedor' >";
		echo "<div style='text-align:left; width: 300px; height: 100px; overflow-y: scroll; border: 1px solid #BDBDBD'>";
                                        foreach ($lista as $key => $value) {
			echo "&nbsp&nbsp<input type='checkbox' name='tipos_experiencias_$key' value=$key />&nbsp&nbsp".$value."<br />";
                                        }
		echo "</div>";
                echo "</td>";               
        echo "</tr>";

        echo"<tr class='tab_bg_1 center' >";
                echo "<td>" . __('BIM') . "</td>";
                        echo "<td class='selector_proveedor' style='text-align: left;'>";
                                echo"<input type='checkbox' name='bim'/>";
                        echo "</td>";
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo "<td>" . __('LEED') . "</td>";
	echo "<td class='selector_proveedor' style='text-align: left;'>";
                        echo"<input type='checkbox' name='leed'/>";
	echo "</td>";
        echo "</tr>";

        echo"<tr class='tab_bg_1 center '>";
                echo "<td>" . __('BREEAM') . "</td>";
                echo "<td class='selector_proveedor' style='text-align: left;'>";
                        echo"<input type='checkbox' name='breeam'/>";
                echo "</td>";                       
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo "<td center>" . __('Otros certificados') . "</td>";
	echo "<td class='selector_proveedor' style='text-align: left;'>";
                        echo"<input type='checkbox' name='otros_certificados'/>";
	echo "</td>";
        echo "</tr>";

        echo"<tr class='tab_bg_1 center'>";
                echo"<td colspan='4' center>";
                        echo "<span class='vsubmit' style='margin-right: 15px;' onClick='location.reload();'>ATRAS</span>";
                        echo "<span onclick='filtrarListaProveedores(".$_GET['paquete_id'].")' class='vsubmit' style='margin-right: 15px;'>FILTRAR</span>";
                 echo"</td>";
        echo "</tr>";
			
echo "</table>";

include 'listSelectionSupplier.php';

echo"<span onclick='imprimirPdf()' class='vsubmit' style='margin-right: 15px; '>IMPRIMIR</span>";
echo"<span onclick='guardarPreseleccion(".$_GET['paquete_id'].",\"$preselecionIds\")' class='vsubmit' style='margin-right: 15px; '>GUARDAR PRESELECCIÓN</span>";
echo "<span onclick='inlcuirProveedoresAlPaquete(".$_GET['paquete_id'].")' class='vsubmit' style='margin-right: 15px;'>AÑADIR PROVEEDOR</span>";
echo"<br>";
echo"<br>";
echo"<br>";

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
                
                function guardarPreseleccion( paquete_id, preselecionIds){

                        var parametros = {
                                'preseleccion_guardar': 'preseleccion_guardar',
                                'arrayPreselecion':preselecionIds,
                                'paquete_id' : paquete_id
                        };
                      
                        $.ajax({  
                                type: 'GET',        		
                                url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/selectionsupplier.form.php',
                                data: parametros,   		
                                success:function(data){ 
                                   alert(data);
                                },
                                error: function(result) {
                                    alert('Data not found');
                                }
                        });
                }
                        
                function imprimirPdf(){
                  
                                var ids='';
                                for(i=0; i<arrayProveedoresElegidos.length;i++){
                                    if(arrayProveedoresElegidos[i]!=null){
                                        ids=ids+arrayProveedoresElegidos[i]+',';
                                    }   
                                }
                                ids = ids.substring(0, ids.length-1); 
                                if(ids!=''){
                                
                                        window.open('".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/lisSelectionSupplierPDF.php?id='+ids,'_blank'); 
                                }
                                else{
                                        alert('Tienes que elegir un proveedor de la lista, para poder exportar a PDF');
                                }
                }

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
                      
                        if($('.selector_proveedor > input[name=intervencion_bovis]').prop('checked')) {	
	   	intervencion_bovis=1;
                        }else{	
		intervencion_bovis=0;
                        }

                        if($('.selector_proveedor > input[name=bim]').prop('checked')) {	
	   	bim=1;
                        }else{	
		bim=0;
                        }

                        if($('.selector_proveedor > input[name=breeam]').prop('checked')) {		
	   	breeam=1;
                        }else{	
		breeam=0;
                        }

                        if($('.selector_proveedor > input[name=leed]').prop('checked')) {
	   	leed=1;
                        }else{	
		leed=0;
                        }

                        if($('.selector_proveedor > input[name=otros_certificados]').prop('checked')) {	
	   	otros_certificados=1;
                        }else{
		otros_certificados=0;
                        }

                       var parametros = {
                                'actualizar_lista': 'actualizar_lista',
                                'paquete_id':paquete_id,
                                'arrayProveedoresElegidos' : arrayProveedoresElegidos,
                                'nombre_proveedor': $('.selector_proveedor > input[name=nombre_proveedor]').val(),
                                'experiencia_id': experiencias_id,
                                'intervencion_bovis':intervencion_bovis,
                                'bim':bim,
                                'breeam':breeam,
                                'leed':leed,
                                'otros_certificados':otros_certificados
                        };

                        $.ajax({  
                            type: 'GET',        		
                            url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/selectionsupplier.form.php',
                            data: parametros,   		
                            success:function(data){
                                
                                //Pasamos los id de los proveedore a un array
                                var proveedores_aptos=data.split(',');
                                existe_proveedor=false;
                                
                                for(var i=0;i<=arrayProveedoresElegidos.length;i++){
                                
                                        //Recorremos en array para comprobar si coinciden
                                        for(var j=0;j<=proveedores_aptos.length;j++){
                                       
                                                //Si coinciden no se elimina, cumple con los requisitos del filtro
                                                if(proveedores_aptos[j]==arrayProveedoresElegidos[i] 
                                                && proveedores_aptos[j]!=null 
                                                && arrayProveedoresElegidos[i]!=null){
       
                                                        existe_proveedor=true;
                                               }
                                        }
                                        
                                        //Si no cumple los requisitos del filtro, ponemos el checkbox a falso y lo quitamos de arrayProveedoresElegidos
                                        if(!existe_proveedor && arrayProveedoresElegidos[i]!=null){
                                        
                                                $('#proveedor_'+arrayProveedoresElegidos[i]).prop('checked', false); 
                                                delete  arrayProveedoresElegidos[i];                                                                                               
                                        }else{
                                        
                                                existe_proveedor=false;
                                        }
                                }
                               
                            },
                            error: function(result) {
                                alert('Data not found');
                            }
                        });
				
	}
        
                function inlcuirProveedoresAlPaquete(paquete_id){
                
                        
                        var numProveedores=0;
                     
                      

                        for(var i=0;i<=arrayProveedoresElegidos.length;i++){
                                
                                if(arrayProveedoresElegidos[i]!=null){
                                        numProveedores++;
                                }
                                   
                        }
                       
                        if(numProveedores==1){
                                var parametros = {
                                        'add_proveedor_al_paquete': 'add_proveedor_al_paquete',
                                        'paquete_id':paquete_id,
                                        'arrayProveedoresElegidos' : arrayProveedoresElegidos,   
                                };

                                $.ajax({  
                                    type: 'GET',        		
                                    url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/selectionsupplier.form.php',
                                    data: parametros,   		
                                    success:function(data){
                                        location.reload();
                                    },
                                    error: function(result) {
                                        alert('Data not found');
                                    }
                                });
                        }
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
