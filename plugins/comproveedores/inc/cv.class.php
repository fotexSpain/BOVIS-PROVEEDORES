<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresCv extends CommonDBTM{

		static public $itemtype	=	'PluginComproveedoresComproveedore';
		static public $items_id	=	'plugin_comproveedores_cv';
		static $types = array('Computer');

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('CURRICULUM DE LA EMPRESA','CURRICULUM DE LA EMPRESA',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Gestion de CV');
			}
			return 'CV Detallado';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){
			
			global $CFG_GLPI;
			$self = new self();
			if($item->getType()=='Supplier'){
				$self->showFormItem($item, $withtemplate);
			}else if($item->getType()=='PluginComproveedoresComproveedore'){
				$self->showFormComproveedores();
			}else{
					//$self->showForm();
			}
		}
		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('CVs');

			$tab[1]['table']	=$this->getTable();
			$tab[1]['field']	='name';
			$tab[1]['name']		=__('Name');
			$tab[1]['datatype']		='itemlink';
			$tab[1]['itemlink_type']	=$this->getTable();

			return $tab;

		}

		function defineTabs($options=array()){
			$ong = array();

			$this->addDefaultFormTab($ong);						
			$this->addStandardTab('PluginComproveedoresUser', $ong, $options);
			$this->addStandardTab('PluginComproveedoresExperience', $ong, $options);
			$this->addStandardTab('PluginComproveedoresListspecialty', $ong, $options);


			return $ong;
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
			//Aqui entra desde la pestaña de proveedores
			$ID=$item->fields['cv_id'];	
			$options = array();
			$options['colspan']  = 3;
			
			$this->initForm($ID, $options);
			$this->showFormHeader($options);

			$user_Id=$_SESSION['glpiID'];

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Información General")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";


			echo Html::hidden('supplier_id', array('value' => $item->fields['id']));

			echo"<tr>";
			echo "<td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "name");
			echo "</td>";

			echo "<td>" . __('CIF') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "cif");
			echo "</td>";

			echo "</tr>";

			echo "<td>" . __('Forma Juridica') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($item, "forma_juridica");
			echo "</td>";
			echo "<td>"._n('Email', 'Emails', 1)."</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "email");
			echo "</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>".__('Fax')."</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "fax");
			echo "</td>";
			echo "<td>".__('Website')."</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "website");
			echo "</td>";

			echo "</tr>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>" . __('Teléfono') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "phonenumber");
			echo "</td>";
			echo "<td>"._x('location', 'State')."</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "state");
			echo"</td></tr>";

			echo "<tr  class='tab_bg_1'>";
			echo "<td class='middle'>".__('Address')."</td>";
			echo "<td class='middle'>";
			echo "<textarea cols='37' rows='3' name='address'>".$item->fields["address"]."</textarea>";
			echo "</td>";
			echo"</tr>";




			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Dirección")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>".__('Country')."</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "country");
			echo "</td>";
			echo "<td>" . __('Location') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $item->fields["locations_id"],
				'name'=>'locations_id',
				'entity' => $item->fields["entities_id"]));
			echo"</td></tr>";


			echo"<tr class='tab_bg_1'>";
			echo "<td>" . __('Codigo Postal') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "postcode");
			echo "</td>";
			echo"<td>". __('City')."</td>";
			echo "<td>";
			Html::autocompletionTextField($item, "town", ['size' => 23]);

			echo "</td>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Empresa matriz(Si la tiene)")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";


			echo "<tr class='tab_bg_1'><td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_nombre");
			echo "</td>";

			echo "<td>" . __('Dirección') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $this->fields["empresa_matriz_direccion"],
				'name'=>'empresa_matriz_direccion',
				'entity' => $this->fields["entities_id"]));
			echo "</td></tr>";

			echo"<tr class='tab_bg_1'>";

			echo "<td>" . __('Población') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_poblacion");
			echo "</td>";

			echo "<td>" . __('Provincia') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_provincia");
			echo "</td></tr>";

			///////////////////Categoías y número de empleados//////////
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Categoría y número de empleados")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>Nº ";
			Html::autocompletionTextField($this, "titulacion_superior");
			echo "</td>";
			echo "<td>" . __('Titulación Superior') . "</td>";
			echo "<td>Nº ";
			Html::autocompletionTextField($this, "personal");
			echo "</td>";
			echo "<td>" . __('Personal') . "</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>Nº ";
			Html::autocompletionTextField($this, "titulacion_grado_medio");
			echo "</td>";
			echo "<td>" . __('Titulación Grado Medio') . "</td>";
			echo "<td>Nº ";
			Html::autocompletionTextField($this, "otros_categoria_numeros_empleados");
			echo "</td>";
			echo "<td>" . __('Otros') . "</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>Nº ";
			Html::autocompletionTextField($this, "tecnicos_no_universitarios");
			echo "</td>";
			echo "<td>" . __('Técnicos No Universitarios') . "</td>";
			echo "</tr>";

			///////////////////
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Comentarios")."</th></tr>";

			echo"<tr>";
			echo "<td rowspan='8' class='middle right'>".__('Comments')."</td>";
			echo "<td class='center middle' rowspan='8'>";
			echo "<textarea cols='45' rows='13' name='comment' >".$item->fields["comment"]."</textarea>";
			echo "</td></tr>";


			echo "</tbody></table>";

			$this->showFormButtons($options);


			/*
			*	ENTRAMOS AL CURRICULUM DEL PROVEEDOR MEDIANTE EL SUPPLIER_id
			*
			*	LLAMAMOS A LAS CLASES USER Y EXPERIENCE PASANDO EL ID DEL PROVEEEDOR Y EL ID DEL CURRICULUM 
			*/


			$itemS= new Supplier;
			$itemS->fields['supplier_id']=$item->fields['id'];
			$itemS->fields['cv_id']=$item->fields['cv_id'];


			//Ocultar si el proveedor no tiene cv_id




			if($itemS->fields['cv_id']!=0 && $itemS->fields['cv_id']!= null){

			//	PluginComproveedoresUser::displayTabContentForItem($itemS,'','');

				//PluginComproveedoresExperience::displayTabContentForItem($itemS,'','');

			//	PluginComproveedoresListspecialty::displayTabContentForItem($itemS,'','');

				//PluginComproveedoresEmpleado::displayTabContentForItem($itemS,'','');
			}
			
			
			


		}

		function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores y desde el menu
			global $CFG_GLPI;

			$options['colspan']      = 4;
			$options['formtitle']    = "Datos de la empresa";

			$user_Id=$_SESSION['glpiID'];
			$profile_Id=$this->getProfileByUserID($user_Id);

			$data=$this->getSupplierCompleteByCv($ID);


			$this->initForm($ID, $options);
			
			$this->showFormHeader($options);
			echo"<table class='tab_cadre_fixehov'><tbody>";
			echo "</br>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover '><th colspan='4'>".__("Información General")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov'>";

			echo Html::hidden('id', array('value' => $this->fields['id']));

			if($profile_Id!=9){
				
				echo Html::hidden('supplier_id', array('value' => $this->fields['supplier_id']));
			}else{
				echo Html::hidden('supplier_id', array('value' => $this->getSupplierByUserID($user_Id)));
			}
			
			
			echo"<tr>";
			echo "<td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "name");
			echo "</td>";

			echo "<td>" . __('CIF') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "cif");
			echo "</td>";

			echo "</tr>";

			echo "<td>" . __('Forma Juridica') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($data, "forma_juridica");
			echo "</td>";
			echo "<td>"._n('Email', 'Emails', 1)."</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "email");
			echo "</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>".__('Fax')."</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "fax");
			echo "</td>";
			echo "<td>".__('Website')."</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "website");
			echo "</td>";

			echo "</tr>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>" . __('Teléfono') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "phonenumber");
			echo "</td>";
			echo "<td>"._x('location', 'State')."</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "state");
			echo"</td></tr>";

			echo "<tr  class='tab_bg_1'>";
			echo "<td class='middle'>".__('Address')."</td>";
			echo "<td class='middle'>";
			echo "<textarea cols='37' rows='3' name='address'>".$data->fields["address"]."</textarea>";
			echo "</td>";
			echo"</tr>";




			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Dirección")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";

			echo "<tr class='tab_bg_1'>";
			echo "<td>".__('Country')."</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "country");
			echo "</td>";
			echo "<td>" . __('Location') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $data->fields["locations_id"],
				'name'=>'locations_id',
				'entity' => $data->fields["entities_id"]));
			echo"</td></tr>";






			echo"<tr class='tab_bg_1'>";
			echo "<td>" . __('Codigo Postal') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "postcode");
			echo "</td>";
			echo"<td>". __('City')."</td>";
			echo "<td>";
			Html::autocompletionTextField($data, "town", ['size' => 23]);

			echo "</td>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Empresa matriz(Si la tiene)")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";


			echo "<tr class='tab_bg_1'><td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_nombre");
			echo "</td>";

			echo "<td>" . __('Dirección') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $this->fields["empresa_matriz_direccion"],
				'name'=>'empresa_matriz_direccion',
				'entity' => $this->fields["entities_id"]));
			echo "</td></tr>";

			echo"<tr class='tab_bg_1'>";

			echo "<td>" . __('Población') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_poblacion");
			echo "</td>";

			echo "<td>" . __('Provincia') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_provincia");
			echo "</td></tr>";

			echo "</tr>";


			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Comentarios")."</th></tr>";

			echo"<tr>";
			echo "<td rowspan='8' class='middle right'>".__('Comments')."</td>";
			echo "<td class='center middle' rowspan='8'>";
			echo "<textarea cols='45' rows='13' name='comment' >".$data->fields["comment"]."</textarea>";
			echo "</td></tr>";


			echo "</tbody></table>";
			$this->showFormButtons($options);
			

			
			$itemS= new Supplier;
			if($profile_Id!=9){
				$itemS->fields['supplier_id']=$this->fields['supplier_id'];
				$itemS->fields['cv_id']=$this->fields['id'];
			}else{
				$itemS->fields['supplier_id']=$this->getSupplierByUserID($user_Id);
				$itemS->fields['cv_id']=$this->fields['id'];
			}

			//Ocultar si el proveedor no tiene cv_id
			if($itemS->fields['cv_id']!=0 && $itemS->fields['cv_id']!= null){

				//PluginComproveedoresUser::displayTabContentForItem($itemS,'','');

				//PluginComproveedoresExperience::displayTabContentForItem($itemS,'','');
			
				//PluginComproveedoresListspecialty::displayTabContentForItem($itemS,'','');
			}
			
		}

		function getSupplierCompleteByCv($Id){
			global $DB;
			$options=array();
			$query ="SELECT *  FROM glpi_suppliers WHERE cv_id=$Id";

			$result=$DB->query($query);
			$data=$DB->fetch_array($result);

			$itemSupplier=new Supplier();
			
			
			foreach ($data as $key => $value) {
				$itemSupplier->fields[$key]=$value;
			}
			return $itemSupplier;
		}

		function getSupplierByUserID($Id){
			global $DB;
			$options=array();
			$query ="SELECT supplier_id as cv FROM glpi_users WHERE id=$Id";

			$result=$DB->query($query);
			$id=$DB->fetch_array($result);

			if($id['cv']<>''){
				$options['id']=$id['cv'];
			}

			return $options['id'];
		}

		function getProfileByUserID($Id){
			global $DB;

			$query ="SELECT profiles_id as profile FROM glpi_users WHERE id=$Id";

			$result=$DB->query($query);
			$id=$DB->fetch_array($result);

			if($id['profile']<>''){
				$options['profile']=$id['profile'];
			}
			return $options['profile'];
		}

	}