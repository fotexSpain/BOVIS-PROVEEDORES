<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;
                                   
                    echo"<script type='text/javascript'>                                           
                               
                        var arrayValoracion = [];

                        for ( var i = 1; i <=3; i++ ) {
                            arrayValoracion[i] = []; 
                        }
                    </script>";
                    
                    echo consultaAjax();
                        
                    $valoracion=0;
                    $paquete_id=0;
                    
                    $query2="select 
                                    (select count(*) from glpi_plugin_comproveedores_criterios as criterio2 where criterio2.criterio_padre=criterio.criterio_padre) as num_subcriterios,
                                    (select GROUP_CONCAT(id) from glpi_plugin_comproveedores_criterios as criterio3 where criterio3.criterio_padre=criterio.criterio_padre) as num_ids_criterio,
                                    criterio.id as criterio_id,
                                    criterio.criterio_padre, 
                                    criterio.criterio_hijo,
                                    criterio.ponderacion,
                                    criterio.denom_Mala,
                                    criterio.denom_Excelente
                                    from glpi_plugin_comproveedores_criterios as criterio 
                                    left join glpi_plugin_comproveedores_subvaluations as subvaloracion on subvaloracion.criterio_id=criterio.id";
                        
                    $result2 = $DB->query($query2);
                                      
                   //sacar todo los
                    
                    //formato de fecha yyyy-mm-dd
                   /* $_SESSION['glpidate_format']=0;
                    echo "<div id='fecha_valoracion_".$valoracion."' style='text-align:left; display: -webkit-box;'>";
                                echo"<div style='margin-right:10px; position: relative; top: 3px;'>Fecha de valoración</div>";
                                echo"<div>";
                                Html::showDateTimeField("fecha");
                                echo"</div>";
                    echo"</div>";*/
                                                                       
                    echo "<div align='center'><table class='tab_cadre_fixehov'>";
                    echo "<tr class=' tab_cadre_fixehov nohover'><th colspan='14' >Evaluación</th></tr>";
                    echo"<br/>";
                    echo "<tr><th></th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Mal')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Pobre')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Adecuado')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Bien')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Excelente')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Comentario')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('%')."</th>";
                        echo "<th style='width: 100px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Total')."</th>";
                    echo "</tr>";

                        $cambio_criterio_padre='';                        
                                                    
                         while ($data=$DB->fetch_array($result2)) {

                                echo "<tr class='tab_bg_2' style='height:60px;'>";
                                    echo"<td class='center' style='background-color:#D8D8D8; border: 1px solid #BDBDDB;'><span style='font-weight: bold;'>".$data['criterio_padre']."</span>\n".$data['criterio_hijo']."</td>";
                                    echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_1' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(1,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                    echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_2' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(2,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                    echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_3' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(3,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                    echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_4' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(4,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                    echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_5' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(5,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                    echo"<td class='center' style='font-weight:bold; border: 1px solid #BDBDDB;'><textarea  id='criterio_".$data['criterio_id']."_comentario' rows='4' cols='45' style='resize: none'></textarea></td>";
                                    echo"<td class='center' id='criterio_".$data['criterio_id']."_porcentaje' style='font-weight:bold; border: 1px solid #BDBDDB;'>".$data['ponderacion']."%</td>";
                                    
                                    if($cambio_criterio_padre!=$data['criterio_padre']){
                                        $cambio_criterio_padre=$data['criterio_padre'];
                                        echo"<td rowspan='".$data['num_subcriterios']."' id='criterio_padre_".$data['criterio_padre']."' class='center' style='font-weight:bold; border: 1px solid #BDBDDB;'></td>";
                                    }
                                    
                                echo "</tr>";    
                            
                         }
                    echo"<br/>";
                    echo "</table></div>";
                    
                        $query="select *,
valoracion.num_evaluacion,
valoracion.projecttasks_id as contrato_id,
(select GROUP_CONCAT(id) from glpi_plugin_comproveedores_criterios as criterio3 where criterio3.criterio_padre=criterio.criterio_padre) as num_ids_criterio 
from glpi_plugin_comproveedores_subvaluations as Subvaloraciones 
left join glpi_plugin_comproveedores_criterios as criterio on Subvaloraciones.criterio_id=criterio.id
left join glpi_plugin_comproveedores_valuations as valoracion on valoracion.id=Subvaloraciones.valuation_id
where Subvaloraciones.valuation_id=".$_GET['id']." order by criterio_id asc";                   
                        
                            $result = $DB->query($query);
                            //$contenido_valoracion=1;             
                           
                                            
                                //Creamos un script donde se cagarán los valores de la consulta
                                echo"<script type='text/javascript'>      
                                       $( function() {";
                                                
                                                while ($data=$DB->fetch_array($result)) {
                                                    
                                                        $num_valoracion=$data['num_evaluacion'];
                                                        $valoracion_id=$data['valuation_id'];
                                                        $paquete_id=$data['contrato_id'];
                                
                                                        
                                                        echo "$('#criterio_".$data['criterio_id']."_comentario').html('".$data['comentario']."');";
                                                        echo"valorElegido(".$data['valor'].", ".$data['criterio_id'].", \"".$data['num_ids_criterio']."\",\"".$data['criterio_padre']."\");";
                                                }
                                               
                                        //Les pasamos el valor a los input de fecha de valoración
                                        //echo"$('#fecha_valoracion_".$valoracion."').find('input[name=_fecha]').val('".$data['fecha']."');";    
                                        //echo"$('#fecha_valoracion_".$valoracion."').find('input[name=fecha]').val('".$data['fecha']."');";    
                                echo"});</script>";
                                    
                            echo "<br><br>";
                            echo"<div  id='boton_guardar_$valoracion'>";
                                if($result->num_rows!=0){ 

                                    echo "<span onclick='guardarYModificarSubValoracion($paquete_id,$num_valoracion,$valoracion_id,\"update_valoracion\")' class='vsubmit' style='margin-right: 15px;'>MODIFICAR EVALUACIÓN</span>";
                                }  
                                else{

                                    echo "<span onclick='guardarYModificarSubValoracion($paquete_id,$valoracion,-1,\"add_valoracion\")'class='vsubmit' style='margin-right: 15px;'>GUARDAR EVALUACIÓN</span>";      
                                }   
                                echo"</div>";
                                echo"<br>";
                                echo"<br>";
                                
                                
  function consultaAjax(){
                GLOBAL $DB,$CFG_GLPI;
                $resultado="<script type='text/javascript'>  
                    var arraySubValoracionValor = [];
                    var arraySubValoracionComentario = [];

                        function valorElegido(valor_criterio, tipo_criterio, num_subcriterios, criterio_padre){
                        

                                for(i=1;i<=5;i++){
                                        if(valor_criterio==i){

                                                $('#criterio_'+tipo_criterio+'_valor_'+i).css({
                                                    'background-image':'url(".$CFG_GLPI["root_doc"]."/pics/valoracion_'+valor_criterio+'.png)',
                                                    'background-repeat':'no-repeat',
                                                    'background-position':'center'});
                                                $('#criterio_'+tipo_criterio+'_valor_'+i).html(valor_criterio);

                                                //añadimos el valor elegido a arraySubValoracionValor
                                                arraySubValoracionValor[tipo_criterio]=valor_criterio;  
                                        }
                                        else{
                                                $('#criterio_'+tipo_criterio+'_valor_'+i).css({'background-image':'none'});
                                                $('#criterio_'+tipo_criterio+'_valor_'+i).html('');
                                        }                                  
                                }    
                                
                                totalSubvaloracion(num_subcriterios, criterio_padre);
                        }
                        
                         //Comprobamos que todo los valores de un criterio esten rellenos para sacar el total
                        function totalSubvaloracion(num_subcriterios, criterio_padre){
                        
                                calcularTotal=true;
                                
                                var arrayIdsCriterio = num_subcriterios.split(',');
                               
                                for(i=arrayIdsCriterio[0];i<=arrayIdsCriterio[arrayIdsCriterio.length-1];i++){

                                        //Comprobamos que existe el valor del criterio en el array
                                        if(typeof arraySubValoracionValor[i] == 'undefined'){
                                                calcularTotal=false;
                                        }
                            
                                }
                                
                                //Calculamos el total para el criterio_padre elegido
                               if(calcularTotal){
                                        total_criterio=0;
                                        for(i=arrayIdsCriterio[0];i<=arrayIdsCriterio[arrayIdsCriterio.length-1];i++){
                                        
                                                porcentaje=$('#criterio_'+i+'_porcentaje').text();
                                                porcentaje=Number(porcentaje.substring(0,porcentaje.length-1));

                                                total_criterio+=(arraySubValoracionValor[i]*porcentaje)/100;

                                                
                                        
                                        }
                                        total_criterio=Math.round(total_criterio * 100) / 100
                                        $('#criterio_padre_'+criterio_padre).html(total_criterio);
                                       
                                }

                        }

                        function guardarYModificarSubValoracion(paquete_id, numero_valoracion, valoracion_id, metodo){
                        
                                //Guardamos los valores de los comentarios
                                for(i=1;i<=4;i++){
                                        arraySubValoracionComentario[i]=$('#criterio_'+i+'_comentario').val();        
                                }

                                //Guardamos las subvaloraciones
                                    var parametros = {
                                        'guardarSubvaloraciones': '',
                                        'arraySubValoracionValor':arraySubValoracionValor,
                                        'arraySubValoracionComentario':arraySubValoracionComentario,
                                        'valoracion_id':valoracion_id
                                    };
                                                                              
                                    $.ajax({ 
                                        type: 'GET',
                                        data: parametros,                  
                                        url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php',                    
                                        success:function(data){
                                        //alert(data);
                                        },
                                        error: function(result) {
                                            alert('Data not found');
                                        }
                                    });
                                    
                                //guardamos las valoraciones
                            
                        }   
                 </script>";
                
                return $resultado;
            }      