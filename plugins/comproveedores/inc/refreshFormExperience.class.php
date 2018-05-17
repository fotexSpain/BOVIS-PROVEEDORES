<?php


use Glpi\Event;


include ("../../../inc/includes.php");




GLOBAL $DB,$CFG_GLPI;


			$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE id=".$_GET['idExperiencia'];

			$objCommonDBT=new CommonDBTM;
			$objExperiencia=new PluginComproveedoresExperience;

			$result = $DB->query($query);


			while ($data=$DB->fetch_array($result)) {

			echo "<script type='text/javascript'>


				$(document).ready(function() {

					if($('#intervencionBovis').find('input').prop('checked')) {

						$('.tipos_experiencias').hide();

					}
					else{

						$('.tipos_experiencias').show();

					}


				});



				//añadimos onchange al desplegable de Intervención de BOVIS
					$('#intervencionBovis').find('input').change(function() {

   						if($('#intervencionBovis').find('input').prop('checked')) {
   							
   							$('.tipos_experiencias').hide();

						}else{
							
							$('.tipos_experiencias').show();

						}

					});

			</script>";



			$opt3['comments']= false;
			$opt3['addicon']= false;
			$opt3['value']=  $data["plugin_comproveedores_communities_id"];

			$opt2['comments']= false;
			$opt2['addicon']= false;
			$opt2['value']=  $data["plugin_comproveedores_experiencestypes_id"];
			
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";

			echo Html::hidden('idExperiencia',array('value' => $_GET['idExperiencia']));

			echo"<th colspan='33'>Experiencia</th></tr>";
			echo"<tr class='tab_bg_1 center'>";

			echo "<td>" . __('Nombre proyecto') . "</td>";

			echo "<td>" . __('Estado') . "</td>";
			
			echo "<td>" . __('Intervención de BOVIS') . "</td>";
			
			echo "<td class='tipos_experiencias'>" . __('Tipos de experiencias') . "</td>";
			
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			
			echo "<td>" . __('Cliente') . "</td>";
			
			echo "<td>" . __('Año') . "</td>";
			
			echo "<td>" . __('Importe contratado') . "</td>";
			
			echo "<td>" . __('Duración de su contratado') . "</td>";
			
			echo "<td>" . __('BIM') . "</td>";
			
			echo "<td>" . __('Breeam') . "</td>";
			
			echo "<td>" . __('Leed') . "</td>";
			
			echo "<td>" . __('Otros certificados') . "</td>";
			
			echo "<td>" . __('Cpd Tier') . "</td>";
			
			echo "<td>" . __('Observaciones') . "</td>";
		
			/*echo"<td rowspan='2'><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<td rowspan='2'><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete'>AÑADIR SIN BORRAR</span></td>";
			echo"<td rowspan='2'><span class='vsubmit' onclick='guardarModificar();' name='Update'>GUARDAR MODIFICACIÓN</span></td>";*/
			echo"<tr class='tab_bg_1'>";

			echo "<td id='nombreExperiencia'>";
			echo "<textarea cols='20' rows='3' name='name'>".$data['name']."</textarea>";
			echo "</td>";	

			echo "<td>";
			Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'),  array('value' => $data['estado']));
			echo "</td>";

			echo "<td id='intervencionBovis'>";
			if($data['intervencion_bovis']==1){
				echo "<input type='checkbox' name='intervencion_bovis' value='1' style='margin-left: 30px;' checked>";
			}
			else{
				echo "<input type='checkbox' name='intervencion_bovis' value='1' style='margin-left: 30px;'>";
			}
			//Dropdown::showYesNo('intervencion_bovis', (int)$data['intervencion_bovis']);
			echo "</td>";	

			echo "<td class='tipos_experiencias'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt2);
			echo "</td>";	

			echo "<td>";
			Dropdown::show('PluginComproveedoresCommunity',$opt3);
			echo "</td>";

			echo "<td>";
			echo "<textarea cols='20' rows='3' name='cliente'>".$data['cliente']."</textarea>";
			echo "</td>";

			echo "<td>";
			$keyAnio=0;
			foreach ($objExperiencia->getYears() as $key => $value) {
				if($value==$data['anio'])
					$keyAnio=$key;

			}
			Dropdown::showFromArray('anio', $objExperiencia->getYears(), array('value' => (int)$keyAnio));
			echo "</td>";

			echo "<td>";
			$importe=number_format($data['importe'], 2, ',', '.');

			Html::autocompletionTextField($objCommonDBT, "importe", array('value' => $importe));
			echo "</td>";

			echo "<td>";
			Html::autocompletionTextField($objCommonDBT, "duracion", array('value' => $data['duracion']));
			echo "</td>";

			echo "<td>";
			if($data['bim']==1){
				echo "<input type='checkbox' name='bim' value='1' style='margin-left: 5px;' checked>";
			}
			else{
				echo "<input type='checkbox' name='bim' value='1' style='margin-left: 5px;'>";
			}
			//Dropdown::showFromArray('bim', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value' => $data['bim']));
			echo "</td>";

			echo "<td>";
			if($data['breeam']==1){
				echo "<input type='checkbox' name='breeam' value='1' style='margin-left: 15px;' checked>";
			}
			else{
				echo "<input type='checkbox' name='breeam' value='1' style='margin-left: 15px;'>";
			}
			//Dropdown::showFromArray('breeam', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value' => $data['breeam']));
			echo "</td>";

			echo "<td>";
			if($data['leed']==1){
				echo "<input type='checkbox' name='leed' value='1' style='margin-left: 8px;' checked>";
			}
			else{
				echo "<input type='checkbox' name='leed' value='1' style='margin-left: 8px;'>";
			}
			//Dropdown::showFromArray('leed', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value' => $data['leed']));
			echo "</td>";

			echo "<td>";
			if($data['otros_certificados']==1){
				echo "<input type='checkbox' name='otros_certificados' value='1' style='margin-left: 25px;' checked>";
			}
			else{
				echo "<input type='checkbox' name='otros_certificados' value='1' style='margin-left: 25px;'>";
			}
			//Dropdown::showFromArray('otros_certificados', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value' => $data['otros_certificados']));
			echo "</td>";

			echo "<td>";
			if($data['cpd_tier']==1){
				echo "<input type='checkbox' name='cpd_tier' value='1' style='margin-left: 5px;' checked>";
			}
			else{
				echo "<input type='checkbox' name='cpd_tier' value='1' style='margin-left: 5px;'>";
			}
			//Dropdown::showFromArray('cpd_tier', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value' => $data['cpd_tier']));
			echo "</td>";

			echo "<td>";
			echo "<textarea cols='20' rows='3' name='observaciones'>".$data['observaciones']."</textarea>";
			//Html::autocompletionTextField($objCommonDBT, "observaciones", array('value' => $data['observaciones']));
			echo "</td>";

			echo"</tr>";

			echo"</tbody>";
			echo"</table>";

			echo "<div>";
			echo "<div style='display: inline-block;'><input type='submit' class='submit' name='add' value='AÑADIR' style='margin-right: 15px;'/></div>";
			echo "<div style='display: inline-block;'><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete' style='margin-right: 15px;'>AÑADIR SIN BORRAR </span></div>";
			echo "<div style='display: inline-block;' id='guardar_modificacion'><span class='vsubmit' onclick='guardarModificar(".$data["plugin_comproveedores_experiencestypes_id"].", ".$data['intervencion_bovis'].");' name='Update'>GUARDAR MODIFICACIÓN</span></div>";
			echo "</div>";
		}


