<?php

/******************************************

	PLUGIN DE GESTION DE CURRICULUMS DE LOS PROVEEDORES


 ******************************************/

	class PluginComproveedoresIntegratedmanagementsystem extends CommonDBTM{

		static $rightname	= "plugin_comproveedores";

		static function getTypeName($nb=0){
			return _n('Sistema integrado de gestión','Sistema integrado de gestión',1,'comproveedores');
		}

		function getTabNameForItem(CommonGLPI $item, $tabnum=1,$withtemplate=0){
			if($item-> getType()=="Supplier"){
				return self::createTabEntry('Sistema integrado de gestión');
			}
			return 'Sistema integrado de gestión';
		}


		static function displayTabContentForItem(CommonGLPI $item,$tabnum=1,$withtemplate=0){

			global $CFG_GLPI;
			$self = new self();

			//Entrada Administrador
			if($item->getType()=='Supplier'){	

				if(isset($item->fields['cv_id'])){
			
					$self->showFormItemSIG($item, $withtemplate);

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

		function showFormItemSIG($item, $withtemplate='') {	
			GLOBAL $DB,$CFG_GLPI;
			
			/*///////////////////////////////
			//AÑADIR SEGURO AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['cv_id']; 

			//
			$query ="SELECT id FROM glpi_plugin_comproveedores_integratedmanagementsystems WHERE cv_id=".$CvId;

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
			$options['formtitle']    = "Sistema integrado de gestión(SIG)";
			$options['colspan']=5;

			$this->initForm($ID, $options);
			
			$this->showFormHeader($options);
				
			echo Html::hidden('cv_id', array('value' => $CvId));

			//Aseguramiento calidad

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";

			echo "<td colspan='3'>Aseguramiento de calidad</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td <td colspan='3' style='text-align: left'>¿Tiene la empresa un sistema o plan de gestión?
					<span style='color:#B40404'>(Indicar Acriditaciones Vigentes. Ejemplo:ISO 9001 o similar)</span></td>";
			echo "<td>";
			Dropdown::showYesNo('planGestion', $this->fields["planGestion"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsPlanGestion'>".$this->fields["obsPlanGestion"]."</textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td <td colspan='3' style='text-align: left'>". __('¿Posee procedimientos de control de documentos?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('controlDocumentos',$this->fields["controlDocumentos"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsControlDocumentos'>".$this->fields["obsControlDocumentos"]."</textarea>";
			echo "</td>";
			echo"</tr>";
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td <td colspan='3' style='text-align: left'>". __('¿Posee Política de calidad?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('politicaCalidad', $this->fields["politicaCalidad"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsPoliticaCalidad'>".$this->fields["obsPoliticaCalidad"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>". __('¿Realiza auditorias internas de calidad?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('auditoriasInternas', $this->fields["auditoriasInternas"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsAuditoriasInternas'>".$this->fields["obsAuditoriasInternas"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			//Sostenibilidad

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan='3'>Sostenibilidad</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>" . __('¿Tiene la empresa un plan de sostenibilidad?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('planSostenibilidad', $this->fields["planSostenibilidad"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsPlanSostenibilidad'>".$this->fields["obsPlanSostenibilidad"]."</textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Tiene acreditado un Sistema de Gestión Medioambiental?<span style='color:#B40404'>(Indicar Acriditaciones Vigentes. Ejemplo:ISO 14001 o similar)</span>'</td>";
			echo "<td>";
			Dropdown::showYesNo('SGMedioambiental', $this->fields["SGMedioambiental"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsSGMedioambiental'>".$this->fields["obsSGMedioambiental"]."</textarea>";
			echo "</td>";
			echo"</tr>";
			
			//Responsabilidad Social Corporativa(RSC)

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan='3'>Responsabilidad Social Corporativa(RSC)</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Realiza la Empresa Acciones en favor de la RSC?
					<span style='color:#B40404'>(Indicar las más destacadas)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('accionesRSC', $this->fields["accionesRSC"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsAccionesRSC'>".$this->fields["obsAccionesRSC"]."</textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Tiene implementada una politica de gestión de la RSC?'
					<span style='color:#B40404'>(Indicar qué política)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('gestionRSC', $this->fields["gestionRSC"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsGestionRSC'>".$this->fields["obsGestionRSC"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			//Seguridad y Salud

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan='3'>Seguridad y Salud</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Dispone de un sistema de gestión de la Seguridad y Salud tipo OSHAS 18001 o similar?'
					<span style='color:#B40404'>(Indicar sistema de gestión similar)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('SGSeguridadYSalud', $this->fields["SGSeguridadYSalud"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsSGSeguridadYSalud'>".$this->fields["obsSGSeguridadYSalud"]."</textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿La formación de los empleados está acreditada por un certificado de formación emitido por un organismo competente?'
					<span style='color:#B40404'>(Indicar el organismo acreditador)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('certificadoFormacion', $this->fields["certificadoFormacion"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsCertificadoFormacion'>".$this->fields["obsCertificadoFormacion"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Cuenta la empresa con un departamento especializado en la Gestión de Seguridad y Salud?'
				<span style='color:#B40404'>(Indicar Indicar número de empleados de dicho departamento)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('departamentoSeguridaYSalud', $this->fields["departamentoSeguridaYSalud"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsDepartamentoSeguridaYSalud'>".$this->fields["obsDepartamentoSeguridaYSalud"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>". __('¿Tiene implantada la empresa una metodología para medir, evaluar, auditar, inspeccionar, etc sus desempeño en Seguridad y Salud?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('metodologiaSeguridaYSalud', $this->fields["metodologiaSeguridaYSalud"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsMetodologiaSeguridaYSalud'>".$this->fields["obsMetodologiaSeguridaYSalud"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Proporciona la empresa formación especifica en Seguridad y Salud?'
					<span style='color:#B40404'>(Indicar número de horas de formación impartidas durante el último año)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('formacionSeguridaYSalud', $this->fields["formacionSeguridaYSalud"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsFormacionSeguridaYSalud'>".$this->fields["obsFormacionSeguridaYSalud"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>De la plantilla actual. ¿Cuántos empleados podrían ejercer como Recursos Preventivo en una obra?'
					<span style='color:#B40404'>(Indicar número de empleados fijos capacitados)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('empleadoRP', $this->fields["empleadoRP"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsEmpleadoRP'>".$this->fields["obsEmpleadoRP"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>¿Dispone la empresa de Asesoría técina-legal competente para la asesoramiento y/o asistencia materia de Seguridad y Salud?'
					<span style='color:#B40404'>(Indicar número de procesos judiciales o acciones legales relacionados con la Seguridad y Salud emprendidos contra la empresa en los últimos 5 años)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('empresaAsesoramiento', $this->fields["empresaAsesoramiento"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsEmpresaAsesoramiento'>".$this->fields["obsEmpresaAsesoramiento"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td colspan='3' style='text-align: left'>En la práctica habitual ¿existe un procedimiento de la empresa que garantice que sus Subcontratistas son competentes y están capacitados para el desempeño de su trabajo con seguridad? 
					<span style='color:#B40404'>(Caso de existir, indicar el número de Subcontratistas que ya habrían sido precalificados)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('procedimientoSubcontratistas', $this->fields["procedimientoSubcontratistas"]);
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsProcedimientoSubcontratistas'>".$this->fields["obsProcedimientoSubcontratistas"]."</textarea>";
			echo "</td>";
			echo"</tr>";

			//Consignar los siguientas índices de siniestralidad de los tres últimos años
			
			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan=5>Consignar los siguientas índices de siniestralidad de los tres últimos años</td>";
			echo "</tr>";
			echo"<tr class='tab_bg_1 center'>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td>Incidencia</td>";
				echo "<td>Frecuencia</td>";
				echo "<td>Gravedad</td>";
			echo"</tr>";

			for($i=0; $i<3; $i++){

				echo"<tr class='tab_bg_1 center'>";
				echo "<td>Año:</td>";
				echo "<td><input class='center' style='border:none;' type='text' name='anio".$i."' value='".(date("Y")-$i)."' readonly></td>";
				echo "<td>";
					Html::autocompletionTextField($this, "incidencia".$i);
				"</td>";
				echo "<td>";
					Html::autocompletionTextField($this, "frecuencia".$i);
				"</td>";
				echo "<td>";
					Html::autocompletionTextField($this, "gravedad".$i);
				"</td>";
				echo"</tr>";
			}

			echo"</tbody>";
			echo"</table>";
			echo"</div>";

			$this->showFormButtons($options);
			//echo"</form>";

			/*///////////////////////////////
			//LISTAR SEGUROS DEL PROVEEDOR
			///////////////////////////////*/

			/*$query2 ="SELECT * FROM glpi_plugin_comproveedores_insurances WHERE cv_id=$CvId" ;

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
				}*/
					
		}


		function showFormNoCV($ID, $options=[]) {
			//Aqui entra cuando no tien gestionado el curriculum

			echo "<div>Necesitas gestionar el CV antes de añadir seguros</div>";
			echo "<br>";
		}

		

		function showForm($ID, $options=[]) {
			//Aqui entra desde el inicio de los proveedores

			/*global $CFG_GLPI;

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

			$this->showFormButtons($options);*/
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
			
			//echo $this->consultaAjax();

			/*///////////////////////////////
			//AÑADIR SEGURO AL PROVEEDOR
			///////////////////////////////*/

			$CvId=$item->fields['id']; 

			echo"<form action=".$CFG_GLPI["root_doc"]."/plugins/comproveedores/front/integratedmanagementsystem.form.php method='post'>";		
			echo Html::hidden('cv_id', array('value' => $CvId));

			//Aseguramiento calidad

			echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken()));
			echo "<div class='center'>";
			echo"<table class='tab_cadre_fixe'><tbody>";
			echo"<tr class='headerRow'>";
			echo"<th colspan='4'>Sistema integrado de gestión(SIG)</th></tr>";

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td>Aseguramiento de calidad</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Tiene la empresa un sistema o plan de gestión?
					<span style='color:#B40404'>(Indicar Acriditaciones Vigentes. Ejemplo:ISO 9001 o similar)</span></td>";
			echo "<td>";
			Dropdown::showYesNo('planGestion');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsPlanGestion'></textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>". __('¿Posee procedimientos de control de documentos?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('controlDocumentos');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsControlDocumentos'></textarea>";
			echo "</td>";
			echo"</tr>";
			
			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>". __('¿Posee Política de calidad?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('politicaCalidad');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsPoliticaCalidad'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>". __('¿Realiza auditorias internas de calidad?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('auditoriasInternas');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsAuditoriasInternas'></textarea>";
			echo "</td>";
			echo"</tr>";

			//Sostenibilidad

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td>Sostenibilidad</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>" . __('¿Tiene la empresa un plan de sostenibilidad?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('planSostenibilidad');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsPlanSostenibilidad'></textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Tiene acreditado un Sistema de Gestión Medioambiental?<span style='color:#B40404'>(Indicar Acriditaciones Vigentes. Ejemplo:ISO 14001 o similar)</span>'</td>";
			echo "<td>";
			Dropdown::showYesNo('SGMedioambiental');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsSGMedioambiental'></textarea>";
			echo "</td>";
			echo"</tr>";
			
			//Responsabilidad Social Corporativa(RSC)

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td>Responsabilidad Social Corporativa(RSC)</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Realiza la Empresa Acciones en favor de la RSC?
					<span style='color:#B40404'>(Indicar las más destacadas)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('accionesRSC');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsAccionesRSC'></textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Tiene implementada una politica de gestión de la RSC?'
					<span style='color:#B40404'>(Indicar qué política)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('gestionRSC');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsGestionRSC'></textarea>";
			echo "</td>";
			echo"</tr>";

			//Seguridad y Salud

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td>Seguridad y Salud</td>";
			echo "<td>Sí/No</td>";
			echo "<td>Observaciones/Comentarios</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Dispone de un sistema de gestión de la Seguridad y Salud tipo OSHAS 18001 o similar?'
					<span style='color:#B40404'>(Indicar sistema de gestión similar)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('SGSeguridadYSalud');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsSGSeguridadYSalud'></textarea>";
			echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿La formación de los empleados está acreditada por un certificado de formación emitido por un organismo competente?'
					<span style='color:#B40404'>(Indicar el organismo acreditador)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('certificadoFormacion');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsCertificadoFormacion'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Cuenta la empresa con un departamento especializado en la Gestión de Seguridad y Salud?'
				<span style='color:#B40404'>(Indicar Indicar número de empleados de dicho departamento)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('departamentoSeguridaYSalud');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsDepartamentoSeguridaYSalud'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>". __('¿Tiene implantada la empresa una metodología para medir, evaluar, auditar, inspeccionar, etc sus desempeño en Seguridad y Salud?') . "</td>";
			echo "<td>";
			Dropdown::showYesNo('metodologiaSeguridaYSalud');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsMetodologiaSeguridaYSalud'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Proporciona la empresa formación especifica en Seguridad y Salud?'
					<span style='color:#B40404'>(Indicar número de horas de formación impartidas durante el último año)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('formacionSeguridaYSalud');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsFormacionSeguridaYSalud'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>De la plantilla actual. ¿Cuántos empleados podrían ejercer como Recursos Preventivo en una obra?'
					<span style='color:#B40404'>(Indicar número de empleados fijos capacitados)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('empleadoRP');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsEmpleadoRP'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>¿Dispone la empresa de Asesoría técina-legal competente para la asesoramiento y/o asistencia materia de Seguridad y Salud?'
					<span style='color:#B40404'>(Indicar número de procesos judiciales o acciones legales relacionados con la Seguridad y Salud emprendidos contra la empresa en los últimos 5 años)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('empresaAsesoramiento');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsEmpresaAsesoramiento'></textarea>";
			echo "</td>";
			echo"</tr>";

			echo"<tr class='tab_bg_1 center'>";
			echo "<td style='text-align: left'>En la práctica habitual ¿existe un procedimiento de la empresa que garantice que sus Subcontratistas son competentes y están capacitados para el desempeño de su trabajo con seguridad? 
					<span style='color:#B40404'>(Caso de existir, indicar el número de Subcontratistas que ya habrían sido precalificados)</span>
				</td>";
			echo "<td>";
			Dropdown::showYesNo('procedimientoSubcontratistas');
			echo "</td><td>";
			echo "<textarea cols='37' rows='3' name='obsProcedimientoSubcontratistas'></textarea>";
			echo "</td>";
			echo"</tr>";

			//Consignar los siguientas índices de siniestralidad de los tres últimos años

			echo"<tr class='tab_bg_1 center' style='font-weight: bold; font-size:12px;'>";
			echo "<td colspan=3>Consignar los siguientas índices de siniestralidad de los tres últimos años</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center' style='font-weight: bold;'>";
			echo"<td colspan=3><input type='submit' class='submit' name='add' value='AÑADIR' /></td>";
			echo"</tr>";
			echo"</tbody>";
			echo"</table>";
			echo"</div>";
			echo"</form>";

			/*///////////////////////////////
			//LISTAR SEGUROS DEL PROVEEDOR
			///////////////////////////////*/

			/*$query2 ="SELECT * FROM glpi_plugin_comproveedores_insurances WHERE cv_id=$CvId" ;

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
				}*/
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