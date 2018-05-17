<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

	
	if($_GET['tipo']=='intervencion_bovis'){

		$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id']." and intervencion_bovis='1' order by id desc";

	}
	else{
		$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id']." and plugin_comproveedores_experiencestypes_id='".$_GET['tipo']."' order by id desc";

	}
		echo"<script src='../js/mindmup-editabletable.js'></script>";

		echo"<script>
			$(document).ready(function(){
				
				$('#data_table_".$_GET['tipo']."').editableTableWidget();

				 $('#data_table_".$_GET['tipo']." td').change(function() {			 	            	

              		
              		var parametros = {
						'update':'GUARDAR MODIFICAR',
						'estado':$(this).parents('tr').find('td').eq(2).html(),
						'id'	:$(this).parents('tr').find('td').eq(0).html(),
						'intervencion_bovis'	:	'1',
						'plugin_comproveedores_experiencestypes_id':$(this).parents('tr').find('td').eq(4).html(),
						'plugin_comproveedores_communities_id'	:$(this).parents('tr').find('td').eq(5).html(),
                		'name' : $(this).parents('tr').find('td').eq(1).html(),
                		'cliente' :$(this).parents('tr').find('td').eq(6).html(),
             			
                		'duracion': $(this).parents('tr').find('td').eq(9).html(),
                		'bim'	:	$(this).parents('tr').find('td').eq(10).html(),
                		'breeam':	$(this).parents('tr').find('td').eq(11).html(),
                		'leed'	:	$(this).parents('tr').find('td').eq(12).html(),
                		'otros_certificados':	$(this).parents('tr').find('td').eq(13).html(),
                		'cpd_tier'	:	$(this).parents('tr').find('td').eq(14).html(),
                		'observaciones'	: $(this).parents('tr').find('td').eq(15).html()
                		
                	};

                	guardarModificacion(parametros);

        			
    			})

    			function guardarModificacion(parametros){

    				

                	/*
                	'anio'	:	$(this).parents('tr').find('td').eq(7).html(),
                		'importe': $(this).parents('tr').find('td').eq(8).html(),
                		*/

					$.ajax({  
						type: 'GET',  
						async: false,                
           				url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php',                    
           				data: parametros, 
						success:function(data){
							alert(data);
                		},
                		error: function(result) {
                   			 alert('Data not found');
                		}
            		});

            		////////Actualizar Lista expeciencias, la tabla en que se ha creador y la tabla en la que estaba

            		tipo_experiencia_id=$('input[name=plugin_comproveedores_experiencestypes_id]').val();

            		// Si la experiencia ahora es de bovis o otros tipo de esperiencia, actualizamos la lista para que aparezca
            		if(intervencion_bovis){
            			
            			actualizarLista('intervencion_bovis', 'intervencion_bovis');

            		}else{
            			
            			actualizarLista(tipo_experiencia_id, 'tipo_experiencia_'+tipo_experiencia_id);

            		}

            		//Si antes la experiencia era de bovis o algún tipo de experiencia, actualizamos la lista para que no aparezca
            		if(intervencion_bovis_antigua){
            			
            			actualizarLista('intervencion_bovis', 'intervencion_bovis');

            		}else{
            			
            			actualizarLista(tipo_experiencia_id, 'tipo_experiencia_'+tipo_experiencia_id);

            		}

						
				}

			})
		</script>";

			$result = $DB->query($query);

			//Ocultar lista, si no existe ninguna expeciencia
			

				echo "<div class='actualizarLista' align='center'><table id='data_table_".$_GET['tipo']."' class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Experiencias del proveedor</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Proyecto/Obra')."</th>";
				echo "<th>".__('Estado')."</th>";
				if (Session::isMultiEntitiesMode())
					echo "<th>".__('Entity')."</th>";
					echo "<th>".__('Intervención Bovis')."</th>";
					echo "<th>".__('Tipo de experiencia')."</th>";
					echo "<th>".__('Comunidad autonoma')."</th>";
					echo "<th>".__('Cliente/Propiedad')."</th>";
					echo "<th>".__('Año')."</th>";
					echo "<th>".__('Importe contratado')."</th>";
					echo "<th>".__('Duración de su contrato')."</th>";
					echo "<th>".__('BIM')."</th>";
					echo "<th>".__('Bream')."</th>";
					echo "<th>".__('Leed')."</th>";
					echo "<th>".__('Otros')."</th>";
					echo "<th>".__('Cpd tier')."</th>";
					echo "<th>".__('Observaciones')."</th>";
					echo "<th>".__('Modificar')."</th>";
					echo "<th>".__('Eliminar')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result)) {
							if($data['is_deleted']==""){
								$data['is_deleted']=1;
							}

							echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
							/*if ((in_array($data['entities_id'],$_SESSION['glpiactiveentities']))) {
								echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php?id=".$data["id"]."'>".$data["name"];
								if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
								echo "</a></td>";
							} else {
								echo "<td class='center'>".$data["name"];
								if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
								echo "</td>";
							}
							echo "</a></td>";*/

							echo "<td style='display:none;' class='center'>".$data['id']."</td>";

							echo "<td class='center'>".$data['name']."</td>";

							if (Session::isMultiEntitiesMode())
								echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";

								echo "<td class='center'>";
								if($data['estado']=='1'){
									echo "En Curso";
								}else{
									echo "Finalizado";
								}
								echo "</td>";

								echo "<td class='center'>";
								if($data['intervencion_bovis']=='1'){
									echo "Si";
								}else{
									echo "No";
								}
								echo "</td>";

								echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_experiencestypes",$data['plugin_comproveedores_experiencestypes_id'])."</td>";

								echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_communities",$data['plugin_comproveedores_communities_id'])."</td>";

								echo "<td class='center'>".$data['cliente']."</td>";
								$anio = date("Y", strtotime($data['anio']));
								$anio++;
								echo "<td class='center'>".$anio."</td>";

								//Formato al importe
								$importe=number_format($data['importe'], 2, ',', '.');
								
								echo "<td class='center'>".$importe."</td>";
								echo "<td class='center'>".$data['duracion']."</td>";
								echo "<td class='center'>";
								if($data['bim']=='1'){
									echo "Si";
								}elseif($data['bim']=='0'){
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['breeam']=='1'){
									echo "Si";
								}elseif($data['breeam']=='0'){
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['leed']=='1'){
									echo "Si";
								}elseif($data['leed']=='0'){
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['otros_certificados']=='1'){
									echo "Si";
								}elseif($data['otros_certificados']=='0'){
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['cpd_tier']=='1'){
									echo "Si";
								}elseif($data['cpd_tier']=='0'){
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>".$data['observaciones']."</td>";
			
								echo"<td class='center'><span class='vsubmit' onclick='modificar(".$data['id'].");' name='Update'>MODIFICAR</span></td>";
								echo "<td class='center'>";
								echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php method='post'>";
								echo Html::hidden('id', array('value' => $data['id']));
								echo Html::hidden('cv_id', array('value' => $data['cv_id']));
								echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
								echo"<input title='Quitar acceso' type='submit' class='submit' value='QUITAR' name='purge'/>";
								echo "</td>";
								echo"</form>";

							
					}

							echo "</table></div>";
							echo"<br>";

			

