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


			//Version anterior que no funcionaba
			//echo Html::hidden('supplier_id', array('value' => $this->getSupplierByUserID($user_Id)));		


			echo Html::hidden('supplier_id', array('value' => $item->fields['id']));


			echo "<td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "name");
			echo "</td>";

			echo "<td>" . __('CIF') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cif");
			echo "</td>";

			echo"<tr></tr>";

			echo "<td>" . __('Forma Juridica') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($this, "forma_juridica");
			echo "</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Dirección")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";

			echo "<td>" . __('Location') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $this->fields["locations_id"],
				'name'=>'locations_id',
				'entity' => $this->fields["entities_id"]));
			echo "</td>";


			echo "<td>" . __('Teléfono') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "contacto_telefono");
			echo "</td>";

			echo"<tr></tr>";

			echo "<td>" . __('Codigo Postal') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "codigo_postal");
			echo "</td>";

			echo "</tr>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='4'>".__("Empresa matriz(Si la tiene)")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";

			echo "<td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_nombre");
			echo "</td>";

			echo "<td>" . __('Dirección') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $this->fields["empresa_matriz_direccion"],
				'name'=>'empresa_matriz_direccion',
				'entity' => $this->fields["entities_id"]));
			echo "</td>";

			echo"<tr></tr>";

			echo "<td>" . __('Población') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_poblacion");
			echo "</td>";

			echo "<td>" . __('Provincia') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_provincia");
			echo "</td>";

			echo "</tr>";

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
			
			PluginComproveedoresUser::displayTabContentForItem($itemS,'','');

			PluginComproveedoresExperience::displayTabContentForItem($itemS,'','');
			


		}

		function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores y desde el menu
			global $CFG_GLPI;

			$options['colspan']      = 4;
			$options['formtitle']    = "Datos de la empresa";

			$user_Id=$_SESSION['glpiID'];


			$this->initForm($ID, $options);
			
			$this->showFormHeader($options);
			echo"<table class='tab_cadre_fixehov'><tbody>";
			echo "</br>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover '><th colspan='4'>".__("Información General")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov'>";

			echo Html::hidden('id', array('value' => $this->fields['id']));
			echo Html::hidden('supplier_id', array('value' => $this->getSupplierByUserID($user_Id)));
			
			echo "<td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "name");
			echo "</td>";

			echo "<td>" . __('CIF') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "cif");
			echo "</td>";

			echo"<tr></tr>";

			echo "<td>" . __('Forma Juridica') . "</td>";			
			echo "<td>";
			Html::autocompletionTextField($this, "forma_juridica");
			echo "</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover '><th  colspan='4'>".__("Dirección")."</th></tr>";
			echo "<tr class='tab_bg_2 tab_cadre_fixehov'>";

			echo "<td>" . __('Location') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $this->fields["locations_id"],
				'name'=>'locations_id',

				'entity' => $this->fields["entities_id"]));
			echo "</td>";

			echo "<td>" . __('Teléfono') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "contacto_telefono");
			echo "</td>";

			echo"<tr></tr>";

			echo "<td>" . __('Codigo Postal') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "codigo_postal");
			echo "</td>";

			echo "</tr>";


			
			echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover '><th colspan='4'>".__("Empresa matriz(Si la tiene)")."</th></tr>";
			echo "<tr class='tab_bg_2 nohover'>";

			echo "<td>" . __('Name') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_nombre");
			echo "</td>";

			echo "<td>" . __('Dirección') . "</td>";
			echo "<td>";
			Location::dropdown(array('value' => $this->fields["empresa_matriz_direccion"],
				'name'=>'empresa_matriz_direccion',
				'entity' => $this->fields["entities_id"]));
			echo "</td>";

			echo"<tr></tr>";

			echo "<td>" . __('Población') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_poblacion");
			echo "</td>";

			echo "<td>" . __('Provincia') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "empresa_matriz_provincia");
			echo "</td>";

			echo "</tr>";
			
			echo "</tbody></table>";
			$this->showFormButtons($options);
			

			$profile_Id=$this->getProfileByUserID($user_Id);
			$itemS= new Supplier;
			if($profile_Id!=9){
				$itemS->fields['supplier_id']=$this->fields['supplier_id'];
				$itemS->fields['cv_id']=$this->fields['id'];
			}else{
				$itemS->fields['supplier_id']=$this->getSupplierByUserID($user_Id);
				$itemS->fields['cv_id']=$this->fields['id'];
			}


			//var_dump($this);
			PluginComproveedoresUser::displayTabContentForItem($itemS,'','');

			PluginComproveedoresExperience::displayTabContentForItem($itemS,'','');
			

			
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