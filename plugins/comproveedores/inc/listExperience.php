<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;

$objExperiencia=new PluginComproveedoresExperience;


	
	if($_GET['tipo']=='intervencion_bovis'){

		$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id']." and intervencion_bovis='1' order by id desc";

	}elseif($_GET['tipo']=='sin_experiencia'){

		$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id']." and intervencion_bovis='0' and plugin_comproveedores_experiencestypes_id='0' order by id desc";

	}
	else{
		$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id']." and plugin_comproveedores_experiencestypes_id='".$_GET['tipo']."' and intervencion_bovis='0' order by id desc";

	}
		
		//echo consultaAjaxListExperiencia();

			$result = $DB->query($query);

			//Ocultar lista, si no existe ninguna expeciencia
			

				echo "<div class='actualizarLista' align='center'><table id='data_table_".$_GET['tipo']."' class='tab_cadre_fixehov'>";
						echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='13' style='background-color:#8cabdb;'>Experiencias del proveedor</th></tr>";
							echo"<br/>";
						echo "</tr>";
						$numero_registros=1;
						$color_titulos='';
					while ($data=$DB->fetch_array($result)) {

							
							echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";

							if($numero_registros%2==0){
								$color_titulos="style='background-color:#CED8F6;'";
							}else{
								$color_titulos="style='background-color:#E0ECF8;'";
							}
							
							
							
							echo"<th ".$color_titulos.">".__('Proy')."</th>";
							echo "<th ".$color_titulos.">".__('Estado')."</th>";
							if (Session::isMultiEntitiesMode())
								echo "<th ".$color_titulos.">".__('Entity')."</th>";
								//echo "<th ".$color_titulos.">".__('Bovis')."</th>";
								if($_GET['tipo']=='intervencion_bovis'){
									echo "<th ".$color_titulos.">".__('Exper.')."</th>";
								}
								echo "<th ".$color_titulos.">".__('CCAA')."</th>";
								echo "<th ".$color_titulos.">".__('Cliente')."</th>";
								echo "<th ".$color_titulos.">".__('Año')."</th>";
								echo "<th ".$color_titulos.">".__('Importe')."</th>";
								echo "<th ".$color_titulos.">".__('Meses')."</th>";
								echo "<th ".$color_titulos.">".__('BIM')."</th>";
								echo "<th ".$color_titulos.">".__('Bre.')."</th>";
								echo "<th ".$color_titulos.">".__('Le.')."</th>";
								echo "<th ".$color_titulos.">".__('Otr.')."</th>";
								//echo "<th ".$color_titulos.">".__('Cpd')."</th>";
								//echo "<th ".$color_titulos.">".__('Observaciones')."</th>";
								echo "<th ".$color_titulos.">".__('Modificar')."</th>";
								echo "<th ".$color_titulos."'>".__('Eliminar')."</th>";
								echo "</tr>";

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

							if(strlen($data['name']) > 25)
								{
									$proyecto = substr($data['name'], 0, 25);
									echo "<td class='center'>".$proyecto."..."."</td>";
								}
								else
									echo "<td class='center'>".$data['name']."</td>";

							if (Session::isMultiEntitiesMode())
								echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";

								echo "<td class='center'>";
								if($data['estado']=='1'){
									echo "En Curso";
								}else{
									echo "Finalizado";
								}
								//Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'),  array('value' => $data['estado']));
								echo "</td>";

								/*echo "<td class='center'>";
								if($data['intervencion_bovis']=='1'){
									//echo "Si";
									echo"<input id='checkbox_intervencion_bovis' type='checkbox' checked>";
								}else{
									echo"<input id='checkbox_intervencion_bovis' type='checkbox'>";
									//echo "No";
								}
								echo "</td>";*/
								if($_GET['tipo']=='intervencion_bovis'){
								echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_experiencestypes",$data['plugin_comproveedores_experiencestypes_id'])."</td>";
								}
								/*echo "<td class='center'>";
								Dropdown::show('PluginComproveedoresExperiencestype', array('value' => $data['plugin_comproveedores_experiencestypes_id'], 'comments' => false, 'addicon' => false));
								echo "</td>";*/

								echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_communities",$data['plugin_comproveedores_communities_id'])."</td>";

								/*echo "<td class='center'>";
								Dropdown::show('PluginComproveedoresCommunity', array('value' => $data['plugin_comproveedores_communities_id'], 'comments' => false, 'addicon' => false));
								echo "</td>";*/

								if(strlen($data['cliente']) > 25)
								{
									$cliente = substr($data['cliente'], 0, 25);
									echo "<td class='center'>".$cliente."..."."</td>";
								}
								else
									echo "<td class='center'>".$data['cliente']."</td>";

								$anio = date("Y", strtotime($data['anio']));
								$anio++;
								echo "<td class='center'>".$anio."</td>";
								/*echo "<td class='center'>";
								Dropdown::showFromArray('anio', $objExperiencia->getYears(),array('value'=>$anio));
								echo "</td>";*/

								//Formato al importe
								$importe=number_format($data['importe'], 2, ',', '.');
								
								echo "<td class='center'>".$importe."</td>";
								echo "<td class='center'>".$data['duracion']."</td>";
								echo "<td class='center'>";
								if($data['bim']=='1'){
									echo"<input id='checkbox_bim' type='checkbox' value='0' checked disabled='disabled' style='border: 2px solid #00882D;'>";
									//echo "Si";
								}else{
									echo"<input id='checkbox_bim' type='checkbox' value='1' disabled='disabled'>";
									//echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['breeam']=='1'){
									echo"<input id='checkbox_breeam' type='checkbox' value='0' checked disabled='disabled'>";
									//echo "Si";
								}else{
									echo"<input id='checkbox_breeam' type='checkbox' value='1' disabled='disabled'>";
									//echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['leed']=='1'){
									echo"<input id='checkbox_leed' type='checkbox' value='0' checked disabled='disabled'>";
									//echo "Si";
								}else{
									echo"<input id='checkbox_leed' type='checkbox' value='1' disabled='disabled'>";
									//echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['otros_certificados']=='1'){
									echo"<input id='checkbox_otros_certificados' type='checkbox' value='0' checked disabled='disabled'>";
									//echo "Si";
								}else{
									echo"<input id='checkbox_otros_certificados' type='checkbox' value='1' disabled='disabled'>";
									//echo "No";
								}
								echo "</td>";
								/*echo "<td class='center'>";
								if($data['cpd_tier']=='1'){
									echo"<input id='checkbox_cpd_tier' type='checkbox' value='0' checked disabled='disabled'>";
									//echo "Si";
								}else{
									echo"<input id='checkbox_cpd_tier' type='checkbox' value='1' disabled='disabled'>";
									//echo "No";
								}*/
								/*echo "</td>";
								echo "<td class='center'>".$data['observaciones']."</td>";*/
			
								echo"<td rowspan='2' class='center'><span class='vsubmit' onclick='modificar(".$data['id'].");' name='Update'>MODIFICAR</span></td>";
								echo "<td rowspan='2' class='center'>";
								echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php method='post'>";
								echo Html::hidden('id', array('value' => $data['id']));
								echo Html::hidden('cv_id', array('value' => $data['cv_id']));
								echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
								echo"<input title='Quitar acceso' type='submit' class='submit' value='QUITAR' name='purge'/>";
								echo "</td>";
								echo "</tr>";
								
								//echo "<tr><th ".$color_titulos." colspan='14'>".__('Observaciones')."</th></tr>";
								echo "<tr><td id='observaciones' colspan='11' class='center'>".$data['observaciones']."</td></tr>";

								echo "<tr><td colspan='13' style='border: hidden'></td></tr>";
								echo"</form>";

							$numero_registros++;

							
					}

							echo "</table></div>";
							echo"<br>";


function consultaAjaxListExperiencia(){

			GLOBAL $CFG_GLPI;

			$consulta="<script src='../js/mindmup-editabletable.js'></script>

			<script>
				$(document).ready(function(){
					
					$('#data_table_".$_GET['tipo']."').editableTableWidget();
					
					$('#data_table_".$_GET['tipo']." td').change(function() {

						/*arraycheck=['intervencion_bovis', 'bim', 'breeam', 'leed', 'cpd_tier', 'otros_certificados'];

						for(i=0;i<arraycheck.length;i++){

							var ('prueba'+arraycheck[i])='';
						}*/
						
						/////Cogemos las variable de los checkbox

						var valor_intervencion_bovis='';
						if($(this).parents('tr').find('#checkbox_intervencion_bovis').is(':checked')){
							valor_intervencion_bovis='1';
						}else{
							valor_intervencion_bovis='0';
						}
						
						var valor_bim='';
						if($(this).parents('tr').find('#checkbox_bim').is(':checked')){
							valor_bim='1';
						}else{
							valor_bim='0';
						}

						var valor_breeam='';
						if($(this).parents('tr').find('#checkbox_breeam').is(':checked')){
							valor_breeam='1';
						}else{
							valor_breeam='0';
						}

						var valor_leed='';
						if($(this).parents('tr').find('#checkbox_leed').is(':checked')){
							valor_leed='1';
						}else{
							valor_leed='0';
						}

						var valor_cpd_tier='';
						if($(this).parents('tr').find('#checkbox_cpd_tier').is(':checked')){
							valor_cpd_tier='1';
						}else{
							valor_cpd_tier='0';
						}

						var valor_otros_certificados='';
						if($(this).parents('tr').find('#checkbox_otros_certificados').is(':checked')){
							valor_otros_certificados='1';
						}else{
							valor_otros_certificados='0';
						}


						$(this).parents('tr').find('select[name=anio] option:selected').each(function() {
	      					anio=$( this ).text();
	      					anio=anio+'-00-00 00:00';
	   					});

	   					$(this).parents('tr').find('select[name=estado] option:selected').each(function() {
	      					estado=$( this ).val();
	   					});

						//Creamos el array con los valores, para la consulta
	              		var parametros = {
							'update':'GUARDAR MODIFICAR',
							'estado':estado,
							'id'	:$(this).parents('tr').find('td').eq(0).html(),
							'intervencion_bovis'	:	valor_intervencion_bovis,
							'plugin_comproveedores_experiencestypes_id':$(this).parents('tr').find('input[name=plugin_comproveedores_experiencestypes_id]').val(),
							'plugin_comproveedores_communities_id'	:$(this).parents('tr').find('[name=plugin_comproveedores_communities_id]').val(),
	                		'name' : $(this).parents('tr').find('td').eq(1).html(),
	                		'cliente' :$(this).parents('tr').find('td').eq(6).html(),
	                		'anio'	:	anio, 
	                		'importe': $(this).parents('tr').find('td').eq(8).html(),
	                		'duracion': $(this).parents('tr').find('td').eq(9).html(),
	                		'bim'	:	valor_bim,
	                		'breeam':	valor_breeam,
	                		'leed'	:	valor_leed,
	                		'otros_certificados':	valor_otros_certificados,
	                		'cpd_tier'	:	valor_cpd_tier,
	                		'observaciones'	: $(this).parents('tr').next().find('#observaciones').text()
	                		
	                	};
	           
	           			alert('entro');

	                	guardarModificacion(parametros);
		
	    			});

				});

				function guardarModificacion(parametros){	           

					$.ajax({  
						type: 'GET',  
						async: false,                
	           			url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php',                    
	           			data: parametros, 
						success:function(data){
							
	                	},
	                	error: function(result) {
	                  		 alert('Data not found');
	                	}
	            	});

	            	////////Actualizar Lista expeciencias, la tabla en que se ha creador y la tabla en la que estaba

	            	tabla_modificada='".$_GET['tipo']."';

	            	//Actualizar para quitar la experiencia de la tabla, en el caso de que cambie de tabla

	            	actualizarLista(tabla_modificada);

	            	//Actualizar para visualizar la experiencia, en el caso de que cambie de tabla
	            	if(parametros['intervencion_bovis']==1){
	            			
	            		actualizarLista('intervencion_bovis');

	            	}
	            	if(parametros['intervencion_bovis']==0){
	            			
	            		actualizarLista('sin_experiencia');

	            	}else{

	            		actualizarLista(parametros['plugin_comproveedores_experiencestypes_id']);

	            	}
							
				}

			</script>";

			return $consulta;

}
			

