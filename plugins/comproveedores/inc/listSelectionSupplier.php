<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

$where='';
$where2='';

//comprobamos que se a enviado algún filtro de busqueda
foreach ($_GET as $value) {
        if($value!='' && $value!='Siguiente'){
            $where=" where ";
        }   
}

 //Añadimos los filtros al where de la consulta
if($_GET['nombre_proveedor']!=''){
     $where=$where."UPPER(proveedor.name) LIKE UPPER('%".$_GET['nombre_proveedor']."%') and ";
 }
if(isset($_GET['especialidad_id'])){
     $where=$where."especialidad.id='".$_GET['especialidad_id']."' and ";
 }
 /*if($_GET['nombre_proyecto']!=''){
        $where=$where."proyectos.name='".$_GET['nombre_proyecto']."' and ";
 }
 if($_GET['codigo_proyecto']!=''){
      $where=$where."proyectos.code='".$_GET['codigo_proyecto']."' and ";
 }*/

 //eliminamos el ultimo and y ordenamos por proveedor
$posicion= strripos($where, ' and');
$where = substr($where, 0, $posicion);
//$where=$where." order by proveedor.id desc";

//añadimos el where a la consulta

$query ="select 
proveedor.id as supplier_id,
proveedor.name, 
GROUP_CONCAT(distinct especialidad.name SEPARATOR '\n')  as especialidad, 
facturacion.facturacion, 
1 as existe_cv_id,
ROUND(Sum(valoracion.calidad)/count(valoracion.calidad)) as calidad, 
ROUND(Sum(valoracion.plazo)/count(valoracion.plazo)) as plazo,
ROUND(Sum(valoracion.costes)/count(valoracion.costes)) as costes, 
ROUND(Sum(valoracion.cultura)/count(valoracion.cultura)) as cultura, 
ROUND(Sum(valoracion.suministros_y_subcontratistas)/count(valoracion.suministros_y_subcontratistas)) as suministros_y_subcontratistas, 
ROUND(Sum(valoracion.sys_y_medioambiente)/count(valoracion.sys_y_medioambiente)) as sys_y_medioambiente
from glpi_suppliers as proveedor 
LEFT JOIN glpi_plugin_comproveedores_annualbillings as facturacion
on facturacion.cv_id=proveedor.cv_id and YEAR(facturacion.anio)=YEAR(now())
LEFT JOIN glpi_plugin_comproveedores_valuations as valoracion
on valoracion.cv_id=proveedor.cv_id
LEFT JOIN glpi_plugin_comproveedores_listspecialties as lista_especialidades 
on lista_especialidades.cv_id=proveedor.cv_id
LEFT JOIN glpi_plugin_comproveedores_specialties as especialidad 
on especialidad.id=lista_especialidades.plugin_comproveedores_specialties_id ".$where;

if(!isset($_GET['esperiencia_id']) && 
        empty($_GET['facturacion_year_1']) && 
        empty($_GET['facturacion_year_2']) && 
        empty($_GET['facturacion_year_3'])){
    
    if($_GET['nombre_proveedor']!=''){
        $where2="and UPPER(proveedor.name) LIKE UPPER('%".$_GET['nombre_proveedor']."%')";
    }
    
    $query =$query." Union 
    select 
    proveedor.id as supplier_id,
    proveedor.name, 
    null as especialidad,
    null as facturacion,
    0 as existe_cv_id,
    null as calidad, 
    null as plazo,
    null as costes, 
    null as cultura, 
    null as suministros_y_subcontratistas, 
    null as sys_y_medioambiente
    from glpi_suppliers as proveedor 
    where cv_id is null ".$where2;
    
    
}

echo $query;

$result = $DB->query($query);

            echo "<div align='center'><table class='tab_cadre_fixehov'>";
              
	echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";
                echo "<th style='background-color: white; border-bottom:0px;'></th>";
                echo"<th class='center' style=' border: 1px solid #BDBDDB;' colspan='14'>Lista de proveedores</th></tr>";
	echo"<br/>";
	echo "<tr>";
                            echo "<th  style='background-color: white'>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Nombre')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Especialidad')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('CV')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Facturación')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Calidad')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Plazo')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Costes')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Cultura')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Suministros y Subcontratistas')."</th>";
                            echo "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('SyS y Medioambiente')."</th>";
                                 
                   echo "</tr>";

	while ($data=$DB->fetch_array($result)) {
                            
                        if($data["name"]!=NULL){
                                                   
                            echo "<tr class='tab_bg_2'>";
                             echo "<td class='center' style=' border: 1px solid #BDBDDB;'><input type='checkbox'/></td>";
                                    echo "<td class='center' style=' border: 1px solid #BDBDDB;'><a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$data["supplier_id"]."'>".$data["name"]."</a></td>";   
                                    if(!empty($data['calidad'])){
                                          echo "<td class='center' style=' border: 1px solid #BDBDDB;'>".$data['especialidad']."</td>";
                                    }else{
                                          echo "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                    if(!empty($data['existe_cv_id'])){
                                            echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                    }
                                    else{
                                            echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                    }
                                    echo "<td class='center' style=' border: 1px solid #BDBDDB;'>".$data['facturacion']."</td>";
                                    if(!empty($data['calidad'])){
                                        echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img style='vertical-align:middle;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_".$data['calidad'].".png></td>";
                                    }
                                    else{
                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['plazo'])){
                                        echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img style='vertical-align:middle;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_".$data['plazo'].".png></td>";
                                    }
                                    else{
                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['costes'])){
                                        echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img style='vertical-align:middle;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_".$data['costes'].".png></td>";
                                    }
                                    else{
                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['cultura'])){
                                        echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img style='vertical-align:middle;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_".$data['cultura'].".png></td>";
                                    }
                                    else{
                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['suministros_y_subcontratistas'])){
                                        echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img style='vertical-align:middle;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_".$data['suministros_y_subcontratistas'].".png></td>";
                                    }
                                    else{
                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                    if(!empty($data['sys_y_medioambiente'])){
                                        echo "<td class='center' style=' border: 1px solid #BDBDDB;'><img style='vertical-align:middle;' src=".$CFG_GLPI["root_doc"]."/pics/valoracion_".$data['sys_y_medioambiente'].".png></td>";
                                    }
                                    else{
                                        echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }

                           echo "</tr>";
                        }
	}
	echo"<br/>";
	echo "</table></div></br>";
                echo "<div><span  style='font-weight: bold; size:20px;'>*</span>Si un proveedor no contiene CV, no tendra ni valoraciones, ni especialidad, ni facturación.</div>";
	echo"<br>";