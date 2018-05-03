<?php

use Glpi\Event;

include ("../../../inc/includes.php");

GLOBAL $DB,$CFG_GLPI;


	$query ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=".$_GET['cv_id'];

			$result = $DB->query($query);

			//Ocultar lista, si no existe ninguna expeciencia
			if($result->num_rows!=0){

				echo "<div class='actualizarLista' align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Experiencias del proveedor</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Proyecto/Obra')."</th>";
				if (Session::isMultiEntitiesMode())
					echo "<th>".__('Entity')."</th>";
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
					echo "<th>".__('Eliminar')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result)) {
							if($data['is_deleted']==""){
								$data['is_deleted']=1;
							}

							echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
							if ((in_array($data['entities_id'],$_SESSION['glpiactiveentities']))) {
								echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php?id=".$data["id"]."'>".$data["name"];
								if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
								echo "</a></td>";
							} else {
								echo "<td class='center'>".$data["name"];
								if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
								echo "</td>";
							}
							echo "</a></td>";
							if (Session::isMultiEntitiesMode())
								echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
								echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_communities",$data['plugin_comproveedores_communities_id'])."</td>";
								echo "<td class='center'>".$data['cliente']."</td>";
								$anio = date("Y", strtotime($data['anio']));
								$anio++;
								echo "<td class='center'>".$anio."</td>";
								echo "<td class='center'>".$data['importe']."</td>";
								echo "<td class='center'>".$data['duracion']."</td>";
								echo "<td class='center'>";
								if($data['bim']=='1'){
									echo "Si";
								}else{
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['breeam']=='1'){
									echo "Si";
								}else{
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['leed']=='1'){
									echo "Si";
								}else{
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['otros_certificados']=='1'){
									echo "Si";
								}else{
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>";
								if($data['cpd_tier']=='1'){
									echo "Si";
								}else{
									echo "No";
								}
								echo "</td>";
								echo "<td class='center'>".$data['observaciones']."</td>";
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

			}