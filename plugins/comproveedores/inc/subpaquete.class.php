<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresSubpaquete extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('SubPContrato','SubContratos',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('SubContratos');
			}
			return 'SubContratos';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();

			//Entrada Administrador
			
			$self->showFormItem($item, $withtemplate);
				
			


		}

		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('Subpaquete');

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

			$projecttasks_id=$item->fields['id']; 

			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/subpaquete.form.php method='post'>";		
			echo Html::hidden('projecttasks_id', array('value' => $projecttasks_id));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='6'>SubPaquete</th></tr>";
                        
			echo"<tr class='tab_bg_1 center'>";
			echo "<td>" . __('Nombre') . "</td>";
			echo "<td>";
                                                Html::autocompletionTextField($this, "name");
			echo "</td>";
			echo "<td>". __('Proveedor') . "</td>";
			echo "<td>";                    
                                                Dropdown::show('supplier',array('comments'=>false, 'width'=>'250px'));
			echo "</td>";
			echo "<td>" . __('Valoración') . "</td>";
			echo "<td>";
			Html::autocompletionTextField($this, "valoracion");
			echo "</td>";
			echo "</tr>";
			

			echo"<tr class='tab_bg_1 center'>";
			echo"<td colspan='6'><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"<tr class='tab_bg_1'>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			$query2 ="SELECT * FROM glpi_plugin_comproveedores_subpaquetes WHERE projecttasks_id=$projecttasks_id" ;
                                               
			$result2 = $DB->query($query2);

                                                echo "<div align='center'><table class='tab_cadre_fixehov'>";
                                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>SubPaquetes</th></tr>";
                                                echo"<br/>";
                                                echo "<tr><th>".__('SubPaquete')."</th>";
                                                        echo "<th>".__('Proveedor')."</th>";
                                                        echo "<th>".__('Valoración')."</th>";
                                                echo "</tr>";
                                                
                                                if($result2->num_rows!=0){
                                                        while ($data=$DB->fetch_array($result2)) {
                                                                       
                                                                echo "<tr>";
                                                                        echo "<td class='center'>".$data['name']."</td>";
                                                                        echo "<td class='center'>".Dropdown::getDropdownName("glpi_suppliers",$data['suppliers_id'])."</td>";
                                                                        echo "<td class='center'>".$data['valoracion']."</td>";
                                                                echo "</tr>";

                                                        }
                                                }
                                                echo"<br/>";
			echo "</table></div>";
                                                echo"<br>";
                                              
		}

}