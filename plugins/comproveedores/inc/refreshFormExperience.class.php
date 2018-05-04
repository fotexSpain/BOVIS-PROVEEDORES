<?php


use Glpi\Event;


include ("../../../inc/includes.php");




GLOBAL $DB,$CFG_GLPI;


			$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE id=".$_GET['idExperiencia'];

			$objCommonDBT=new CommonDBTM;
			$objExperiencia=new PluginComproveedoresExperience;

			$result = $DB->query($query);


			while ($data=$DB->fetch_array($result)) {

			

			$opt3['comments']= false;
			$opt3['addicon']= false;
			$opt3['value']=  $data["plugin_comproveedores_communities_id"];

			$opt2['comments']= false;
			$opt2['addicon']= false;
			$opt2['value']=  $data["plugin_comproveedores_experiencestypes_id"];
			
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";

			echo Html::hidden('idExperiencia',array('value' => $_GET['idExperiencia']));

			echo"<th colspan='4'>Experiencia</th></tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Intervención de BOVIS') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('intervencion_bovis', (int)$data['intervencion_bovis']);
			echo "</td>";
			echo "<td class='tipos_experiencias'>" . __('Tipos de experiencias') . "</td>";
			echo "<td class='tipos_experiencias'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt2);
			echo "</td>";
			echo"</tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Nombre proyecto') . "</td>";
			echo "<td id='nombreExperiencia'>";
			Html::autocompletionTextField($objCommonDBT, "name", array('value' => $data['name']));
			echo "</td>";
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			echo "<td>";
			
			Dropdown::show('PluginComproveedoresCommunity',$opt3);

			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cliente') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($objCommonDBT, "cliente", array('value' => $data['cliente']));
			echo "</td>";
			echo "<td>" . __('Año') . "</td>";
			echo "<td>";
			$keyAnio=0;
			foreach ($objExperiencia->getYears() as $key => $value) {
				if($value==$data['anio'])
					$keyAnio=$key;

			}
			Dropdown::showFromArray('anio', $objExperiencia->getYears(), array('value' => (int)$keyAnio));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Importe contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($objCommonDBT, "importe", array('value' => $data['importe']));
			echo "</td>";
			echo "<td>" . __('Duración de su contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($objCommonDBT, "duracion", array('value' => $data['duracion']));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('BIM') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('bim',$data['bim']);
			echo "</td>";
			echo "<td>" . __('Breeam') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('breeam',$data['breeam']);
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Leed') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('leed',$data['leed']);
			echo "</td>";
			echo "<td>" . __('Otros certificados') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('otros_certificados',$data['otros_certificados']);
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cpd Tier') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('cpd_tier',$data['cpd_tier']);
			echo "</td>";
			echo "<td>" . __('Observaciones') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($objCommonDBT, "observaciones", array('value' => $data['observaciones']));
			echo "</td>";
			echo "</tr>";
			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<td><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete'>AÑADIR SIN BORRAR</span></td>";
			echo"<td><span class='vsubmit' onclick='guardarModificar();' name='Update'>GUARDAR MODIFICAR</span></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
		}


