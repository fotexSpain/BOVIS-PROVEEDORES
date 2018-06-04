<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

$where='';

$query ="SELECT 
if(paquetes.projecttasks_id=0
    and 
 (select (Select count(*) as numero
from glpi_projecttasks as subpaquetes1 
LEFT JOIN  glpi_projecttaskteams as paquetes_proveedor 
	on subpaquetes1.id=paquetes_proveedor.projecttasks_id
where paquetes1.id=subpaquetes1.projecttasks_id 
and paquetes_proveedor.items_id=proveedores.id
) as 'numero' from glpi_projecttasks as paquetes1 where paquetes1.id=paquetes.id)!=0, '0', '1') as 'visualizar',

proveedores.id as 'proveedor_id',

proveedores.name as 'proveedor_nombre',

proveedores.cif as 'cif',

proveedores.cv_id as 'cv',

proyectos.name as 'proyecto_nombre',

IF (paquetes.projecttasks_id <> '0', 
        (Select subpaquetes.name from glpi_projecttasks as subpaquetes where subpaquetes.id=paquetes.projecttasks_id) , paquetes.name ) as 'paquete_nombre',

IF (paquetes.projecttasks_id <> '0', paquetes.name, '' ) as 'subpaquete_nombre'

FROM glpi_suppliers as proveedores 

LEFT JOIN  glpi_projecttaskteams as paquetes_proveedor 
	on proveedores.id=paquetes_proveedor.items_id
    
LEFT JOIN glpi_projecttasks as paquetes
	on paquetes_proveedor.projecttasks_id=paquetes.id
    
LEFT JOIN  glpi_projects as proyectos 
	on proyectos.id=paquetes.projects_id";

//comprobamos que se a enviado algún filtro de busqueda
foreach ($_GET as $value) {
        if($value!='' && $value!='Siguiente'){
            $where=" where ";
        }   
}

 //Añadimos los filtros al where de la consulta
if($_GET['nombre_proveedor']!=''){
     $where=$where."UPPER(proveedores.name) LIKE UPPER('%".$_GET['nombre_proveedor']."%') and ";
 }
if($_GET['cif']!=''){
     $where=$where."proveedores.cif='".$_GET['cif']."' and ";
 }
 if($_GET['nombre_proyecto']!=''){
        $where=$where."proyectos.name='".$_GET['nombre_proyecto']."' and ";
 }
 if($_GET['codigo_proyecto']!=''){
      $where=$where."proyectos.code='".$_GET['codigo_proyecto']."' and ";
 }

 //eliminamos el ultimo and y ordenamos por proveedor
$posicion= strpos($where, ' and');
$where = substr($where, 0, $posicion);
$where=$where." order by proveedores.id desc";

//añadimos el where a la consulta
 $query=$query.$where;

$result = $DB->query($query);

            echo "<div align='center'><table class='tab_cadre_fixehov'>";
	echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Lista de proveedores</th></tr>";
	echo"<br/>";
	echo "<tr>";
                            echo "<th>".__('Nombre')."</th>";
                            echo "<th>".__('CIF')."</th>";
                            echo "<th>".__('Proyecto')."</th>";
                            echo "<th>".__('Paquete')."</th>";
                            echo "<th>".__('SubPaquete')."</th>";
                            echo "<th>".__('CV')."</th>";
                   echo "</tr>";

	while ($data=$DB->fetch_array($result)) {
                            if($data['visualizar']!=0){
                                 echo "<tr class='tab_bg_2'>";
                                     echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$data["proveedor_id"]."'>".$data["proveedor_nombre"]."</a></td>";               
		echo "<td class='center'>".$data['cif']."</td>";
		echo "<td class='center'>".$data['proyecto_nombre']."</td>";
                                     echo "<td class='center'>".$data['paquete_nombre']."</td>";
                                      echo "<td class='center'>".$data['subpaquete_nombre']."</td>";
                                      if($data['cv']=='1'){
                                           echo "<td class='center'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                      }
                                      else{
                                           echo "<td class='center'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                      }
                                    
                                echo "</tr>";
                            }   
	}
	echo"<br/>";
	echo "</table></div>";
	echo"<br>";