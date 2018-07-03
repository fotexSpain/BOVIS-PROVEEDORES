<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

$where='';

//obtenemos el id del contrato, si es en visualizar preselección o si es en elegir proveedor
$contrato_id='';
if(!empty($preseleccion)){
    $contrato_id=$_GET['id'];
}else{
    $contrato_id=$_GET['paquete_id'];
}

////////////////////////////////Preselección
//where para la Preselección
if(!empty($preseleccion)){
     $where=" where  proveedor.id in(".$preseleccion.")";
}
/////////////////////////////////

/////////////////////////////////SelectionsupplierF1
//Comprobamos que se ha enviado algún filtro de busqueda
$filtrar=false;

foreach ($_GET as $value) {
         if($value!=''){
                $filtrar=true;
         }
}
//Si hay algún filtro y viene de la página selectionsupplierF1 que ponga el where en la consulta
 if($filtrar && $_GET['PrimerFiltro']){
        $where=$where." where ";
}  
///////////////////////////////////

 //Añadimos los filtros al where de la consulta
if($_GET['nombre_proveedor']!=''){
     $where=$where."UPPER(proveedor.name) LIKE UPPER('%".$_GET['nombre_proveedor']."%') and ";
 }
if(isset($_GET['especialidad_id'])){
     $where=$where."especialidad.id='".$_GET['especialidad_id']."' and ";
 }
        //Facturación año actual
 if($_GET['facturacion_year_1']!=''){
     
        //quitamos los posible puntos y añadimos los 3 ceros (x1000) 
        $_GET['facturacion_year_1']=str_replace('.', '', $_GET['facturacion_year_1']);
        $_GET['facturacion_year_1']=$_GET['facturacion_year_1'].'000';
        
        $where=$where." ( select facturacion1.facturacion 
        from  glpi_plugin_comproveedores_annualbillings as facturacion1 
        left join glpi_suppliers as proveedor1
        on proveedor1.cv_id=facturacion1.cv_id 
        where YEAR(anio)=YEAR(now()) and proveedor1.id=proveedor.id )=".$_GET['facturacion_year_1']." and ";
 }
        //Facturación año actual-1
 if($_GET['facturacion_year_2']!=''){
     
        //quitamos los posible puntos y añadimos los 3 ceros (x1000) 
      $_GET['facturacion_year_2']=str_replace('.', '', $_GET['facturacion_year_2']);
      $_GET['facturacion_year_2']=$_GET['facturacion_year_2'].'000';
     
      $where=$where." ( select facturacion1.facturacion 
        from  glpi_plugin_comproveedores_annualbillings as facturacion1 
        left join glpi_suppliers as proveedor1
        on proveedor1.cv_id=facturacion1.cv_id 
        where YEAR(anio)=YEAR(now())-1 and proveedor1.id=proveedor.id )=".$_GET['facturacion_year_2']." and ";
 }
        //Facturación año actual-2
 if($_GET['facturacion_year_3']!=''){
     
       //quitamos los posible puntos y añadimos los 3 ceros (x1000) 
      $_GET['facturacion_year_3']=str_replace('.', '', $_GET['facturacion_year_3']);
      $_GET['facturacion_year_3']=$_GET['facturacion_year_3'].'000';
     
      $where=$where." ( select facturacion1.facturacion 
        from  glpi_plugin_comproveedores_annualbillings as facturacion1 
        left join glpi_suppliers as proveedor1
        on proveedor1.cv_id=facturacion1.cv_id 
        where YEAR(anio)=YEAR(now())-2 and proveedor1.id=proveedor.id )=".$_GET['facturacion_year_3']." and ";
 }

 //Eliminamos el ultimo and y ordenamos por proveedor,
 //en el caso de que venga de la página selectionsupplierF1, sino estamos es que estamos en la preselección y no hay and en el where
 if($_GET['PrimerFiltro']){
     $posicion= strripos($where, ' and');
     $where = substr($where, 0, $posicion);
 }

$where=$where." group by proveedor.name order by proveedor.name desc";

//Creamos la consulta y añadimos el where a la consulta
$query ="select 
proveedor.id as supplier_id,
proveedor.name, 
GROUP_CONCAT(distinct especialidad.name SEPARATOR '\n')  as especialidad, 
facturacion.facturacion, 
proveedor.cv_id,
contrato.tipo_especialidad,
ROUND(Sum(valoracion.calidad)/count(valoracion.calidad),2) as calidad, 
ROUND(Sum(valoracion.planificacion)/count(valoracion.planificacion),2) as planificacion,
ROUND(Sum(valoracion.costes)/count(valoracion.costes),2) as costes, 
ROUND(Sum(valoracion.cultura_empresarial)/count(valoracion.cultura_empresarial),2) as cultura_empresarial, 
ROUND(Sum(valoracion.gestion_de_suministros_y_subcontratistas)/count(valoracion.gestion_de_suministros_y_subcontratistas),2) as gestion_de_suministros_y_subcontratistas, 
ROUND(Sum(valoracion.seguridad_y_salud_y_medioambiente)/count(valoracion.seguridad_y_salud_y_medioambiente),2) as seguridad_y_salud_y_medioambiente,
ROUND(Sum(valoracion.bim)/count(valoracion.bim),2) as bim,
ROUND(Sum(valoracion.certificacion_medioambiental)/count(valoracion.certificacion_medioambiental),2) as certificacion_medioambiental,
ROUND(Sum(valoracion.proyecto_basico)/count(valoracion.proyecto_basico),2) as proyecto_basico, 
ROUND(Sum(valoracion.proyecto_de_ejecucion)/count(valoracion.proyecto_de_ejecucion),2) as proyecto_de_ejecucion,
ROUND(Sum(valoracion.capacidad_de_la_empresa)/count(valoracion.capacidad_de_la_empresa),2) as capacidad_de_la_empresa, 
ROUND(Sum(valoracion.colaboradores)/count(valoracion.colaboradores),2) as colaboradores, 
ROUND(Sum(valoracion.capacidad)/count(valoracion.capacidad),2) as capacidad, 
ROUND(Sum(valoracion.actitud)/count(valoracion.actitud),2) as actitud

from glpi_suppliers as proveedor 
LEFT JOIN glpi_plugin_comproveedores_annualbillings as facturacion on facturacion.cv_id=proveedor.cv_id and YEAR(facturacion.anio)=YEAR(now()) 
 LEFT JOIN glpi_projecttaskteams as projecttaskteams on projecttaskteams.items_id=proveedor.id
 LEFT JOIN glpi_projecttasks as contrato on contrato.id=projecttaskteams.projecttasks_id and contrato.tipo_especialidad=(select tipo_especialidad from glpi_projecttasks contrato_principal where id=".$contrato_id.")
 LEFT JOIN glpi_plugin_comproveedores_valuations as valoracion on valoracion.cv_id=proveedor.cv_id and valoracion.projecttasks_id=contrato.id
 LEFT JOIN glpi_plugin_comproveedores_listspecialties as lista_especialidades on lista_especialidades.cv_id=proveedor.cv_id 
 LEFT JOIN glpi_plugin_comproveedores_specialties as especialidad on especialidad.id=lista_especialidades.plugin_comproveedores_specialties_id AND lista_especialidades.plugin_comproveedores_roltypes_id=(select tipo_especialidad from glpi_projecttasks contrato_principal where id=".$contrato_id.")
 ".$where;

$result = $DB->query($query);

           echo"<div id='tabla_seleccion_proveedores' align='center'><table class='tab_cadre_fixehov'>";
              
	echo"<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";
        
               //Eliminación al visualizar la preselección
                if(empty($preseleccion)){
                        echo"<th style='background-color: white; border-bottom:0px;'></th>";
                }
             
                if($_GET['PrimerFiltro']){
                        echo"<th class='center' style=' border: 1px solid #BDBDDB;' colspan='14'>Lista de proveedores</th></tr>";
                }
                if(!empty($preseleccion)){
                        echo"<th class='center' style=' border: 1px solid #BDBDDB;' colspan='14'>Lista de proveedores preseleccionados</th></tr>";
                }
	echo"<br/>";
        
                 $visualizar_cabecera=true;
                while ($data=$DB->fetch_array($result)) {
                    
                        if($visualizar_cabecera){
                                
                                //solo visualizamos 1 vez la cabecera
                                $visualizar_cabecera=false;
                                echo"<tr>";
                                
                                //Eliminación al visualizar la preselección
                                if(empty($preseleccion)){
                                        echo"<th  style='background-color: white'></th>";
                                }
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Nombre')."</th>";
                                echo"<th class='center' style='width: 150px; height: 40px; border: 1px solid #BDBDDB;'>".__('Especialidad')."</th>";
                                echo"<th class='center' style='border: 1px solid #BDBDDB;'>".__('CV')."</th>";
                                echo"<th class='center' style='border: 1px solid #BDBDDB;'>".__('Facturación')."</th>";

                                ////////Criterios Contratista///////
                                if($data["tipo_especialidad"]==2){
                                        echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('Q')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PLZ')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('COST')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('CULT')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('SUBC')."</th>";
                                        echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('SyS')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                        echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('CERT')."</th>";

                                ////////Criterios Servicios Profesionales ///////      
                                }else{
                                        echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PROY BÁSICO')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('PROY EJECUCIÓN')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('COLABORADOR')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('CAPACIDAD')."</th>";
                                        echo "<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('ACTITUD')."</th>";
                                        echo"<th class='center' style='width: 40px; height: 40px; border: 1px solid #BDBDDB;'>".__('BIM')."</th>";
                                }

                                echo"</tr>";
                        }
                                      
                        //Añadimos los id de los proveedores para la preselección
                        $preselecionIds=$preselecionIds.$data["supplier_id"]."-";
            
                            echo"<tr class='tab_bg_2'>";
                                    
                                        //Eliminación al visualizar la preselección
                                       if(empty($preseleccion)){
                                           echo"<td  class='center' style=' border: 1px solid #BDBDDB;'>";
                                                      echo"<input onclick='setListaProveedorfiltro(".$data["supplier_id"].")' id='proveedor_".$data["supplier_id"]."' name='proveedor_".$data["supplier_id"]."' type='checkbox'/>";
                                           echo"</td>";
                                       }

                                       echo"<td class='center' style=' border: 1px solid #BDBDDB;'><a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$data["supplier_id"]."'>".$data["name"]."</a></td>";   
                                       if(!empty($data['especialidad'])){
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$data['especialidad']."</td>";
                                       }else{
                                             echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                       }
                                       if(!empty($data['cv_id'])){
                                           echo"<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                       }
                                       else{
                                           echo"<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                       }

                                       $facturacion=substr(number_format($data['facturacion'], 0, '', '.'),0,strlen(number_format($data['facturacion'], 0, '', '.')));

                                      echo"<td class='center' style=' border: 1px solid #BDBDDB;'>".$facturacion."</td>";

                                       ////////Criterios Contratista///////
                                       if($data["tipo_especialidad"]==2){
                                                if(!empty($data['calidad'])){
                                                   echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['calidad']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['planificacion'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['planificacion']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['planificacion']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['costes'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['costes']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['cultura_empresarial'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['cultura_empresarial']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['cultura_empresarial']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['gestion_de_suministros_y_subcontratistas'])){
                                                     echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['gestion_de_suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['gestion_de_suministros_y_subcontratistas']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['seguridad_y_salud_y_medioambiente'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['seguridad_y_salud_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['seguridad_y_salud_y_medioambiente']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['bim'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['bim']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['certificacion_medioambiental'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['certificacion_medioambiental']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['certificacion_medioambiental']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                
                                        ///////Criterios Servicios Profesionales ///////          
                                        }else{
                                                 if(!empty($data['proyecto_basico'])){
                                                   echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['proyecto_basico']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['proyecto_basico']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['proyecto_de_ejecucion'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['proyecto_de_ejecucion']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['proyecto_de_ejecucion']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['capacidad_de_la_empresa'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['capacidad_de_la_empresa']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['capacidad_de_la_empresa']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['colaboradores'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['colaboradores']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['colaboradores']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['capacidad'])){
                                                     echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['capacidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['capacidad']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                if(!empty($data['actitud'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['actitud']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['actitud']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
                                                 if(!empty($data['bim'])){
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['bim']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['bim']."</td>";
                                                }
                                                else{
                                                    echo"<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                                }
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