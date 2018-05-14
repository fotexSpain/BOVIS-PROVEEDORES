<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresExperience extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Expeciencias','Expeciencias',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Experiencias');
			}
			return 'Experiencias';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();

			if($item->getType()=='Supplier'){	

				if(isset($item->fields['cv_id'])){
			
					$self->showFormItemExperience($item, $withtemplate);

				}else{
				
					$self->showFormNoCV($item, $withtemplate);
				}
				
			}else if($item->getType()=='PluginComproveedoresCv'){
				$self->showFormItem($item, $withtemplate);
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
			GLOBAL $DB,$CFG_GLPI;
			

			/*///////////////////////////////
			//AÑADIR EXPERIENCIA AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['id']; 
			$Experiencia_Id=0;
			$opt['comments']= false;
			$opt['addicon']= false;

			
			echo $this->consultaAjax();

			
			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			
			

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center' id='actualizarFormulario'>";
			echo $this->consultaJquery();
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";

			echo Html::hidden('idExperiencia');

			echo"<th colspan='33'>Experiencia</th></tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Estado') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'));
			echo "</td>";
		
			echo "<td>" . __('Intervención de BOVIS') . "</td>";
			echo "<td id='intervencionBovis'>";
			Dropdown::showYesNo('intervencion_bovis');
			echo "</td>";

			echo "<td class='tipos_experiencias'>" . __('Tipos de experiencias') . "</td>";
			echo "<td class='tipos_experiencias'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt);
			echo "</td>";
			
	
			echo "<td>" . __('Nombre proyecto') . "</td>";
			echo "<td id='nombreExperiencia'>";
			echo "<textarea cols='37' rows='3' name='name'></textarea>";
			echo "</td>";
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			echo "<td>";
			Dropdown::show('PluginComproveedoresCommunity',$opt);
			echo "</td>";
			
			echo "<td>" . __('Cliente') . "</td>";
			echo "<td>";
			echo "<textarea cols='37' rows='3' name='cliente'></textarea>";
			echo "</td>";
			echo "<td>" . __('Año') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('anio',$this->getYears());

			echo "</td>";
			
			echo "<td>" . __('Importe contratado') . "</td>";
			echo "<td id='importeExperiencia'>";
			Html::autocompletionTextField($this, "importe");
			echo "</td>";
			echo "<td>" . __('Duración de su contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "duracion");
			echo "</td>";
			
			echo "<td>" . __('BIM') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('bim', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			echo "<td>" . __('Breeam') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('breeam', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			
			echo "<td>" . __('Leed') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('leed', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			echo "<td>" . __('Otros certificados') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('otros_certificados', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			
			echo "<td>" . __('Cpd Tier') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('cpd_tier', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			echo "<td>" . __('Observaciones') . "</td>";
			echo "<td>";
			echo "<textarea cols='37' rows='3' name='observaciones'></textarea>";
			//Html::autocompletionTextField($this, "observaciones");
			echo "</td>";
			
			

			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<td><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete'>AÑADIR SIN BORRAR</span></td>";
			echo"<td><span class='vsubmit' onclick='guardarModificar();' name='Update'>GUARDAR MODIFICACIÓN</span></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo "</div>";
			echo"</form>";
			

			/*///////////////////////////////
			//LISTAR EXPERIENCIA DEL PROVEEDOR
			///////////////////////////////*/
			echo "<div id='actualizarLista'>";


			$query2 ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=$CvId order by id desc" ;

			$result2 = $DB->query($query2);

			//Ocultar lista, si no existe ninguna expeciencia
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='17'>Experiencias del proveedor</th></tr>";
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


						echo"<br/>";
							echo "</table></div>";
							echo"<br>";

			}
			echo "</div>";
					
		}


		function showFormNoCV($ID, $options=[]) {
			//Aqui entra cuando no tien gestionado el curriculum

			echo "<div>Necesitas gestionar el CV antes de añadir expeciencias</div>";
			echo "<br>";
		}

		function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores

			global $CFG_GLPI;
			$this->initForm($ID, $options);
			$this->showFormHeader($options);

			echo $this->consultaJquery();

			$opt2['comments']= false;
			$opt2['addicon']= false;
			$opt2['value']=  $this->fields["plugin_comproveedores_communities_id"];

			$opt['comments']= false;
			$opt['addicon']= false;
			$opt['value']=  $this->fields["plugin_comproveedores_experiencestypes_id"];
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Estado') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'),array('value'=>$this->fields["estado"]));
			echo "</td>";
			echo "</tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Intervención de BOVIS') . "</td>";
			echo "<td id='intervencionBovis'>";
			Dropdown::showYesNo('intervencion_bovis');
			echo "</td>";
			echo "<td class='tipos_experiencias'>" . __('Tipos de experiencias') . "</td>";
			echo "<td class='tipos_experiencias'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt);
			echo "</td>";
			echo"</tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Nombre proyecto') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "name");
			echo "</td>";
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			echo "<td>";
			
			Dropdown::show('PluginComproveedoresCommunity',$opt2);

			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cliente') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cliente");
			echo "</td>";
			echo "<td>" . __('Año') . "</td>";
			echo "<td>";

			$anio = date("Y", strtotime($this->fields["anio"]));
			$anio++;
			Dropdown::showFromArray('anio', $this->getYears(),array('value'=>$anio));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Importe contratado') . "</td>";
			echo "<td>";
			$importe=number_format($this->fields["importe"], 2, ',', '.');
			Html::autocompletionTextField($this, "importe",array('value'=>$importe));
			echo "</td>";
			echo "<td>" . __('Duración de su contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "duracion");
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('BIM') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('bim', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value'=>$this->fields['bim']));
			echo "</td>";
			echo "<td>" . __('Breeam') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('breeam', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value'=>$this->fields['breeam']));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Leed') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('leed', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value'=>$this->fields['leed']));
			echo "</td>";
			echo "<td>" . __('Otros certificados') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('otros_certificados', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value'=>$this->fields['otros_certificados']));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cpd Tier') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('cpd_tier', array(-1 =>'------', 1=>'Sí' , 0 =>'No'),array('value'=>$this->fields['cpd_tier']));
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
			for ($i= $year; $i >=  1945; $i--) {

				$lista[$i]=$i;
				
			}
			return $lista;
		}

		function showFormItemExperience($item, $withtemplate='') {	

			GLOBAL $DB,$CFG_GLPI;
			

			/*///////////////////////////////
			//AÑADIR EXPERIENCIA AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['cv_id']; 
			$Experiencia_Id=0;
			$opt['comments']= false;
			$opt['addicon']= false;

			
			echo $this->consultaAjax();

			
			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center' id='actualizarFormulario'>";
			echo $this->consultaJquery();
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";

			echo Html::hidden('idExperiencia');

			echo"<th colspan='33'>Experiencia</th></tr>";
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Estado') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'));
			echo "</td>";
			
			echo "<td>" . __('Intervención de BOVIS') . "</td>";
			echo "<td id='intervencionBovis'>";
			Dropdown::showYesNo('intervencion_bovis');
			echo "</td>";

			echo "<td class='tipos_experiencias'>" . __('Tipos de experiencias') . "</td>";
			echo "<td class='tipos_experiencias'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt);
			echo "</td>";
			
			
			echo "<td>" . __('Nombre proyecto') . "</td>";
			echo "<td id='nombreExperiencia'>";
			echo "<textarea cols='37' rows='3' name='name'></textarea>";
			echo "</td>";
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			echo "<td>";
			Dropdown::show('PluginComproveedoresCommunity',$opt);
			echo "</td>";
			
			echo "<td>" . __('Cliente') . "</td>";
			echo "<td>";
			echo "<textarea cols='37' rows='3' name='cliente'></textarea>";
			echo "</td>";
			echo "<td>" . __('Año') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('anio',$this->getYears());

			echo "</td>";
			
			echo "<td>" . __('Importe contratado') . "</td>";
			echo "<td id='importeExperiencia'>";
			Html::autocompletionTextField($this, "importe");
			echo "</td>";
			echo "<td>" . __('Duración de su contratado') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "duracion");
			echo "</td>";
			
			echo "<td>" . __('BIM') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('bim', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			echo "<td>" . __('Breeam') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('breeam', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			
			echo "<td>" . __('Leed') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('leed', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			echo "<td>" . __('Otros certificados') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('otros_certificados', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			
			echo "<td>" . __('Cpd Tier') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('cpd_tier', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";
			echo "<td>" . __('Observaciones') . "</td>";
			echo "<td>";
			echo "<textarea cols='37' rows='3' name='observaciones'></textarea>";
			//Html::autocompletionTextField($this, "observaciones");
			echo "</td>";
			
			

			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<td><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete'>AÑADIR SIN BORRAR</span></td>";
			echo"<td><span class='vsubmit' onclick='guardarModificar();' name='Update'>GUARDAR MODIFICACIÓN</span></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo "</div>";
			echo"</form>";
			

			/*///////////////////////////////
			//LISTAR EXPERIENCIA DEL PROVEEDOR
			///////////////////////////////*/
			echo "<div id='actualizarLista'>";


			$query2 ="SELECT * FROM glpi_plugin_comproveedores_experiences WHERE cv_id=$CvId order by id desc" ;

			$result2 = $DB->query($query2);

			//Ocultar lista, si no existe ninguna expeciencia
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='17'>Experiencias del proveedor</th></tr>";
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

								/*if($data["is_deleted"]=='1'){
									echo "<td class='center'>Si</td></tr>";
								}else{
									echo "<td  class='center'>No</td></tr>";
								}*/

					}


						echo"<br/>";
							echo "</table></div>";
							echo"<br>";

			}
			echo "</div>";
		}

		function consultaJquery(){

			GLOBAL $DB,$CFG_GLPI;

			$consulta="<script type='text/javascript'>

				$(document).ready(function() {

					//añadimos onchange al desplegable de Intervención de BOVIS
					$('#intervencionBovis').find('select').change(function() {
						
						//Cogemos el valor selecionado
    					$('[name=intervencion_bovis] option:selected').each(function() {
      						valor_intervencion_bovis=$(this).text();
   						 });

						if(valor_intervencion_bovis=='No'){

							$('.tipos_experiencias').show();

						}else{
							
							$('.tipos_experiencias').hide();
						}

					});
  					
				});	
			

			</script>";

			return $consulta;
		}

		function consultaAjax(){

		GLOBAL $CFG_GLPI;

		$consulta="<script type='text/javascript'>

				function añadirSinBorrar(){
					
					$('select[name=intervencion_bovis] option:selected').each(function() {
      						intervencion_bovis=$( this ).val();
   					});
					$('select[name=bim] option:selected').each(function() {
      						bim=$( this ).val();
   					});
   					$('select[name=breeam] option:selected').each(function() {
      					breeam=$( this ).val();
   					});
   					$('select[name=leed] option:selected').each(function() {
      					leed=$( this ).val();
   					});
   					$('select[name=otros_certificados] option:selected').each(function() {
      					otros_certificados=$( this ).val();
   					});
   					$('select[name=cpd_tier] option:selected').each(function() {
      					cpd_tier=$( this ).val();
   					});
   					$('select[name=anio] option:selected').each(function() {
      					anio=$( this ).text();
      					anio=anio+'-00-00 00:00';
   					});
   					$('select[name=estado] option:selected').each(function() {
      					estado=$( this ).val();
   					});
   				
                	var parametros = {
						'addNoDelete':'AÑADIR SIN BORRAR',
						'cv_id' : $('input[name=cv_id]').val(),
						'estado':estado,
						'intervencion_bovis'	:	intervencion_bovis,
						'plugin_comproveedores_experiencestypes_id':$('input[name=plugin_comproveedores_experiencestypes_id]').val(),
						'plugin_comproveedores_communities_id'	:	$('[name=plugin_comproveedores_communities_id]').val(),
                		'name' : $('#nombreExperiencia > textarea[name=name]').val(),
                		'cliente' : $('textarea[name=cliente]').val(),
                		'anio'	:	anio,
                		'importe': $('input[name=importe]').val(),
                		'duracion': $('input[name=duracion]').val(),
                		'bim'	:	bim,
                		'breeam':	breeam,
                		'leed'	:	leed,
                		'otros_certificados':	otros_certificados,
                		'cpd_tier'	:	cpd_tier,
                		'observaciones'	:	$('textarea[name=observaciones]').val()
                		
                	};
                	

					$.ajax({  
						type: 'GET',  
						async: false,               
           				url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php',                    
           				data: parametros, 
						success:function(data){
							$('input[name=idExperiencia]').val(data);        					
                		},
                		error: function(result) {
                   			 alert('Data not found');
                		}
            		});

            		//Actualizar Lista expeciencias
            	
            		actualizarLista();
						
				}

				function guardarModificar(){


					$('select[name=intervencion_bovis] option:selected').each(function() {
      						intervencion_bovis=$( this ).val();
   					});
					$('select[name=bim] option:selected').each(function() {
      						bim=$( this ).val();
   					});
   					$('select[name=breeam] option:selected').each(function() {
      					breeam=$( this ).val();
   					});
   					$('select[name=leed] option:selected').each(function() {
      					leed=$( this ).val();
   					});
   					$('select[name=otros_certificados] option:selected').each(function() {
      					otros_certificados=$( this ).val();
   					});
   					$('select[name=cpd_tier] option:selected').each(function() {
      					cpd_tier=$( this ).val();
   					});
   					$('select[name=anio] option:selected').each(function() {
      					anio=$( this ).text();
      					anio=anio+'-00-00 00:00';
   					});
   					$('select[name=estado] option:selected').each(function() {
      					estado=$( this ).val();
   					});

                	var parametros = {
						'update':'GUARDAR MODIFICAR',
						'cv_id' : $('input[name=cv_id]').val(),
						'estado':estado,
						'id'	: $('input[name=idExperiencia]').val(),
						'intervencion_bovis'	:	intervencion_bovis,
						'plugin_comproveedores_experiencestypes_id':$('input[name=plugin_comproveedores_experiencestypes_id]').val(),
						'plugin_comproveedores_communities_id'	:	$('[name=plugin_comproveedores_communities_id]').val(),
                		'name' : $('#nombreExperiencia > textarea[name=name]').val(),
                		'cliente' : $('textarea[name=cliente]').val(),
                		'anio'	:	anio,
                		'importe': $('input[name=importe]').val(),
                		'duracion': $('input[name=duracion]').val(),
                		'bim'	:	bim,
                		'breeam':	breeam,
                		'leed'	:	leed,
                		'otros_certificados':	otros_certificados,
                		'cpd_tier'	:	cpd_tier,
                		'observaciones'	:	$('textarea[name=observaciones]').val()
                		
                	};


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

            		//Actualizar Lista expeciencias
            	
            		actualizarLista();
						
				}

				function actualizarLista(){

					$.ajax({ 
						async: false, 
            			type: 'GET',
            			data: {'cv_id': $('input[name=cv_id]').val() },                  
           				url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/listExperience.php',                    
           				success:function(data){
           					$('#actualizarLista').html(data);
                		},
                		error: function(result) {
                   			 alert('Data not found');
                		}
            		});

            		
				}

				function modificar(idExperiencia){

					$.ajax({ 
						async: false, 
            			type: 'GET',
            			data: {'idExperiencia':  idExperiencia},                  
           				url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/refreshFormExperience.class.php',                    
           				success:function(data){
           					$('#actualizarFormulario').html(data);
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