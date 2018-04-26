<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresExperience extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Expeciencia del proveedor','Expeciencia del proveedor',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Expeciencia del proveedor');
			}
			return 'Expeciencia del proveedor';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();
			if($item->getType()=='Supplier'){

				if(isset($item->fields['cv_id'])){
					$self->showFormItemExperience($item, $withtemplate);
				}else{
					$self->showFormItem($item, $withtemplate);
				}
				
			}else{
				$self->showForm11();
			}
		}

		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('Experiencias');

			$tab[1]['table']	=$this->getTable();
			$tab[1]['field']	='name';
			$tab[1]['name']		=__('Name');
			$tab[1]['datatype']		='itemlink';
			$tab[1]['itemlink_type']	=$this->getTable();

			return $tab;

		}

		function registerType($type){
			if(!in_array($type, self::$types)){
				self::$types[]= $type;
			}		
		}

		static function getTypes($all=false) {
			if ($all) {
				return self::$types;
			}
    // Only allowed types
			$types = self::$types;
			foreach ($types as $key => $type) {
				if (!($item = getItemForItemtype($type))) {
					continue;
				}

				if (!$item->canView()) {
					unset($types[$key]);
				}
			}
			return $types;
		}

		function showFormItem($item, $withtemplate='') {	
			
		}


		function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores
			global $CFG_GLPI;
			$this->initForm($ID, $options);
			$this->showFormHeader($options);
			
			echo"<th colspan='4'>Usuarios</th>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Nombre proyecto') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "name");
			echo "</td>";
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			echo "<td>";
			//Html::autocompletionTextField($this, "comunidad");
			Dropdown::show('PluginComproveedoresCommunity',
				array('value' => $this->fields["plugin_comproveedores_communities_id"]));

			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cliente') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cliente");
			echo "</td>";
			echo "<td>" . __('Año') . "</td>";
			echo "<td>";
			$options['value']=$this->fields["anio"];
			Dropdown::showFromArray('anio', $this->getYears(),array($options));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Importe contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "importe");
			echo "</td>";
			echo "<td>" . __('Duración de su contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "duracion");
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('BIM') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('bim',$this->fields['bim']);
			echo "</td>";
			echo "<td>" . __('Breeam') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('breeam',$this->fields['breeam']);
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Leed') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('leed',$this->fields['leed']);
			echo "</td>";
			echo "<td>" . __('Otros certificados') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('otros_certificados',$this->fields['otros_certificados']);
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cpd Tier') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('cpd_tier',null);
			echo "</td>";
			echo "<td>" . __('Observaciones') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "observaciones");
			echo "</td>";
			echo "</tr>";

			$this->showFormButtons($options);
		}


		function getYears(){
			$year = date("Y");
			for ($i= 1945; $i <= $year ; $i++) {

				$lista[]=$i;
				
			}
			return $lista;
		}

		function showFormItemExperience($item, $withtemplate='') {	

			GLOBAL $DB,$CFG_GLPI;
			

			/*///////////////////////////////
			//AÑADIR EXPERIENCIA AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['cv_id']; 

			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='4'>Experiencia</th></tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Nombre proyecto') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "name");
			echo "</td>";
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			echo "<td>";
			Dropdown::show('PluginComproveedoresCommunity');
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cliente') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cliente");
			echo "</td>";
			echo "<td>" . __('Año') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('anio',$this->getYears());

			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Importe contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "importe");
			echo "</td>";
			echo "<td>" . __('Duración de su contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "duracion");
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('BIM') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('bim', null);
			echo "</td>";
			echo "<td>" . __('Breeam') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('breeam', null);
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Leed') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('leed', null);
			echo "</td>";
			echo "<td>" . __('Otros certificados') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('otros_certificados',null);
			echo "</td>";
			echo"</tr>";


			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cpd Tier') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('cpd_tier',null);
			echo "</td>";
			echo "<td>" . __('Observaciones') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "observaciones");
			echo "</td>";
			echo "</tr>";

			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			/*///////////////////////////////
			//LISTAR EXPERIENCIA DEL PROVEEDOR
			///////////////////////////////*/

			$query2 ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=$CvId" ;

			$result2 = $DB->query($query2);

			//Ocultar lista, si no existe ninguna expeciencia
			if($result2->num_rows!=0){

			echo "<div align='center'><table class='tab_cadre_fixehov'>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Esperiencias del proveedor</th></tr>";
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

				while ($data=$DB->fetch_array($result2)) {
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
							echo "<td class='center'>".$data['anio']."</td>";
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

							/*if($data["is_deleted"]=='1'){
								echo "<td class='center'>Si</td></tr>";
							}else{
								echo "<td  class='center'>No</td></tr>";
							}*/

					}


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
							echo "</table></div>";
							echo"<br>";

			}
		}
	}