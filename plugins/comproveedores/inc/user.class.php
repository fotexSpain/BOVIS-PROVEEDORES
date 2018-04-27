<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresUser extends CommonDBRelation{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Personas de contacto','Personas de contacto',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Personas de contacto');
			}
			return 'Usuario del proveedor';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();
			if($item->getType()=='Supplier'){	
				$self->showFormItem($item, $withtemplate);
			}else if($item->getType()=='PluginComproveedoresCv'){
				$self->showFormItemCv($item, $withtemplate);
			}
		}

		function showFormItem($item, $withtemplate='') {	

			///////////////////////////
			//ENTRA DESDE EL CURRICULUM
			///////////////////////////


			GLOBAL $DB,$CFG_GLPI;
			//AÑADIR USUARIOS AL PROVEEDOR

			$dropdown = new Dropdown();
			$SupplierId=$item->fields['id']; 
			
			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php  method='post'>";		
			echo Html::hidden('supplier_id', array('value' => $SupplierId));
			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='4'>Usuarios</th></tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo"<td>Selecciona un usuario:</td>";
			echo"<td>";
			$options['condition']='profiles_id=9';
			$options['name']='user_id';
			Dropdown::show('User',$options);
			echo"</td>";
			echo"<td><input type='submit' class='submit' name='addToSupplier' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";
			echo"<br>\n"; 


			//MOSTRAR USUARIOS

			if (Session::isMultiEntitiesMode()) {
				$colsup=1;
			} else {
				$colsup=0;
			}



			$query2 ="SELECT * FROM glpi_users WHERE supplier_id=$SupplierId";

			$result2 = $DB->query($query2);

			//Ocultar lista, si no existe ningun usuario
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='nohover'><th colspan='".(6+$colsup)."'>"._n('Usuarios del proveedor:', 'Usuarios del proveedor:', 2, 'comproveedores')."</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Name')."</th>";
				if (Session::isMultiEntitiesMode())
					echo "<th>".__('Entity')."</th>";
					echo "<th>".__('Puesto')."</th>";	
					echo "<th>".__('Desenlazar')."</th>";
					echo "<th>".__('Papelera')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result2)) {
						if($data['is_deleted']==""){
							$data['is_deleted']=1;
						}

						echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
						if ((in_array($data['entities_id'],$_SESSION['glpiactiveentities']))) {
							echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/front/user.form.php?id=".$data["id"]."'>".$data["name"];
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
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_usertitles",$data['usertitles_id'])."</td>";
							echo "<td class='center'>";
							echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php method='post'>";
							echo Html::hidden('users_id', array('value' => $data['id']));
							echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
							echo"<input title='Quitar acceso' type='submit' class='submit' value='QUITAR' name='delUser'/>";
							echo "</td>";
							echo"</form>";

							if($data["is_deleted"]=='1'){
								echo "<td class='center'>Si</td></tr>";
							}else{
								echo "<td  class='center'>No</td></tr>";
							}

						}


						echo"<br/>";
						echo "<tr><th>".__('Name')."</th>";
						if (Session::isMultiEntitiesMode())
							echo "<th>".__('Entity')."</th>";
							echo "<th>".__('Puesto')."</th>";
							echo "<th>".__('Desenlazar')."</th>";
							echo "<th>".__('Papelera')."</th>";
							echo "</tr>";
							echo "</table></div>";
							echo"<br>";

						}
					}
					function showFormItemCv($item, $withtemplate='') {	
						
						////////////////////////
						//ENTRA DESDE LA PESTAÑA
						////////////////////////


						GLOBAL $DB,$CFG_GLPI;
						//AÑADIR USUARIOS AL PROVEEDOR

						$dropdown = new Dropdown();
						//
						$SupplierId=$item->fields['supplier_id']; 

						echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php method='post'>";		
						echo Html::hidden('supplier_id', array('value' => $SupplierId));
						echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
						echo "<div class='center'>";
						echo"<table class='tab_cadre_fixe'><tbody>";
						echo"<tr class='headerRow'>";
						echo"<th colspan='4'>Usuarios</th></tr>";
						echo"<tr class='tab_bg_1 center'>";
						echo"<td>Selecciona un usuario:</td>";
						echo"<td>";
						$options['condition']='profiles_id=9';
						$options['name']='user_id';
						Dropdown::show('User',$options);
						echo"</td>";
						echo"<td><input type='submit' class='submit' name='addToSupplier' value='AÑADIR' /></td>";
						echo"<tr class='tab_bg_1'>";
						echo"</tr>";
						echo"</tbody>";
						echo"</table>";
						echo"</div>";
						echo"</form>";
						


						//MOSTRAR USUARIOS

						if (Session::isMultiEntitiesMode()) {
							$colsup=1;
						} else {
							$colsup=0;
						}



						$query2 ="SELECT * FROM glpi_users WHERE supplier_id=$SupplierId AND supplier_id<>0 " ;

						$result2 = $DB->query($query2);

						//Ocultar lista, si no existe ningun usuario
						if($result2->num_rows!=0){
							echo "<div align='center'><table class='tab_cadre_fixehov'>";
							echo "<tr class='nohover'><th colspan='".(6+$colsup)."'>"._n('Usuarios del proveedor:', 'Usuarios del proveedor:', 2, 'comproveedores')."</th></tr>";
							echo"<br/>";
							echo "<tr><th>".__('Name')."</th>";
							if (Session::isMultiEntitiesMode())
								echo "<th>".__('Entity')."</th>";
								echo "<th>".__('Puesto')."</th>";	
								echo "<th>".__('Desenlazar')."</th>";
								echo "<th>".__('Papelera')."</th>";
								echo "</tr>";

								while ($data=$DB->fetch_array($result2)) {
									if($data['is_deleted']==""){
										$data['is_deleted']=1;
									}

									echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
									if ((in_array($data['entities_id'],$_SESSION['glpiactiveentities']))) {
										echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/front/user.form.php?id=".$data["id"]."'>".$data["name"];
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
										echo "<td class='center'>".Dropdown::getDropdownName("glpi_usertitles",$data['usertitles_id'])."</td>";
										echo "<td class='center'>";
										echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/cv.form.php method='post'>";
										echo Html::hidden('users_id', array('value' => $data['id']));
										echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
										echo"<input title='Quitar acceso' type='submit' class='submit' value='QUITAR' name='delUser'/>";
										echo "</td>";
										echo"</form>";

										if($data["is_deleted"]=='1'){
											echo "<td class='center'>Si</td></tr>";
										}else{
											echo "<td  class='center'>No</td></tr>";
										}

									}


									echo"<br/>";
									echo "<tr><th>".__('Name')."</th>";
									if (Session::isMultiEntitiesMode())
										echo "<th>".__('Entity')."</th>";
										echo "<th>".__('Puesto')."</th>";
										echo "<th>".__('Desenlazar')."</th>";
										echo "<th>".__('Papelera')."</th>";
										echo "</tr>";
										echo "</table></div>";
										echo"<br>";

									}		

								}

								
								function showForm($ID, $options=[]) {
									echo "Hola"; 
								}

							}