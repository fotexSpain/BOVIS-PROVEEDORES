<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresFinancial extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Financiero','Financiero',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Financiero');
			}
			return 'Financiero';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();

			//Entrada Administrador
			if($item->getType()=='Supplier'){	

				if(isset($item->fields['cv_id'])){
			
					$self->showFormItemFinancial($item, $withtemplate);

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

			$tab['common'] = ('Financiero');

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

		function showFormItemFinancial($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;

			$CvId=$item->fields['cv_id'];

			// Consultamo el id de la tabla integratedmanagementsystems al que esta asociado el supplier
			$query ="SELECT id FROM glpi_plugin_comproveedores_financials WHERE cv_id=".$CvId;

			$result = $DB->query($query);

			if($result->num_rows!=0){
				while ($data=$DB->fetch_array($result)) {
					$ID=$data['id'];
				}
			}
			else{
				$ID='';
			}
			
			$options = array();
			$options['formtitle']    = "Financiero";
			$options['colspan']=12;

			$this->initForm($ID, $options);
			
			$this->showFormHeader($options);
				
			echo Html::hidden('cv_id', array('value' => $CvId));

			//Aseguramiento calidad

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo "<td>Capital Social</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td>";
			$capital_social='';
			if(!empty($this->fields["capital_social"])){
				$capital_social=number_format($this->fields["capital_social"], 2, ',', '.');
			}
			Html::autocompletionTextField($this, "capital_social", array('value'=>$capital_social));
			echo"</td>";
			echo "<td colspan='10'>€</td>";

			echo "</tr>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td></td>";
			echo "</tr>";
			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td></td>";
			echo "</tr>";
			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td></td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan='12'>Facturación Anual</td>";
			echo "</tr>";
			

			echo"<tr class='tab_bg_1 center'>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td>Facturación</td>";
				echo "<td>Beneficios a/. Impuestos (Ebitda)</td>";
				echo "<td>Resultado</td>";
				echo "<td>Total Activo</td>";
				echo "<td>Avtivo Circulante</td>";
				echo "<td>Pasivo Circulante</td>";
				echo "<td>Cash flow al final del ejercicio</td>";
				echo "<td>fondos propios</td>";
				echo "<td>Recursos ajenos</td>";
			echo"</tr>";

			$facturacion;

			$query2 ="SELECT * FROM glpi_plugin_comproveedores_annualbillings WHERE cv_id=".$CvId." order by anio desc limit 3" ;

			$result2 = $DB->query($query2);
		
			if($result2->num_rows!=0){

				$i=0;
				while ($data=$DB->fetch_array($result2)) {

					$facturacion['facturacion'.$i]=substr(number_format($data['facturacion'], 0, '', '.'),0,strlen(number_format($data['facturacion'], 0, '', '.'))-4);
					$facturacion['beneficios_impuestos'.$i]=substr(number_format($data['beneficios_impuestos'], 0, '', '.'),0,strlen(number_format($data['beneficios_impuestos'], 0, '', '.'))-4);
					$facturacion['resultado'.$i]=substr(number_format($data['resultado'], 0, '', '.'),0,strlen(number_format($data['resultado'], 0, '', '.'))-4);
					$facturacion['total_activo'.$i]=substr(number_format($data['total_activo'], 0, '', '.'),0,strlen(number_format($data['total_activo'], 0, '', '.'))-4);
					$facturacion['activo_circulante'.$i]=substr(number_format($data['activo_circulante'], 0, '', '.'),0,strlen(number_format($data['activo_circulante'], 0, '', '.'))-4);
					$facturacion['pasivo_circulante'.$i]=substr(number_format($data['pasivo_circulante'], 0, '', '.'),0,strlen(number_format($data['pasivo_circulante'], 0, '', '.'))-4);
					$facturacion['cash_flow'.$i]=substr(number_format($data['cash_flow'], 0, '', '.'),0,strlen(number_format($data['cash_flow'], 0, '', '.'))-4);
					$facturacion['fondos_propios'.$i]=substr(number_format($data['fondos_propios'], 0, '', '.'),0,strlen(number_format($data['fondos_propios'], 0, '', '.'))-4);
					$facturacion['recursos_ajenos'.$i]=substr(number_format($data['recursos_ajenos'], 0, '', '.'),0,strlen(number_format($data['recursos_ajenos'], 0, '', '.'))-4);
					$i++;
				}
			}

			for($i=0; $i<3; $i++){


				echo"<tr class='tab_bg_1 center'>";
				echo "<td>Año:</td>";
				echo "<td><input class='center' style='border:none;' type='text' name='anio".$i."' value='".(date("Y")-$i)."' readonly></td>";
				if(!empty($facturacion)){
					echo "<td>";
					Html::autocompletionTextField($this, "facturacion".$i, array('value'=>$facturacion['facturacion'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "beneficios_impuestos".$i, array('value'=>$facturacion['beneficios_impuestos'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "resultado".$i, array('value'=>$facturacion['resultado'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "total_activo".$i, array('value'=>$facturacion['total_activo'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "activo_circulante".$i, array('value'=>$facturacion['activo_circulante'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "pasivo_circulante".$i, array('value'=>$facturacion['pasivo_circulante'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "cash_flow".$i, array('value'=>$facturacion['cash_flow'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "fondos_propios".$i, array('value'=>$facturacion['fondos_propios'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "recursos_ajenos".$i, array('value'=>$facturacion['recursos_ajenos'.$i]));
					echo"</td>";
					echo "<td>x1.000€</td>";

					echo"</tr>";
				}else{
					echo "<td>";
					Html::autocompletionTextField($this, "facturacion".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "beneficios_impuestos".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "resultado".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "total_activo".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "activo_circulante".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "pasivo_circulante".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "cash_flow".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "fondos_propios".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "recursos_ajenos".$i);
					echo"</td>";
					echo "<td>x1.000€</td>";

					echo"</tr>";
				}
				
			}

			echo"</tbody>";
			echo"</table>";
			echo"</div>";

			$this->showFormButtons($options);
					
		}


		function showFormNoCV($ID, $options=[]) {
			//Aqui entra cuando no tien gestionado el curriculum

			echo "<div>Necesitas gestionar el CV antes de añadir Sistema integrado de gestión</div>";
			echo "<br>";
		}

		

		function showForm($ID, $options=[]) {
			
			
		}

		function showFormItem($item, $withtemplate='') {	
		
			GLOBAL $DB,$CFG_GLPI;

			$CvId=$item->fields['id'];

			// Consultamo el id de la tabla integratedmanagementsystems al que esta asociado el supplier
			$query ="SELECT id FROM glpi_plugin_comproveedores_financials WHERE cv_id=".$CvId;

			$result = $DB->query($query);

			if($result->num_rows!=0){
				while ($data=$DB->fetch_array($result)) {
					$ID=$data['id'];
				}
			}
			else{
				$ID='';
			}
			
			$options = array();
			$options['formtitle']    = "Financiero";
			$options['colspan']=12;

			$this->initForm($ID, $options);
			
			$this->showFormHeader($options);
				
			echo Html::hidden('cv_id', array('value' => $CvId));

			//Aseguramiento calidad

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo "<td>Capital Social</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td>";
			$capital_social='';
			if(!empty($this->fields["capital_social"])){
				$capital_social=number_format($this->fields["capital_social"], 2, ',', '.');
			}
			Html::autocompletionTextField($this, "capital_social", array('value'=>$capital_social));
			echo"</td>";
			echo "<td colspan='10'>€</td>";

			echo "</tr>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td></td>";
			echo "</tr>";
			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td></td>";
			echo "</tr>";
			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo"<td></td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan='12'>Facturación Anual</td>";
			echo "</tr>";
			

			echo"<tr class='tab_bg_1 center'>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td>Facturación</td>";
				echo "<td>Beneficios a/. Impuestos (Ebitda)</td>";
				echo "<td>Resultado</td>";
				echo "<td>Total Activo</td>";
				echo "<td>Avtivo Circulante</td>";
				echo "<td>Pasivo Circulante</td>";
				echo "<td>Cash flow al final del ejercicio</td>";
				echo "<td>fondos propios</td>";
				echo "<td>Recursos ajenos</td>";
			echo"</tr>";

			$facturacion;

			$query2 ="SELECT * FROM glpi_plugin_comproveedores_annualbillings WHERE cv_id=".$CvId." order by anio desc limit 3" ;

			$result2 = $DB->query($query2);
		
			if($result2->num_rows!=0){

				$i=0;
				while ($data=$DB->fetch_array($result2)) {

					$facturacion['facturacion'.$i]=substr(number_format($data['facturacion'], 0, '', '.'),0,strlen(number_format($data['facturacion'], 0, '', '.'))-4);
					$facturacion['beneficios_impuestos'.$i]=substr(number_format($data['beneficios_impuestos'], 0, '', '.'),0,strlen(number_format($data['beneficios_impuestos'], 0, '', '.'))-4);
					$facturacion['resultado'.$i]=substr(number_format($data['resultado'], 0, '', '.'),0,strlen(number_format($data['resultado'], 0, '', '.'))-4);
					$facturacion['total_activo'.$i]=substr(number_format($data['total_activo'], 0, '', '.'),0,strlen(number_format($data['total_activo'], 0, '', '.'))-4);
					$facturacion['activo_circulante'.$i]=substr(number_format($data['activo_circulante'], 0, '', '.'),0,strlen(number_format($data['activo_circulante'], 0, '', '.'))-4);
					$facturacion['pasivo_circulante'.$i]=substr(number_format($data['pasivo_circulante'], 0, '', '.'),0,strlen(number_format($data['pasivo_circulante'], 0, '', '.'))-4);
					$facturacion['cash_flow'.$i]=substr(number_format($data['cash_flow'], 0, '', '.'),0,strlen(number_format($data['cash_flow'], 0, '', '.'))-4);
					$facturacion['fondos_propios'.$i]=substr(number_format($data['fondos_propios'], 0, '', '.'),0,strlen(number_format($data['fondos_propios'], 0, '', '.'))-4);
					$facturacion['recursos_ajenos'.$i]=substr(number_format($data['recursos_ajenos'], 0, '', '.'),0,strlen(number_format($data['recursos_ajenos'], 0, '', '.'))-4);
					$i++;
				}
			}

			for($i=0; $i<3; $i++){


				echo"<tr class='tab_bg_1 center'>";
				echo "<td>Año:</td>";
				echo "<td><input class='center' style='border:none;' type='text' name='anio".$i."' value='".(date("Y")-$i)."' readonly></td>";
				if(!empty($facturacion)){
					echo "<td>";
					Html::autocompletionTextField($this, "facturacion".$i, array('value'=>$facturacion['facturacion'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "beneficios_impuestos".$i, array('value'=>$facturacion['beneficios_impuestos'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "resultado".$i, array('value'=>$facturacion['resultado'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "total_activo".$i, array('value'=>$facturacion['total_activo'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "activo_circulante".$i, array('value'=>$facturacion['activo_circulante'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "pasivo_circulante".$i, array('value'=>$facturacion['pasivo_circulante'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "cash_flow".$i, array('value'=>$facturacion['cash_flow'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "fondos_propios".$i, array('value'=>$facturacion['fondos_propios'.$i]));
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "recursos_ajenos".$i, array('value'=>$facturacion['recursos_ajenos'.$i]));
					echo"</td>";
					echo "<td>x1.000€</td>";

					echo"</tr>";
				}else{
					echo "<td>";
					Html::autocompletionTextField($this, "facturacion".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "beneficios_impuestos".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "resultado".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "total_activo".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "activo_circulante".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "pasivo_circulante".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "cash_flow".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "fondos_propios".$i);
					echo"</td>";
					echo "<td>";
					Html::autocompletionTextField($this, "recursos_ajenos".$i);
					echo"</td>";
					echo "<td>x1.000€</td>";

					echo"</tr>";
				}
				
			}

			echo"</tbody>";
			echo"</table>";
			echo"</div>";

			$this->showFormButtons($options);
		}

}