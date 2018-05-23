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
			
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";

			echo Html::hidden('idExperiencia');

			echo"<th colspan='33'>Experiencia</th></tr>";
			echo"<tr class='tab_bg_1 center'>";

			echo "<td>" . __('Nombre proyecto') . "</td>";

			echo "<td>" . __('Estado') . "</td>";
			
			echo  "<td>" . __('Intervención de BOVIS') . "</td>";

			echo  "<td class='tipos_experiencias'>" . __('Tipos de experiencias') . "</td>";
			
			echo "<td>" . __('Comunidad Autonoma') . "</td>";
			
			echo "<td>" . __('Cliente') . "</td>";
			
			echo "<td>" . __('Año') . "</td>";
			
			echo "<td>" . __('Importe contratado') . "</td>";
			
			echo "<td>" . __('Duración de su contratado') . "</td>";
			
			echo "<td>" . __('BIM') . "</td>";
			
			echo "<td>" . __('Breeam') . "</td>";
			
			echo "<td>" . __('Leed') . "</td>";
			
			echo "<td>" . __('Otros certificados') . "</td>";
			
			echo "<td>" . __('Cpd Tier') . "</td>";
			
			echo "<td>" . __('Observaciones') . "</td>";
			

			echo "<td rowspan='2'><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo "<td rowspan='2'><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete'>AÑADIR SIN BORRAR</span></td>";
			echo "<td rowspan='2'><span class='vsubmit' onclick='guardarModificar();' name='Update'>GUARDAR MODIFICACIÓN</span></td>";


			echo"<tr class='tab_bg_1'>";
			
			echo "<td id='nombreExperiencia'>";
			echo "<textarea cols='20' rows='3' name='name'></textarea>";
			echo "</td>";

			echo "<td>";
			Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'));
			echo "</td>";

			echo "<td id='intervencionBovis'>";
			//Dropdown::showYesNo('intervencion_bovis');
			echo "<input type='checkbox' name='intervencion_bovis' value='1' style='margin-left: 30px;'>";
			echo "</td>";

			echo "<td class='tipos_experiencias'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt);
			echo "</td>";

			echo "<td>";
			Dropdown::show('PluginComproveedoresCommunity',$opt);
			echo "</td>";

			echo "<td>";
			echo "<textarea cols='20' rows='3' name='cliente'></textarea>";
			echo "</td>";

			echo "<td>";
			Dropdown::showFromArray('anio',$this->getYears());
			echo "</td>";

			echo "<td id='importeExperiencia'>";
			Html::autocompletionTextField($this, "importe");
			echo "</td>";

			echo "<td>";
			Html::autocompletionTextField($this, "duracion");
			echo "</td>";

			echo "<td>";
			echo "<input type='checkbox' name='bim' value='1' style='margin-left: 5px;'>";
			//Dropdown::showFromArray('bim', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td>";
			echo "<input type='checkbox' name='breeam' value='1' style='margin-left: 15px;'>";
			//Dropdown::showFromArray('breeam', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td>";
			echo "<input type='checkbox' name='leed' value='1' style='margin-left: 8px;'>";
			//Dropdown::showFromArray('leed', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td>";
			echo "<input type='checkbox' name='otros_certificados' value='1' style='margin-left: 25px;'>";
			//Dropdown::showFromArray('otros_certificados', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td>";
			echo "<input type='checkbox' name='cpd_tier' value='1' style='margin-left: 5px;'>";
			//Dropdown::showFromArray('cpd_tier', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td>";
			echo "<textarea cols='20' rows='3' name='observaciones'></textarea>";
			//Html::autocompletionTextField($this, "observaciones");
			echo "</td>";

			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo "</div>";
			echo"</form>";
			

		/*///////////////////////////////
			//LISTAR EXPERIENCIA DEL PROVEEDOR
			///////////////////////////////*/
			echo "<div id='accordion'>";

			echo"<h3>Intervención Bovis</h3>";
  			echo"<div id='intervencion_bovis'>";  
  			echo"</div>";

  			$tipos_experiencia_lista=array(
    			"1" => "Edificios de oficinas",
    			"2" => "Centros comerciales/locales comerciales",
    			"3" => "Proyectos de hospitales/Centros sanitarios",
    			"4" => "Proyectos de hoteles/Residencias 3ª edad/Residencias estudiantes",
    			"5" => "Proyectos de equipamiento-museos, Centros culturales, Auditorios, Centros de convenciones, palacios congresos",
    			"6" => "Centros docentes(Universidades,Institutos de enseñanza, Guarderías infatiles,etc)",
    			"7" => "Complejos deportivos(Estadios de fútbol, Pabellones deportivos, Polideportivos, etc)",
    			"8" => "Proyectos industriales/Logísticos",
    			"9" => "Proyectos de vivienda residenciales",
    			"10" => "Obras de rehabilitación de edificios",
    			"11" => "Centro de procesos de datos(CPD) y otros proyectos",
			);

			foreach ($tipos_experiencia_lista as $key => $value) {
				
				echo"<h3>".$value."</h3>";
  				echo"<div id='tipo_experiencia_".$key."'>";  
  				echo"</div>";

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

			echo "<p style='vertical-align:middle; font-size: 15px; font-weight:bold; margin: 10px 0px'> <img id='nuevaExperiencia' style='vertical-align:middle; margin: 10px 0px;' src='../pics/meta_plus.png'> Añadir experiencia</p>";

			echo"<form id='formulario' style='display:none' action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/experience.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center' id='actualizarFormulario'>";
			
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";

			echo Html::hidden('idExperiencia');

			echo"<th colspan='9' style='background-color:#BDBDBD; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>Experiencia</th></tr>";
			echo"<tr class='tab_bg_1 center'  style='background-color:#D8D8D8; border: 20px solid #BDBDDB;'>";

			echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Proy') . "</td>";

			echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Estado') . "</td>";

			echo  "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;' class='tipos_experiencias'>" . __('Exper.') . "</td>";

			echo "<td style='width:100px; font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Meses') . "</td>";

			echo "<td style='width:12%;font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('BOVIS') . "</td>";
			
			echo "<td style='width:12%;font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('BIM') . "</td>";
			
			echo "<td style='width:12%;font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Breeam') . "</td>";
			
			echo "<td style='width:12%;font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Leed') . "</td>";
			
			echo "<td style='width:12%; font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Otros') . "</td>";


			echo "</tr>";

			echo"<tr class='tab_bg_1' style='background-color:#D8D8D8; border: 20px;'>";

			echo "<td id='nombreExperiencia' style='border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
			echo "<textarea style='padding:7px; resize: none; display:block; margin-left:auto; margin-right:auto;' cols='20' rows='3' name='name'></textarea>";
			echo "</td>";

			echo "<td style='text-align: center; border-right: 2px solid #BDBDBD;'>";
			Dropdown::showFromArray('estado',array(1 =>'En curso' , 0 =>'Finalizado'));
			echo "</td>";

			echo "<td class='tipos_experiencias' style='text-align: center; border-right: 2px solid #BDBDBD;'>";
			Dropdown::show('PluginComproveedoresExperiencestype', $opt);

			echo "<td style='text-align-last: center; border-right: 2px solid #BDBDBD;'>";
			Html::autocompletionTextField($this, "duracion", ['option' =>'style="width: 100px;"']);
			echo "</td>";

			echo "<td id='intervencionBovis' style='width:12%; text-align-last: center; border-right: 2px solid #BDBDBD;'>";
			//Dropdown::showYesNo('intervencion_bovis');
			echo "<input type='checkbox' name='intervencion_bovis' value='1'>";
			echo "</td>";

			echo "<td style='width:12%; text-align-last: center; border-right: 2px solid #BDBDBD;'>";
			echo "<input type='checkbox' name='bim' value='1'>";
			//Dropdown::showFromArray('bim', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td style='width:12%; text-align-last: center; border-right: 2px solid #BDBDBD;'>";
			echo "<input type='checkbox' name='breeam' value='1'>";
			//Dropdown::showFromArray('breeam', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td style='width:12%; text-align-last: center; border-right: 2px solid #BDBDBD;'>";
			echo "<input type='checkbox' name='leed' value='1'>";
			//Dropdown::showFromArray('leed', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "<td style='width:12%; text-align-last: center; border-right: 2px solid #BDBDBD;'>";
			echo "<input type='checkbox' name='otros_certificados' value='1'>";
			//Dropdown::showFromArray('otros_certificados', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo "</td>";

			echo "</tr>";

			echo"<tr class='tab_bg_1 center' style='background-color:#d8d8d8;'>";
			
			echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Cliente') . "</td>";
			
			echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Año') . "</td>";
			
			echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('CCAA') . "</td>";

			echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Importe') . "</td>";

			echo Html::hidden("<td>" . __('Cpd Tier') . "</td>");
			
			echo "<td colspan='5' style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Observaciones') . "</td>";

			echo "</tr>";

			echo"<tr class='tab_bg_1 center' style='background-color:#D8D8D8;'>";

			echo Html::hidden("<td>");
			echo Html::hidden("<input type='checkbox' name='cpd_tier' value='1' style='text-align-last: center'>");
			//Dropdown::showFromArray('cpd_tier', array(-1 =>'------', 1=>'Sí' , 0 =>'No'));
			echo Html::hidden("</td>");

			echo "<td style='text-align: center; border-bottom: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
			echo "<textarea style='padding:7px; resize: none;' cols='20' rows='3' name='cliente'></textarea>";
			echo "</td>";

			echo "<td style='border-bottom: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
			Dropdown::showFromArray('anio',$this->getYears());
			echo "</td>";

			echo "<td style='text-align: center; border-bottom: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
			Dropdown::show('PluginComproveedoresCommunity',$opt);
			echo "</td>";

			echo "<td id='importeExperiencia' style='text-align-last: center; border-bottom: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
			Html::autocompletionTextField($this, "importe", ['option' =>'style="width: 100px;"']);
			echo "</td>";

			echo "<td colspan='5' style='border-bottom: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
			echo "<textarea style='padding:7px; width:95%; resize: none;' cols='20' rows='3' name='observaciones'></textarea>";
			//Html::autocompletionTextField($this, "observaciones");
			echo "</td>";

			echo"</tr>";

			echo"<tr>";
					
			echo"</tbody>";
			echo"</table>";


			echo "<div style='margin-bottom: 15px; margin-top: 15px;'>";
			echo "<div style='display: inline-block;'><input type='submit' class='submit' name='add' value='AÑADIR' style='margin-right: 15px;'/></div>";
			echo "<div style='display: inline-block;'><span class='vsubmit' onclick='añadirSinBorrar();' name='addNoDelete' style='margin-right: 15px;'>AÑADIR SIN BORRAR </span></div>";
			echo"<span class='vsubmit' id='guardar_modificacion' name='Update' style='margin-right:15px;'>GUARDAR MODIFICACIÓN</span>";
			
			echo "<div style='display: inline-block;'><span class='vsubmit' onclick='limpiarFormulario();' name='addNoDelete' style='margin-right: 15px;'>LIMPIAR</span></div>";
			echo "</div>";


			echo "</div>";

			echo"</form>";
			

			/*///////////////////////////////
			//LISTAR EXPERIENCIA DEL PROVEEDOR
			///////////////////////////////*/

			echo "<div id='accordion'>";

				///////Intervencion Bovis			 	
				$cadena= "select count(*) as numero, intervencion_bovis as bovis from glpi_plugin_comproveedores_experiences where cv_id={$CvId} and intervencion_bovis=1 group by intervencion_bovis";

                $result = $DB->query($cadena);

                foreach ($result as $fila){
                	echo"<h3 name='intervencion_bovis' class='tipo_experiencia_intervencion_bovis'>Intervención Bovis (".$fila['numero'].")</h3>";
  					echo"<div style='max-height: 350px; min-height: 350px;' class='tipo_experiencia_intervencion_bovis'>";  
  					echo"</div>";
                }
                
                //////Tipos de experiencias
                $cadena= "select e.plugin_comproveedores_experiencestypes_id as id, t.descripcion, count(*) as numero
                            from glpi_plugin_comproveedores_experiences   as e
                            LEFT join glpi_plugin_comproveedores_experiencestypes  as t on e.plugin_comproveedores_experiencestypes_id = t.id
                            where cv_id={$CvId} and e.plugin_comproveedores_experiencestypes_id!='0' and intervencion_bovis=0 
                            group by plugin_comproveedores_experiencestypes_id";
                        
                $result = $DB->query($cadena);
                       
                foreach ($result as $fila){

                    echo"<h3 name='".$fila['id']."' class='tipo_experiencia_".$fila['id']."' style='height:auto;'>".
                    $fila['descripcion']." (".$fila['numero'].")"
                    ."</h3>";

  					echo"<div style='max-height:350px;min-height:50px;background-color: rgb(244, 245, 245);' class='tipo_experiencia_".$fila['id']."'>";  
  					echo"</div>";
                }
                   
                //////Sin experiencias    
                $cadena= "select count(*) as numero from glpi_plugin_comproveedores_experiences where cv_id={$CvId} and intervencion_bovis=0 and plugin_comproveedores_experiencestypes_id=0 group by intervencion_bovis";

                $result = $DB->query($cadena);

                foreach ($result as $fila){

                	echo"<h3 name='sin_experiencia' class='tipo_experiencia_sin_experiencia'>Sin Experiencias (".$fila['numero'].")</h3>";
  					echo"<div style='max-height: 350px; min-height: 350px;' class='tipo_experiencia_sin_experiencia'>";  
  					echo"</div>";

					echo "</div>";
                }			
		}

		function consultaAjax(){

			GLOBAL $CFG_GLPI;

			$consulta="<script type='text/javascript'>


				$(document).ready(function() {

					//ocultamos el botón guardar modificación
					$('#guardar_modificacion').hide();

					//Ocultar y visualizar formulario experiencia
					$('#nuevaExperiencia').on('click',function(){
      					$('#formulario').toggle();
      					var atributo = $(this).attr('src');
      					if(atributo == '../pics/meta_plus.png')
      						$('#nuevaExperiencia').attr('src','../pics/meta_moins.png'); 
      					else
      						$('#nuevaExperiencia').attr('src','../pics/meta_plus.png'); 
   					});
	
					//Añadimos la función acordeon a las listas 
	   				$( '#accordion' ).accordion({collapsible:true, active: false});
	   				$( '.accordion_header .ui-accordion-header .ui-helper-reset .ui-state-default .ui-accordion-icons .ui-accordion-header-active .ui-state-active .ui-corner-top' ).css('background', '#1b2f62');
	   					
					//Añadimos onclick a las lista para que se cargen a elegirlas
					$('h3[class*=tipo_experiencia_]').click(function() {
  						actualizarLista($(this).attr('name'));	
					});
					
				});

				function limpiarFormulario(){

					$('#nombreExperiencia > textarea[name=name]').val('');
					$('[name=importe]').val('');
					$('[name=duracion]').val('');
					$('textarea[name=observaciones]').val('');
					$('textarea[name=cliente]').val('');

					$('input[name=plugin_comproveedores_experiencestypes_id]').val('0').change();
					$('input[name=plugin_comproveedores_communities_id]').val('0').change();
					$('#intervencionBovis').find('input').attr('checked', false);
					$('input[name=bim]').attr('checked', false);
					$('input[name=breeam]').attr('checked', false);
					$('input[name=leed]').attr('checked', false);
					$('input[name=otros_certificados]').attr('checked', false);
					$('select[name=anio]').val('2018').change();	
					$('select[name=estado]').val('1').change();

				}
		

				function añadirSinBorrar(){
						
					if($('#intervencionBovis').find('input').prop('checked')) {	
	   					intervencion_bovis=1;
					}else{	
						intervencion_bovis=0;
					}

					if($('input[name=bim]').prop('checked')) {	
	   					bim=1;
					}else{	
						bim=0;
					}

					if($('input[name=breeam]').prop('checked')) {		
	   					breeam=1;
					}else{	
						breeam=0;
					}

					if($('input[name=leed]').prop('checked')) {
	   					leed=1;
					}else{	
						leed=0;
					}

	   				if($('input[name=otros_certificados]').prop('checked')) {	
	   					otros_certificados=1;
					}else{
						otros_certificados=0;
					}

	   				/*if($('input[name=cpd_tier]').prop('checked')) {	
	   					cpd_tier=1;
					}else{
						cpd_tier=0;
					}*/
	   
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
	            	
	            	tabla_modificada='';

	           		if(parametros['intervencion_bovis']==1){
	            			

	            		actualizarLista('intervencion_bovis');
	            		tabla_modificada='intervencion_bovis';

	            	}
	            	if(parametros['intervencion_bovis']==0 && parametros['plugin_comproveedores_experiencestypes_id']=='0'){

	            		actualizarLista('sin_experiencia');
	            		tabla_modificada='sin_experiencia';

	            	}
	            	if(parametros['intervencion_bovis']==0 && parametros['plugin_comproveedores_experiencestypes_id']!='0'){

	            		actualizarLista(parametros['plugin_comproveedores_experiencestypes_id']);
	            		tabla_modificada=parametros['plugin_comproveedores_experiencestypes_id'];
	            		cabecera_tabla=$('h3[name='+tabla_modificada+']').text();
	            		alert(cabecera_tabla);
						
	            	}

	           		//Habilitamos el boton guardar modificación

	           		$('#guardar_modificacion').show();

	           		$('#guardar_modificacion').attr('onclick', 'guardarModificacion(tabla_modificada)');

				}

				function guardarModificacion(tabla_modificada){

					if($('#intervencionBovis').find('input').prop('checked')) {	
	   					intervencion_bovis=1;
					}else{	
						intervencion_bovis=0;
					}

					if($('input[name=bim]').prop('checked')) {	
	   					bim=1;
					}else{	
						bim=0;
					}

					if($('input[name=breeam]').prop('checked')) {		
	   					breeam=1;
					}else{	
						breeam=0;
					}

					if($('input[name=leed]').prop('checked')) {
	   					leed=1;
					}else{	
						leed=0;
					}

	   				if($('input[name=otros_certificados]').prop('checked')) {	
	   					otros_certificados=1;
					}else{
						otros_certificados=0;
					}

	   				/*if($('input[name=cpd_tier]').prop('checked')) {	
	   					cpd_tier=1;
					}else{
						cpd_tier=0;
					}*/
	   
	   				$('select[name=anio] option:selected').each(function() {
	      				anio=$( this ).text();
	      				anio=anio+'-00-00 00:00';
	   				});
	   				$('select[name=estado] option:selected').each(function() {
	      				estado=$( this ).val();
	   				});

	   					
	                var parametros = {
						'update':'GUARDAR MODIFICACION',
						'cv_id' : $('input[name=cv_id]').val(),
						'id'	: $('input[name=idExperiencia]').val(),
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

            		////////Actualizar Lista expeciencias, la tabla en que se ha creador y la tabla en la que estaba
            		
	            	
	            	//Actualizar para quitar la experiencia de la tabla, en el caso de que cambie de tabla
	            	actualizarLista(tabla_modificada);

	            	//Actualizar para visualizar la experiencia, en el caso de que cambie de tabla
	            	if(parametros['intervencion_bovis']==1){
	            			
	            		actualizarLista('intervencion_bovis');

	            	}
	            	if(parametros['intervencion_bovis']==0 && parametros['plugin_comproveedores_experiencestypes_id']=='0'){
	            			
	            		actualizarLista('sin_experiencia');

	            	}
	            	if(parametros['intervencion_bovis']==0 && parametros['plugin_comproveedores_experiencestypes_id']!='0'){
	            		
	            		actualizarLista(parametros['plugin_comproveedores_experiencestypes_id']);

	            	}
						
				}

				function modificar(idExperiencia){

					//visualizar formulario
					if($('#formulario').attr('style','display: none;')){
						$('#nuevaExperiencia').click();
						$('#nuevaExperiencia').attr('src','../pics/meta_moins.png'); 
					}
					
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

				function actualizarLista(tipo){

					nombre_tabla='tipo_experiencia_'+tipo;

					$.ajax({ 
						async: false, 
	           			type: 'GET',
	           			data: {'cv_id': $('input[name=cv_id]').val(), 'tipo': tipo },                  
	           			url:'".$CFG_GLPI["root_doc"]."/plugins/comproveedores/inc/listExperience.php',                    
	           			success:function(data){
	           				$('div[class*='+nombre_tabla+']').html(data);
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