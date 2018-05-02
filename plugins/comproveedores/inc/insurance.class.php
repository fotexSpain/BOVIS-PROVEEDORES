<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresInsurance extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Seguros del proveedor','Seguros del proveedor',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Seguros del proveedor');
			}
			return 'Seguros del proveedor';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();

			//Entrada Administrador
			if($item->getType()=='Supplier'){	

				if(isset($item->fields['cv_id'])){
			
					$self->showFormItemInsurence($item, $withtemplate);

				}else{
				
					$self->showFormNoCV($item, $withtemplate);
				}
			//entrada Proveedores
			}else if($item->getType()=='PluginComproveedoresCv'){
				$self->showFormItem($item, $withtemplate);
			}


		}

		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('Seguros');

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

		function showFormItemInsurence($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;
			
			echo $this->consultaAjax();

			/*///////////////////////////////
			//AÑADIR SEGURO AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['cv_id']; 

			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/insurance.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='4'>Seguros</th></tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Tipo de seguro') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('selectTipo',$this->getInsurence());
			echo "</td>";
			echo "<td class='SeguroNombreOcultar'>". __('Nombre nuevo seguro') . "</td>";
			echo "<td class='SeguroNombreOcultar'>";
			Html::autocompletionTextField($this, "name", array('value' => 'Resposabilidad civil'));
			echo "</td>";
			echo"</tr>";
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cía aseguradora') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cia_aseguradora");
			echo "</td>";
			echo "<td>" . __('Cuantía') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($this, "cuantia");
			echo "</td>";
			echo"</tr>";
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Fecha caducidad') . "</td>";
			echo "<td>";
			Html::showDateTimeField("fecha_caducidad");
			echo "</td>";
			echo "<td class='SeguroNAseguradosOcultar'>" . __('Nº empleados asegurados') . "</td>";
			echo "<td class='SeguroNAseguradosOcultar'>";
			Html::autocompletionTextField($this, "numero_empleados_asegurados");
			echo "</td>";
			echo"</tr>";
			

			echo"<tr class='tab_bg_1 center'>";
			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			/*///////////////////////////////
			//LISTAR SEGUROS DEL PROVEEDOR
			///////////////////////////////*/

			$query2 ="SELECT * FROM glpi_plugin_comproveedores_insurances WHERE cv_id=$CvId" ;

			$result2 = $DB->query($query2);

			//Ocultar lista, si no existe ninguna expeciencia
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Seguros del proveedor</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Tipo de seguro')."</th>";
					echo "<th>".__('Cía Aseguradora')."</th>";
					echo "<th>".__('Cuantía')."</th>";
					echo "<th>".__('Fecha caducidad')."</th>";
					echo "<th>".__('Nº empleados asegurados')."</th>";
					echo "<th>".__('Eliminar')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result2)) {
							if($data['is_deleted']==""){
								$data['is_deleted']=1;
							}

							echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
							if ((in_array($data['entities_id'],$_SESSION['glpiactiveentities']))) {
								echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/insurance.form.php?id=".$data["id"]."'>".$data["name"];
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
								echo "<td class='center'>".$data['cia_aseguradora']."</td>";
								echo "<td class='center'>".$data['cuantia']."</td>";
								echo "<td class='center'>".$data['fecha_caducidad']."</td>";
								echo "<td class='center'>".$data['numero_empleados_asegurados']."</td>";	

								echo "<td class='center'>";
								echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/insurance.form.php method='post'>";
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
					
		}


		function showFormNoCV($ID, $options=[]) {
			//Aqui entra cuando no tien gestionado el curriculum

			echo "<div>Necesitas gestionar el CV antes de añadir seguros</div>";
			echo "<br>";
		}

		

		function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores

			global $CFG_GLPI;

			if($this->fields['fecha_caducidad']=='0000-00-00'){
				$opt['value']= null;
			}else{
				
				$opt['value']= $this->fields['fecha_caducidad'].' 00:00';
			}
			

			$this->initForm($ID, $options);
			$this->showFormHeader($options);

			echo"<script type='text/javascript'>
				
				$(document).ready(function() {

					if('".$this->fields['name']."' == 'Resposabilidad civil'
						|| '".$this->fields['name']."' == 'Seguro todo riesgo'
						|| '".$this->fields['name']."' == 'Seguro accidentes de trabajo'){

						$('select[name=selectTipo]').find('option:contains(".$this->fields['name'].")').attr('selected',true);
						
					}
					else{
						$('select[name=selectTipo]').find('option:contains(Otros Seguros)').attr('selected',true);
					}
					
				});		
				
			</script>";

			echo $this->consultaAjax();
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Tipo Seguro') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('selectTipo',$this->getInsurence());
			echo "</td>";
			echo "<td class='SeguroNombreOcultar'>". __('Nombre nuevo seguro') . "</td>";
			echo "<td class='SeguroNombreOcultar'>";
			Html::autocompletionTextField($this, "name");
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cía Aseguradora') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cia_aseguradora");
			echo "</td>";
			echo "<td>" . __('Cuantía') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($this, "cuantia");
			echo "</td>";
			echo"</tr>";


			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Fecha Caducidad'). "</td>";
			echo "<td>";
			Html::showDateTimeField('fecha_caducidad',$opt);
			echo "</td>";
			echo "<td class='SeguroNAseguradosOcultar'>" . __('Nº empleados asegurados') . "</td>";
			echo "<td class='SeguroNAseguradosOcultar'>";
			Html::autocompletionTextField($this, "numero_empleados_asegurados");
			echo "</td>";
			echo"</tr>";

			$this->showFormButtons($options);
		}


		function getYears(){
			$year = date("Y");
			for ($i= 1945; $i <= $year ; $i++) {

				$lista[]=$i;
				
			}
			return $lista;
		}

		function showFormItem($item, $withtemplate='') {	

			GLOBAL $DB,$CFG_GLPI;
			
			echo $this->consultaAjax();

			/*///////////////////////////////
			//AÑADIR SEGURO AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['id']; 

			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/insurance.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='4'>Seguros</th></tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Tipo de seguro') . "</td>";
			echo "<td>";
			Dropdown::showFromArray('selectTipo',$this->getInsurence());
			echo "</td>";
			echo "<td class='SeguroNombreOcultar'>". __('Nombre nuevo seguro') . "</td>";
			echo "<td class='SeguroNombreOcultar'>";
			Html::autocompletionTextField($this, "name", array('value' => 'Resposabilidad civil'));
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Cía aseguradora') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cia_aseguradora");
			echo "</td>";
			echo "<td>" . __('Cuantía') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($this, "cuantia");
			echo "</td>";
			echo"</tr>";
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Fecha caducidad') . "</td>";
			echo "<td>";
			Html::showDateTimeField("fecha_caducidad");
			echo "</td>";
			echo "<td class='SeguroNAseguradosOcultar'>" . __('Nº empleados asegurados') . "</td>";
			echo "<td class='SeguroNAseguradosOcultar'>";
			Html::autocompletionTextField($this, "numero_empleados_asegurados");
			echo "</td>";
			echo"</tr>";
			

			echo"<tr class='tab_bg_1 center'>";
			echo"<td><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			/*///////////////////////////////
			//LISTAR SEGUROS DEL PROVEEDOR
			///////////////////////////////*/

			$query2 ="SELECT * FROM glpi_plugin_comproveedores_insurances WHERE cv_id=$CvId" ;

			$result2 = $DB->query($query2);

			//Ocultar lista, si no existe ninguna expeciencia
			if($result2->num_rows!=0){

				echo "<div align='center'><table class='tab_cadre_fixehov'>";
				echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Seguros del proveedor</th></tr>";
				echo"<br/>";
				echo "<tr><th>".__('Tipo de seguro')."</th>";
					echo "<th>".__('Cía Aseguradora')."</th>";
					echo "<th>".__('Cuantía')."</th>";
					echo "<th>".__('Fecha caducidad')."</th>";
					echo "<th>".__('Nº empleados asegurados')."</th>";
					echo "<th>".__('Eliminar')."</th>";
					echo "</tr>";

					while ($data=$DB->fetch_array($result2)) {
							if($data['is_deleted']==""){
								$data['is_deleted']=1;
							}

							echo "<tr class='tab_bg_2".($data["is_deleted"]=='1'?"_2":"")."'>";
							if ((in_array($data['entities_id'],$_SESSION['glpiactiveentities']))) {
								echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/insurance.form.php?id=".$data["id"]."'>".$data["name"];
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
								echo "<td class='center'>".$data['cia_aseguradora']."</td>";
								echo "<td class='center'>".$data['cuantia']."</td>";
								echo "<td class='center'>".$data['fecha_caducidad']."</td>";
								echo "<td class='center'>".$data['numero_empleados_asegurados']."</td>";	

								echo "<td class='center'>";
								echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/insurance.form.php method='post'>";
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
		}

	function getInsurence(){
			$insure = array('Resposabilidad civil',
				'Seguro todo riesgo',
				'Seguro accidentes de trabajo',
				'Otros Seguros');
			
			return $insure;
	}


	function consultaAjax(){

		GLOBAL $DB,$CFG_GLPI;

		$consulta="<script type='text/javascript'>

				$(document).ready(function() {

					$('.SeguroNombreOcultar').hide();

    				//Añadimos onchange al select de tipo de seguro
    				$('[id*=selectTipo]').change(function() {

    					//Cogemos el valor selecionado
    					$('select option:selected').each(function() {
      						valor=$( this ).text();
   						 });

   						//ocultamos o mostramos el nombre del nuevo seguro
   						if(valor=='Otros Seguros'){

   							$('.SeguroNombreOcultar').children('input[name=name]').val('');
   							$('.SeguroNombreOcultar').show();
   							$('.SeguroNAseguradosOcultar').hide();
   						}
   						if(valor!='Otros Seguros'){

   							$('.SeguroNombreOcultar').hide();
   							
   							//ocultamos o mostramos el número de asegurados
   							if(valor=='Resposabilidad civil'){
   								$('.SeguroNAseguradosOcultar').show();

   							}
   							if(valor!='Resposabilidad civil'){
   								$('.SeguroNAseguradosOcultar').hide();
   							}
   							
   							$('.SeguroNombreOcultar').children('input[name=name]').val(valor);
   							$('.SeguroNombreOcultar').hide();
   						}
  						
					});
    				
				});	
				
		</script>";

		return $consulta;
	}

}