
<?php
class PluginComproveedoresListspecialty extends CommonDBTM{

	static $rightname	= "plugin_comproveedores";

	static function getTypeName($nb=0){
		return _n('Especialidad del proveedor','Especialidad del proveedor',1,'comproveedores');
	}

	function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
		if($item->getType()=="Supplier"){
			return self::createTabEntry('Especialidad del proveedor');
		}
		return 'Especialidad del proveedor';
	}

	static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

		global $CFG_GLPI;
		$self = new self();
	
		if($item->getType()=='Supplier'){

			if(isset($item->fields['cv_id'])){
				
				$self->showFormItemSpecialty($item, $withtemplate);
			}else{
				
				$self->showFormNoCV($item, $withtemplate);
			}
				
		}else if($item->getType()=='PluginComproveedoresCv'){
				$self->showFormItem($item, $withtemplate);
		}
	}

	function getSearchOptions(){

		$tab = array();

		$tab['common'] = ('Especialidades');

		$tab[1]['table']	='glpi_plugin_comproveedores_roltypes';
		$tab[1]['field']	='name';
		$tab[1]['name']		=__('name');
		$tab[1]['datatype']		='text';

		return $tab;

	}

	function registerType($type){
		if(!in_array($type, self::$types)){
			self::$types[]= $type;
		}		
	}

	function showFormNoCV($ID, $options=[]) {
			//Aqui entra cuando no tien gestionado el curriculum

			echo "<div>Necesitas gestionar el CV antes de añadir especialidades</div>";
			echo "<br>";
	}

	function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores
		
	}

	
	function showFormItemSpecialty($item, $withtemplate='') {

			GLOBAL $DB,$CFG_GLPI;
			
			$opt['specific_tags']=array('onchange' => 'cambiarCategorias(value)');
			$opt['comments']= false;
			$opt['addicon']= false;
			

			/*///////////////////////////////
			//AÑADIR EXPERIENCIA AL PROVEEDOR
			///////////////////////////////*/
			

			$CvId=$item->fields['cv_id']; 

			$token=Session::getNewCSRFToken();
					
			echo $this->consultaAjax();
			
			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/listspecialty.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='6'>Especialidades</th></tr>";
			
			echo "<td>" . __('Contratista/Proveedor') . "</td>";
			echo "<td>";

			Dropdown::show('PluginComproveedoresRoltype',$opt);

			echo "</td>";

			echo "<td>" . __('Categorias') . "</td>";
			echo "<td>";
			echo "<div id='IdCategorias'>";
			echo "<span class='no-wrap'>
					<div class='select2-container'>
						<a class='select2-choice'>
						<span class='select2-chosen'>------</span>
						</a>
					</div>
				</span>";
         	echo "</div>";
			echo "</td>";

			echo "<td>" . __('Especialidades') . "</td>";
			echo "<td>";
			echo "<div id='IdEspecialidades'>";
			echo "<span class='no-wrap'>
					<div class='select2-container'>
						<a class='select2-choice'>
						<span class='select2-chosen'>------</span>
						</a>
					</div>
				</span>";
         	echo "</div>";
			echo "</td>";
			echo"</tr>";

			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			/*///////////////////////////////
			//LISTAR ESPECIALIDADES DEL PROVEEDOR
			///////////////////////////////*/

			$query2 ="SELECT*
			FROM glpi_plugin_comproveedores_listspecialties
			WHERE cv_id=".$CvId;

			$result2 = $DB->query($query2);
			
			//Ocultar lista, si no existe ninguna especialidad
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>Especialidades del proveedor</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Contratista/Proveedor')."</th>";
				
					echo "<th>".__('Categoría')."</th>";
					echo "<th>".__('Especialidad')."</th>";
					echo "<th>".__('Quitar')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result2)) {
						
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_roltypes",$data['plugin_comproveedores_roltypes_id'])."</td>";
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_categories",$data['plugin_comproveedores_categories_id'])."</td>";
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_specialties",$data['plugin_comproveedores_specialties_id'])."</td>";

							echo "<td class='center'>";
							echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/listspecialty.form.php method='post'>";
							echo Html::hidden('id', array('value' => $data['id']));
							echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
							echo"<input title='Quitar acceso' type='submit' class='submit' value='QUITAR' name='purge'/>";
							echo "</td>";
							echo"</form>";

						}


					echo"<br/>";
					echo "<tr><th>".__('Contratista/Proveedor')."</th>";
						echo "<th>".__('Categoría')."</th>";
						echo "<th>".__('Especialidad')."</th>";
						echo "<th>".__('Quitar')."</th>";
							echo "</tr>";
							echo "</table></div>";
							echo"<br>";
			}			

	}

	function showFormItem($item, $withtemplate='') {

			GLOBAL $DB,$CFG_GLPI;
			
			$opt['specific_tags']=array('onchange' => 'cambiarCategorias(value)');
			$opt['comments']= false;
			$opt['addicon']= false;
			

			/*///////////////////////////////
			//AÑADIR EXPERIENCIA AL PROVEEDOR
			///////////////////////////////*/
			

			$CvId=$item->fields['id'];

			$token=Session::getNewCSRFToken();
					
			echo $this->consultaAjax();
			
			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/listspecialty.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='6'>Especialidades</th></tr>";
			
			echo "<td>" . __('Contratista/Proveedor') . "</td>";
			echo "<td>";

			Dropdown::show('PluginComproveedoresRoltype',$opt);

			echo "</td>";

			echo "<td>" . __('Categorias') . "</td>";
			echo "<td>";
			echo "<div id='IdCategorias'>";
			echo "<span class='no-wrap'>
					<div class='select2-container'>
						<a class='select2-choice'>
						<span class='select2-chosen'>------</span>
						</a>
					</div>
				</span>";
         	echo "</div>";
			echo "</td>";

			echo "<td>" . __('Especialidades') . "</td>";
			echo "<td>";
			echo "<div id='IdEspecialidades'>";
			echo "<span class='no-wrap'>
					<div class='select2-container'>
						<a class='select2-choice'>
						<span class='select2-chosen'>------</span>
						</a>
					</div>
				</span>";
         	echo "</div>";
			echo "</td>";
			echo"</tr>";

			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			/*///////////////////////////////
			//LISTAR ESPECIALIDADES DEL PROVEEDOR
			///////////////////////////////*/

			$query2 ="SELECT*
			FROM glpi_plugin_comproveedores_listspecialties
			WHERE cv_id=".$CvId;

			$result2 = $DB->query($query2);
			
			//Ocultar lista, si no existe ninguna especialidad
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>Especialidades del proveedor</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Contratista/Proveedor')."</th>";
				
					echo "<th>".__('Categoría')."</th>";
					echo "<th>".__('Especialidad')."</th>";
					echo "<th>".__('Quitar')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result2)) {
						
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_roltypes",$data['plugin_comproveedores_roltypes_id'])."</td>";
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_categories",$data['plugin_comproveedores_categories_id'])."</td>";
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_plugin_comproveedores_specialties",$data['plugin_comproveedores_specialties_id'])."</td>";

							echo "<td class='center'>";
							echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/listspecialty.form.php method='post'>";
							echo Html::hidden('id', array('value' => $data['id']));
							echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
							echo"<input title='Quitar acceso' type='submit' class='submit' value='QUITAR' name='purge'/>";
							echo "</td>";
							echo"</form>";

						}


					echo"<br/>";
					echo "<tr><th>".__('Contratista/Proveedor')."</th>";
						echo "<th>".__('Categoría')."</th>";
						echo "<th>".__('Especialidad')."</th>";
						echo "<th>".__('Quitar')."</th>";
							echo "</tr>";
							echo "</table></div>";
							echo"<br>";
			}			

	}

	function consultaAjax(){

		GLOBAL $DB,$CFG_GLPI;

		$consulta="<script type='text/javascript'>
			
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

}