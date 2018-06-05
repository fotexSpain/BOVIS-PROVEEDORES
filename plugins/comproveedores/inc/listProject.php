<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

$where='';

$query ="select 
proyectos.id,
proyectos.name,
proyectos.code,
proyectos.projectstates_id,
(select count(*) from glpi_projecttasks where projects_id=proyectos.id) as numero_paquetes,

(select count(valoracion1.id) as numero 

from glpi_projecttasks as paquetes1 

left join glpi_plugin_comproveedores_valuations as valoracion1 
on valoracion1.projecttasks_id=paquetes1.id

where paquetes1.projects_id=proyectos.id) as numero_evaluaciones,

(select items_id from glpi_projectteams where projects_id=proyectos.id) as usuario_cargo_proyecto 

from glpi_projects  as proyectos";

//comprobamos que se a enviado algún filtro de busqueda
if(!empty($_GET['criteria'])){
    
    foreach ($_GET['criteria'] as $value) {
        if($value!='' && $value!='Buscar'){
            $where=" where ";
        }   
    }
    //Añadimos los filtros al where de la consulta

    //nombre del proyectos
    if(!empty($_GET['criteria'][0]['value'])){
         $where=$where."UPPER(proyectos.name) LIKE UPPER('%".$_GET['criteria'][0]['value']."%') and ";
     }

     //código del proyecto
     if(!empty($_GET['criteria'][1]['value'])){
          $where=$where."proyectos.code='".$_GET['criteria'][1]['value']."' and ";
     }

     //eliminamos el ultimo and y ordenamos por proveedor
    $posicion= strripos($where, ' and ');
    $where = substr($where, 0, $posicion);
}

$where=$where." order by proyectos.id desc";

//añadimos el where a la consulta
 $query=$query.$where;

$result = $DB->query($query);

            echo "<div align='center'><table class='tab_cadre_fixehov'>";
	echo "<tr class='tab_bg_2 tab_cadre_fixehov nohover'><th colspan='14'>Lista de proyectos</th></tr>";
	echo"<br/>";
	echo "<tr>";
                            echo "<th></th>";
                            echo "<th>".__('Nombre')."</th>";
                            echo "<th>".__('Código de proyecto')."</th>";
                            echo "<th>".__('Estado')."</th>";
                            echo "<th>".__('Nº Contratos')."</th>";
                            echo "<th>".__('Nº Evaluaciones')."</th>";
                            echo "<th>".__('Usuario')."</th>";
                   echo "</tr>";

	while ($data=$DB->fetch_array($result)) {
                            
                                
                                echo "<tr class='tab_bg_2'>";
                                echo "<td class='center'><input type='checkbox' /></td>";
                                echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/front/project.form.php?id=".$data["id"]."'>".$data["name"]."</a></td>";               
		echo "<td class='center'>".$data['code']."</td>";
		echo "<td class='center'>".Dropdown::getDropdownName("glpi_projectstates",$data['projectstates_id'])."</td>";
                                     echo "<td class='center'>".$data['numero_paquetes']."</td>";
                                      echo "<td class='center'>".$data['numero_evaluaciones']."</td>";
                                      echo "<td class='center'>".Dropdown::getDropdownName("glpi_users",$data['usuario_cargo_proyecto'])."</td>";
                                                                          
                                echo "</tr>";
                             
	}
	echo"<br/>";
	echo "</table></div>";
	echo"<br>";