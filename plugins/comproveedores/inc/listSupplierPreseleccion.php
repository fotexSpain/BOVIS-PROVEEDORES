<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

$where='';

/////////////////////////////////SelectionsupplierF1
//Comprobamos que se ha enviado algún filtro de busqueda
$filtrar=false;

foreach ($_GET as $value) {
         if($value!='' && $value!='Siguiente'){
                $filtrar=true;
         }
}
//Si hay algún filtro y viene de la página selectionsupplierF1 que ponga el where en la consulta
 if($filtrar){
        $where=$where." where ";
}  
///////////////////////////////////

 //Añadimos los filtros al where de la consulta
if($_GET['nombre_proveedor']!=''){
     $where=$where."UPPER(proveedor.name) LIKE UPPER('%".$_GET['nombre_proveedor']."%') and ";
 }
if($_GET['cif']!=''){
     $where=$where."proveedor.cif='".$_GET['nombre_proveedor']." and ";
 }
 if($_GET['nombre_proyecto']!=''){
     $where=$where."UPPER(proyectos.name) LIKE UPPER('%".$_GET['nombre_proyecto']."%') and ";
 }
  if($_GET['codigo_proyecto']!=''){
     $where=$where."proyectos.code='".$_GET['codigo_proyecto']." and ";
 }
 //Eliminamos el ultimo and y ordenamos por proveedor,
 //en el caso de que venga de la página selectionsupplierF1, sino estamos es que estamos en la preselección y no hay and en el where

$posicion= strripos($where, ' and');
$where = substr($where, 0, $posicion);
 
$where=$where." group by preseleccion.id order by contratos.name";

//Creamos la consulta y añadimos el where a la consulta
$query ="select 
proveedor.id as supplier_id,
proyectos.name as nombre_proyecto,
proyectos.code as codigo_proyecto,
contratos.name as nombre_contrato,
proveedor.name, 
GROUP_CONCAT(distinct especialidad.name SEPARATOR '\n')  as especialidad, 
facturacion.facturacion, 
proveedor.cv_id,
contratos.tipo_especialidad,

(select ROUND(Sum(evaluaciones.calidad)/count(evaluaciones.calidad),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.calidad!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as calidad, 

(select ROUND(Sum(evaluaciones.planificacion)/count(evaluaciones.planificacion),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.planificacion!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as planificacion, 

(select ROUND(Sum(evaluaciones.costes)/count(evaluaciones.costes),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.costes!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as costes, 

(select ROUND(Sum(evaluaciones.cultura_empresarial)/count(evaluaciones.cultura_empresarial),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.cultura_empresarial!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as cultura_empresarial, 

(select ROUND(Sum(evaluaciones.gestion_de_suministros_y_subcontratistas)/count(evaluaciones.gestion_de_suministros_y_subcontratistas),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.gestion_de_suministros_y_subcontratistas!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as gestion_de_suministros_y_subcontratistas, 

(select ROUND(Sum(evaluaciones.seguridad_y_salud_y_medioambiente)/count(evaluaciones.seguridad_y_salud_y_medioambiente),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.seguridad_y_salud_y_medioambiente!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as seguridad_y_salud_y_medioambiente, 

(select ROUND(Sum(evaluaciones.bim)/count(evaluaciones.bim),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.bim!=0 and projecttaskteams.items_id=preseleccion.suppliers_id and contratos.tipo_especialidad=2)  as bim_contratista, 

(select ROUND(Sum(evaluaciones.bim)/count(evaluaciones.bim),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.bim!=0 and projecttaskteams.items_id=preseleccion.suppliers_id and contratos.tipo_especialidad=1)  as bim_servicio, 

(select ROUND(Sum(evaluaciones.certificacion_medioambiental)/count(evaluaciones.certificacion_medioambiental),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.certificacion_medioambiental!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as certificacion_medioambiental, 

(select ROUND(Sum(evaluaciones.proyecto_basico)/count(evaluaciones.proyecto_basico),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.proyecto_basico!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as proyecto_basico, 

(select ROUND(Sum(evaluaciones.proyecto_de_ejecucion)/count(evaluaciones.proyecto_de_ejecucion),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.proyecto_de_ejecucion!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as proyecto_de_ejecucion, 

(select ROUND(Sum(evaluaciones.capacidad_de_la_empresa)/count(evaluaciones.capacidad_de_la_empresa),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.capacidad_de_la_empresa!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as capacidad_de_la_empresa, 

(select ROUND(Sum(evaluaciones.colaboradores)/count(evaluaciones.colaboradores),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.colaboradores!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as colaboradores, 

(select ROUND(Sum(evaluaciones.capacidad)/count(evaluaciones.capacidad),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.capacidad!=0 and projecttaskteams.items_id=preseleccion.suppliers_id )  as capacidad, 

(select ROUND(Sum(evaluaciones.actitud)/count(evaluaciones.actitud),2)
from glpi_plugin_comproveedores_valuations as evaluaciones 
INNER JOIN glpi_projecttasks as contratos on contratos.id=evaluaciones.projecttasks_id
INNER JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.projecttasks_id=contratos.id
where evaluaciones.actitud!=0 and projecttaskteams.items_id=preseleccion.suppliers_id)  as actitud

FROM glpi_plugin_comproveedores_preselections as preseleccion
LEFT JOIN glpi_projecttasks as contratos on contratos.id=preseleccion.projecttasks_id
LEFT JOIN glpi_projects as proyectos on proyectos.id=contratos.projects_id
LEFT JOIN glpi_suppliers as proveedor on proveedor.id=preseleccion.suppliers_id
LEFT JOIN glpi_plugin_comproveedores_valuations as evaluaciones on evaluaciones.projecttasks_id=contratos.id 
LEFT JOIN glpi_plugin_comproveedores_listspecialties as lista_especialidades on lista_especialidades.cv_id=proveedor.cv_id 
LEFT JOIN glpi_plugin_comproveedores_specialties as especialidad on especialidad.id=lista_especialidades.plugin_comproveedores_specialties_id
LEFT JOIN glpi_plugin_comproveedores_annualbillings as facturacion on facturacion.cv_id=proveedor.cv_id and YEAR(facturacion.anio)=YEAR(now()) 
 ".$where;


$result = $DB->query($query);

           echo"<div id='tabla_seleccion_proveedores' align='center'><table class='tab_cadre_fixehov'>";
              
	echo"<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";
        
                echo"<th class='center' style=' border: 1px solid #BDBDDB;' colspan='16'>Lista de proveedores preseleccionados</th></tr>";
                 //Nos creamos 2 array, uno para la tabla Servicios profesionales y otro para Contratistas
                $arrayServicioProfesionales=[];
                $arrayContratistas=[];
                 while ($data=$DB->fetch_array($result)) {

                        if($data['tipo_especialidad']==1){
                                $arrayServiciosProfesionales[]=$data;
                        }
                        if($data['tipo_especialidad']==2){
                                $arrayContratistas[]=$data;
                        }
                }
                 echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='15' >Preselecciones Contratistas</th></tr>";
                                echo"<tr>";
                                
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Nombre')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Contrato')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Proyecto')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Código proyecto')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Especialidad')."</th>";
                                echo"<th class='center' style='border: 1px solid #BDBDDB;'>".__('CV')."</th>";
                                echo"<th class='center' style='border: 1px solid #BDBDDB;'>".__('Facturación')."</th>";

                                echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('Q')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PLZ')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('COST')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('CULT')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('SUBC')."</th>";
                                echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('SyS')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('CERT')."</th>";
                                
                                echo"</tr>";
            
                           
                            
                foreach ($arrayContratistas as $contratista) {
                               
                                       echo"<tr class='tab_bg_2'>";
                                       echo"<td class='center' style=' border: 1px solid #BDBDDB;'><a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$contratista["supplier_id"]."'>".$contratista["name"]."</a></td>";   
                                       if(!empty($contratista['nombre_contrato'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$contratista['nombre_contrato']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                         if(!empty($contratista['nombre_proyecto'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$contratista['nombre_proyecto']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                         if(!empty($contratista['codigo_proyecto'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$contratista['codigo_proyecto']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                        if(!empty($contratista['especialidad'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$contratista['especialidad']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                       if(!empty($contratista['cv_id'])){
                                           echo"<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                       }
                                       else{
                                           echo"<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                       }

                                       $facturacion=substr(number_format($contratista['facturacion'], 0, '', '.'),0,strlen(number_format($contratista['facturacion'], 0, '', '.')));

                                      echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$facturacion."</td>";

                                                if(!empty($contratista['calidad'])){
                                                   echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['calidad']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($contratista['planificacion'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['planificacion']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['planificacion']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($contratista['costes'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['costes']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($contratista['cultura_empresarial'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['cultura_empresarial']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($contratista['gestion_de_suministros_y_subcontratistas'])){
                                                     echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['gestion_de_suministros_y_subcontratistas']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($contratista['seguridad_y_salud_y_medioambiente'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['seguridad_y_salud_y_medioambiente']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($contratista['bim_contratista'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['bim_contratista']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['bim_contratista']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($contratista['certificacion_medioambiental'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($contratista['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center;'>".$contratista['certificacion_medioambiental']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                
                           echo"</tr>";
                        
	}
        
                echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14' >Preselecciones Servicios Profesionales</th></tr>";

                                echo"<tr>";
                                
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Nombre')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Contrato')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Proyecto')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Código proyecto')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Especialidad')."</th>";
                                echo"<th class='center' style='border: 1px solid #BDBDDB;'>".__('CV')."</th>";
                                echo"<th class='center' style='border: 1px solid #BDBDDB;'>".__('Facturación')."</th>";

                                echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PROY BÁSICO')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('COLABORADOR')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('CAPACIDAD')."</th>";
                                echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('ACTITUD')."</th>";
                                echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                

                                echo"</tr>";
        
                foreach ($arrayServiciosProfesionales as $serviciosProfesionales) {
                                
            
                            echo"<tr class='tab_bg_2'>";
                                    
                        
                                       echo"<td class='center' style=' border: 1px solid #BDBDDB;'><a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$serviciosProfesionales["supplier_id"]."'>".$serviciosProfesionales["name"]."</a></td>";   
                                       if(!empty($serviciosProfesionales['nombre_contrato'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$serviciosProfesionales['nombre_contrato']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                         if(!empty($serviciosProfesionales['nombre_proyecto'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$serviciosProfesionales['nombre_proyecto']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                         if(!empty($serviciosProfesionales['codigo_proyecto'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$serviciosProfesionales['codigo_proyecto']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                        if(!empty($serviciosProfesionales['especialidad'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$serviciosProfesionales['especialidad']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                       if(!empty($serviciosProfesionales['cv_id'])){
                                           echo"<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                       }
                                       else{
                                           echo"<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                       }

                                       $facturacion=substr(number_format($serviciosProfesionales['facturacion'], 0, '', '.'),0,strlen(number_format($serviciosProfesionales['facturacion'], 0, '', '.')));

                                      echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$facturacion."</td>";

                                       
                                                 if(!empty($serviciosProfesionales['proyecto_basico'])){
                                                   echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['proyecto_basico']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($serviciosProfesionales['proyecto_de_ejecucion'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['proyecto_de_ejecucion']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($serviciosProfesionales['capacidad_de_la_empresa'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['capacidad_de_la_empresa']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($serviciosProfesionales['colaboradores'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['colaboradores']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['colaboradores']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($serviciosProfesionales['capacidad'])){
                                                     echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['capacidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['capacidad']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($serviciosProfesionales['actitud'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['actitud']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['actitud']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($serviciosProfesionales['bim_servicio'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($serviciosProfesionales['bim_servicio']).".png); background-repeat: no-repeat;  background-position: center;'>".$serviciosProfesionales['bim_servicio']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                        

                           echo"</tr>";
                        
	}
	
	echo"</table></div>";

                
       /* $nombre_pdf="Lista de proveedores seleccionados.pdf";
        //exportamos el contrnido de la variable $html a pdf, y el pdf tendra el nombre de $nombre_pdf
         include ("../../../dompdf/output.php");*/
        //echo $html;
        
        
        
        function getColorValoracion($valor){
	           
            switch ($valor) {
                case $valor<=1:

                        $color=1;
                    break;
                case $valor<=2 && $valor>1:

                        $color=2;
                   break;
                case $valor<=3 && $valor>2:

                        $color=3;
                    break;
                case $valor<=4 && $valor>3:

                        $color=4;
                    break;
                case $valor<=5 && $valor>4:

                        $color=5;
                    break;
                default:
                    break;
            }

	return $color;
        }