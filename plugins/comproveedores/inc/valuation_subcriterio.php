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
                    $contrato_id=$_GET['contrato_id'];
                                     
                    $query2="select 
                                    (select GROUP_CONCAT(criterio3.id ORDER BY criterio3.id asc) from glpi_plugin_comproveedores_criterios as criterio3 where criterio3.tipo_especialidad=".$_GET['tipo_especialidad'].") as total_ids_subcriterios,
                                    (select count(*) from glpi_plugin_comproveedores_criterios as criterio2 where criterio2.criterio_padre=criterio.criterio_padre and criterio2.tipo_especialidad=".$_GET['tipo_especialidad'].") as num_subcriterios,
                                    (select GROUP_CONCAT(criterio4.id ORDER BY criterio4.id asc) from glpi_plugin_comproveedores_criterios as criterio4 where criterio4.criterio_padre=criterio.criterio_padre and criterio4.tipo_especialidad=".$_GET['tipo_especialidad'].") as num_ids_criterio,
                                    criterio.id as criterio_id,
                                    criterio.criterio_padre, 
                                    criterio.criterio_hijo,
                                    criterio.ponderacion,
                                    criterio.denom_Mala,
                                    criterio.denom_Excelente
                                    from glpi_plugin_comproveedores_criterios as criterio 
                                    where criterio.tipo_especialidad=".$_GET['tipo_especialidad']." order by criterio.id";
                        
                    $result2 = $DB->query($query2);

                    //formato de fecha yyyy-mm-dd
                    $_SESSION['glpidate_format']=0;
                                
                                if(!isset($_GET['id'])){
                                        $display="-webkit-box";
                                }else{
                                        $display="none";
                                }
                                                                       
                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
                                 echo "<tr>";
                                            echo"<td colspan='8' style=' border-bottom: none;'><div id='visualizar_ultima_eval' style='display: ".$display."; font-size: 14px;'>Evaluación Final&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
                                                    echo"<input id='evaluacion_final' style='width:17px;height:17px;' type='checkbox'/>";
                                            echo"</td>";
                                echo "</tr>";
                                echo "<tr>";
                    
                                echo"<td colspan='1' style=' border-bottom: none;'><div id='fecha_valoracion' style='display: -webkit-box; font-size: 14px;'>Fecha de valoración&nbsp"; 
                                        Html::showDateTimeField("fecha");
                                echo"</div></td>";
                              
                                //Si es una nueva evaluación que aparezca el Evaluación final. 
                                //Esta con display para el caso en que se modifica la ultima evaluación, para que pueda desmarcar y crear nuevas
                                
                                echo"<td style='font-size: 14px; border-bottom: none;'>Comentario</td>";
                                echo"<td colspan='6' class='center' style='font-weight:bold; border-bottom: none;'><textarea  id='comentario' rows='4' cols='60' style='resize: none'></textarea></td>";
                            
                                
                                
                                
                    echo"</tr>";
                   
                    echo "<tr class=' tab_cadre_fixehov nohover'><th colspan='8' >Evaluación</th></tr>";
                    echo"<br/>";
                    echo "<tr><th></th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Mal')."</th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Pobre')."</th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Adecuado')."</th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Bien')."</th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Excelente')."</th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('%')."</th>";
                        echo "<th style='width: 60px; background-color:#D8D8D8; border: 1px solid #BDBDDB;'>".__('Total')."</th>";
                    echo "</tr>";
                   
                        $cambio_criterio_padre='';                        
                        
                        $nombre_criterio='';
                        $columna_par_impar=0;
                         while ($data=$DB->fetch_array($result2)) {
                                
                                //Color criterios
                                if ($nombre_criterio!=$data['criterio_padre']){
                                    
                                        $nombre_criterio=$data['criterio_padre'];
                                        
                                        if($columna_par_impar==0){
                                                $color_criterio='#d8d8d8';
                                                $columna_par_impar=1;
                                        }else{
                                                $color_criterio='#f3f3f3';
                                                $columna_par_impar=0;
                                        }
                                }
                             
                                $total_ids_subcriterios=$data['total_ids_subcriterios'];

                                echo "<tr class='tab_bg_2' style='height:60px;'>";
                                        $criterio_padre=str_replace ( '_' , ' ' , $data['criterio_padre']);
                                        $criterio_padre=ucfirst($criterio_padre);
                                        echo"<td class='center' style='background-color:".$color_criterio."; border: 1px solid #BDBDDB;'><div style='font-weight: bold;'>".$criterio_padre."</div><br><div>".$data['criterio_hijo']."</div></td>";
                                        echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_1' title='".$data['denom_Mala']."' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(1,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                        echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_2' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(2,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                        echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_3' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(3,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                        echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_4' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(4,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                        echo"<td class='center' id='criterio_".$data['criterio_id']."_valor_5' title='".$data['denom_Excelente']."' style='font-weight:bold; border: 1px solid #BDBDDB;' onclick='valorElegido(5,".$data['criterio_id'].", \"".$data['num_ids_criterio']."\", \"".$data['criterio_padre']."\")'></td>";
                                        echo"<td class='center' id='criterio_".$data['criterio_id']."_porcentaje' style='font-weight:bold; border: 1px solid #BDBDDB;'>".$data['ponderacion']."%</td>";

                                        if($cambio_criterio_padre!=$data['criterio_padre']){
                                            $cambio_criterio_padre=$data['criterio_padre'];
                                            echo"<td rowspan='".$data['num_subcriterios']."' id='criterio_padre_".$data['criterio_padre']."' class='center' style='font-weight:bold; border: 1px solid #BDBDDB;'></td>";
                                        }
                                    
                                echo "</tr>";  
                                
                                
                                
                         }
                    echo"<br/>";
                    echo "</table></div>";
                      
                        if(isset($_GET['id'])){
                        $query="select Subvaloraciones.*,
                                criterio.*,
                                valoracion.fecha,
                                valoracion.comentario,
                                valoracion.projecttasks_id as contrato_id,
                                valoracion.evaluacion_final as evaluacion_final,
                                (select GROUP_CONCAT(id ORDER BY id asc) from glpi_plugin_comproveedores_criterios as criterio3 where criterio3.tipo_especialidad=".$_GET['tipo_especialidad']." and  criterio3.criterio_padre=criterio.criterio_padre) as num_ids_criterio,
                                (select valoracion2.id from glpi_plugin_comproveedores_valuations as valoracion2 where valoracion2.projecttasks_id=valoracion.projecttasks_id order by valoracion2.id desc limit 1) as id_ultima_evaluación
                                from glpi_plugin_comproveedores_subvaluations as Subvaloraciones 
                                left join glpi_plugin_comproveedores_criterios as criterio on Subvaloraciones.criterio_id=criterio.id
                                left join glpi_plugin_comproveedores_valuations as valoracion on valoracion.id=Subvaloraciones.valuation_id
                                where Subvaloraciones.valuation_id=".$_GET['id']." order by criterio_id asc";                   
                        
                            $result = $DB->query($query);
                                            
                                //Creamos un script donde se cagarán los valores de la consulta
                                echo"<script type='text/javascript'>      
                                       $( function() {";
                                                $evaluacion_final;
                                                while ($data=$DB->fetch_array($result)) {
                                                    
                                                        $valoracion_id=$data['valuation_id'];
                                                        $contrato_id=$data['contrato_id'];
                                                        $fecha=$data['fecha'];
                                                        $comentario=$data['comentario'];
                                                        $ultima_evaluacion_guardada_id=$data['id_ultima_evaluación'];
                                                       
                                                        echo"valorElegido(".$data['valor'].", ".$data['criterio_id'].", \"".$data['num_ids_criterio']."\",\"".$data['criterio_padre']."\");"; 
                                                        
                                                        //Si la evaluación tiene marcado el check de ultima evaluación, que pueda quitarlo y sequir creardo evaluaciones.
                                                        $evaluacion_final=$data['evaluacion_final'];
                                                }
                                                //Es la evaluación final(marco el check de evaluación final)
                                                if($evaluacion_final==1 ){
                                                                   
                                                        echo"$('#visualizar_ultima_eval').attr('style', 'display:-webkit-box; font-size: 14px;');";
                                                        echo"$('#evaluacion_final').attr('checked',true);";
                                                } 
                                                //Es la última evaluación (pero no marco el check de evaluación final, al modificar pueda ponerlo como evaluación final)
                                                if($ultima_evaluacion_guardada_id==$valoracion_id &&  $evaluacion_final==0){
                                                                   
                                                        echo"$('#visualizar_ultima_eval').attr('style', 'display:-webkit-box; font-size: 14px;');";
                                                        echo"$('#evaluacion_final').attr('checked',false);";
                                                } 
                                                //Les pasamos el valor a los input de fecha de valoración
                                                echo"$('#fecha_valoracion').find('input[name=_fecha]').val('".$fecha."');";    
                                                echo"$('#fecha_valoracion').find('input[name=fecha]').val('".$fecha."');";    
                                                echo "$('#comentario').html('".$comentario."');";
                                echo"});</script>";
                        }
                        echo "<br><br>";
                        
                        echo"<div  id='boton_formulario'>";
                            
                                echo "<span class='vsubmit' style='margin-right: 15px;' onClick='location.reload();'>VOLVER A LA LISTA</span>";
                                if(isset($_GET['id'])){

                                    echo "<span onclick='guardarYModificarSubValoracion(\"".$total_ids_subcriterios."\", $contrato_id,$valoracion_id, ".$_GET['tipo_especialidad'].", \"update_valoracion\")' class='vsubmit' style='margin-right: 15px;'>MODIFICAR EVALUACIÓN</span>";
                                }  
                                else{
                                    $contrato_id=$_GET['contrato_id'];
                                    echo "<span onclick='guardarYModificarSubValoracion(\"".$total_ids_subcriterios."\", $contrato_id,-1, ".$_GET['tipo_especialidad'].", \"add_valoracion\")'class='vsubmit' style='margin-right: 15px;'>GUARDAR EVALUACIÓN</span>";      
                                }   
                                
                        echo"</div>";
                        echo"<br>";
                        echo"<br>";
                                
                                
  function consultaAjax(){
                GLOBAL $DB,$CFG_GLPI;
                $resultado="<script type='text/javascript'>  
                    var arraySubValoracionValor = [];
                    //var arraySubValoracionComentario = [];

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
                                var inicio=Number(arrayIdsCriterio[0]);
                                var fin=Number(arrayIdsCriterio[arrayIdsCriterio.length-1]);
                               
                                
                                for(i=inicio;i<=fin;i++){
                                   
                                    //Comprobamos que existe el valor del criterio en el array
                                    if(typeof arraySubValoracionValor[i] == 'undefined'){
                                            calcularTotal=false;
                                    }
                                }
                                
                                //Calculamos el total para el criterio_padre elegido
                               if(calcularTotal==true){
                                        total_criterio=0;
                                        for(i=inicio;i<=fin;i++){
                                        
                                                porcentaje=$('#criterio_'+i+'_porcentaje').text();
                                                porcentaje=Number(porcentaje.substring(0,porcentaje.length-1));

                                                total_criterio+=(arraySubValoracionValor[i]*porcentaje)/100;
                                        
                                        }
                                        total_criterio=Math.round(total_criterio * 100) / 100
                                        $('#criterio_padre_'+criterio_padre).html(total_criterio);
                                       
                                }

                        }

                        function guardarYModificarSubValoracion(total_ids_subcriterios, contrato_id, valoracion_id, tipo_especialidad, metodo){
                               
                               //Comprobamos que esten todo los valores rellenos
                               valores_completados=true;
                               var arrayTotalIdsSubcriterios = total_ids_subcriterios.split(',');
                              
                               for(i=arrayTotalIdsSubcriterios[0];i<=arrayTotalIdsSubcriterios[arrayTotalIdsSubcriterios.length-1];i++){
                                   
                                    if(typeof arraySubValoracionValor[i] == 'undefined'){
                                        valores_completados=false;
                                    }    
                                }
                                
                                //Si toda las subvaloraciones estan rellenas, las guardamos
                                if(valores_completados==true){
                                        //Guardamos los valores de los comentarios
                                        /*for(i=i=arrayTotalIdsSubcriterios[0];i<=arrayTotalIdsSubcriterios[arrayTotalIdsSubcriterios.length-1];i++){
                                                arraySubValoracionComentario[i]=$('#criterio_'+i+'_comentario').val();        
                                        }*/
                                    
                                        if($('#evaluacion_final').prop('checked')) {	
                                                eval_final=1;
                                        }else{	
                                                eval_final=0;
                                        }

                                        //Guardamos las subvaloraciones
                                        var parametros = {
                                            'metodo':metodo,
                                            'guardarSubvaloraciones': '',
                                            'comentario': $('#comentario').val(),
                                            'tipo_especialidad':tipo_especialidad,
                                            'arraySubValoracionValor':arraySubValoracionValor,                                            
                                            'valoracion_id':valoracion_id,
                                            'fecha':$('#fecha_valoracion').find('input[name=fecha]').val(), 
                                            'contrato_id':contrato_id,
                                            'cv_id':$('#evaluacion').find('input[name=cv_id]').val(),
                                            'eval_final': eval_final
                                        };
                                        
                                        //'arraySubValoracionComentario':arraySubValoracionComentario,

                                        $.ajax({ 
                                            type: 'GET',
                                            data: parametros,                  
                                            url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/valuation.form.php',                    
                                            success:function(){
                                               
                                                location.reload();
                                            },
                                            error: function(result) {
                                                alert('Data not found');
                                            }
                                        });

                                }
                        }   
                 </script>";
                
                return $resultado;
            }      