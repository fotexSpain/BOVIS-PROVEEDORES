<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresSelectionSupplier extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Selección Proveedor','Selección Proveedor',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Selección Proveedor');
			}
			return 'Selección Proveedor';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();

			//Entrada Administrador
			
			$self->showFormItem($item, $withtemplate);
				
			


		}

		function getSearchOptions(){

			$tab = array();

			$tab['common'] = ('Selección Proveedor');

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
                        
                        echo $this->consultaAjax();

			$projecttasks_id=$item->fields['id']; 
                        echo "<div id='selector_proveedor' align='center'>";
                        echo "<div style='display: inline-block;' onclick='seleccionProvedorFiltro(".$projecttasks_id.")'><span class='vsubmit' style='margin-right: 15px;'>AÑADIR PROVEEDOR </span></div>";
			
                             $query ="SELECT 
                                proveedores.name, 
                                proveedores.cif, 
                                proveedores.comment 
                                FROM glpi_projecttaskteams as projectaskteams 
                                LEFT JOIN glpi_suppliers as proveedores on projectaskteams.items_id=proveedores.id 
                                WHERE projectaskteams.projecttasks_id=$projecttasks_id" ;
                                               
			$result = $DB->query($query);

                                                echo "<table class='tab_cadre_fixehov'>";
                                                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='6'>Proveedores seleccionados</th></tr>";
                                                echo"<br/>";
                                                echo "<tr><th>".__('Proveedor')."</th>";
                                                        echo "<th>".__('CIF')."</th>";
                                                        echo "<th>".__('Comentario')."</th>";
                                                        
                                                echo "</tr>";
                                                
                                                if($result->num_rows!=0){
                                                        while ($data=$DB->fetch_array($result)) {
                                                                       
                                                            echo "<tr>";
                                                                echo "<td class='center'>".$data['name']."</td>";
                                                                echo "<td class='center'>".$data['cif']."</td>";
                                                                echo "<td class='center'>".$data['comment']."</td>";
                                                            echo "</tr>";
                                                            
                                                        }
                                                }
                                                echo"<br/>";
                                                echo "</table></div>";
                                                echo"<br>";
                                              
		}
                
                function consultaAjax(){

                    GLOBAL $CFG_GLPI;

                    $consulta="<script type='text/javascript'>
                        
                        function seleccionProvedorFiltro(paquete_id){
                            
                            $.ajax({ 
				async: false, 
            			type: 'GET',
            			data: {'paquete_id':  paquete_id},                  
           			url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/selectionSupplierF1.php',                    
           			success:function(data){
                                    $('#selector_proveedor').html(data);
                		},
                		error: function(result) {
                                    alert('Data not found');
                		}
                            });
            		};

                    </script>";

                    return $consulta;
		}
}