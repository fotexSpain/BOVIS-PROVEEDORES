<?php

use Glpi\Event;

GLOBAL $DB,$CFG_GLPI;

$where='';

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

 //eliminamos el ultimo and y ordenamos por proveedor
$posicion= strripos($where, ' and');
$where = substr($where, 0, $posicion);
$where=$where." group by proveedor.name order by proveedor.name desc";

//Creamos la consulta y añadimos el where a la consulta
$query ="select 
proveedor.id as supplier_id,
proveedor.name, 
GROUP_CONCAT(distinct especialidad.name SEPARATOR '\n')  as especialidad, 
facturacion.facturacion, 
proveedor.cv_id,
ROUND(Sum(valoracion.calidad)/count(valoracion.calidad),1) as calidad, 
ROUND(Sum(valoracion.plazo)/count(valoracion.plazo),1) as plazo,
ROUND(Sum(valoracion.costes)/count(valoracion.costes),1) as costes, 
ROUND(Sum(valoracion.cultura)/count(valoracion.cultura),1) as cultura, 
ROUND(Sum(valoracion.suministros_y_subcontratistas)/count(valoracion.suministros_y_subcontratistas),1) as suministros_y_subcontratistas, 
ROUND(Sum(valoracion.sys_y_medioambiente)/count(valoracion.sys_y_medioambiente),1) as sys_y_medioambiente
from glpi_suppliers as proveedor 
LEFT JOIN glpi_plugin_comproveedores_annualbillings as facturacion
on facturacion.cv_id=proveedor.cv_id and YEAR(facturacion.anio)=YEAR(now())
LEFT JOIN glpi_plugin_comproveedores_valuations as valoracion
on valoracion.cv_id=proveedor.cv_id
LEFT JOIN glpi_plugin_comproveedores_listspecialties as lista_especialidades 
on lista_especialidades.cv_id=proveedor.cv_id
LEFT JOIN glpi_plugin_comproveedores_specialties as especialidad 
on especialidad.id=lista_especialidades.plugin_comproveedores_specialties_id ".$where;

$result = $DB->query($query);

           $html=  "<div id='tabla_seleccion_proveedores' align='center'><table class='tab_cadre_fixehov'>";
              
	$html.=  "<tr class='tab_bg_2 tab_cadre_fixehov nohover'>";
                $html.=  "<th style='background-color: white; border-bottom:0px;'></th>";
                $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;' colspan='14'>Lista de proveedores</th></tr>";
	$html.=  "<br/>";
	$html.=   "<tr>";
                            $html.=   "<th  style='background-color: white'>";
                            $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Nombre')."</th>";
                            $html.=   "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Especialidad')."</th>";
                            $html.=   "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('CV')."</th>";
                            $html.=   "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Facturación')."</th>";
                            $html.=   "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Calidad')."</th>";
                            $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Plazo')."</th>";
                            $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Costes')."</th>";
                            $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Cultura')."</th>";
                            $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('Suministros y Subcontratistas')."</th>";
                            $html.=  "<th class='center' style=' border: 1px solid #BDBDDB;'>".__('SyS y Medioambiente')."</th>";
                                 
                   $html.=  "</tr>";

	while ($data=$DB->fetch_array($result)) {
                                      
                        //Añadimos los id de los proveedores para la preselección
                        $preselecionIds=$preselecionIds.$data["supplier_id"]."-";
            
                            $html.=  "<tr class='tab_bg_2'>";
                                    $html.=  "<td  class='center' style=' border: 1px solid #BDBDDB;'>";
                                                $html.= "<input onclick='setListaProveedorfiltro(".$data["supplier_id"].")' id='proveedor_".$data["supplier_id"]."' name='proveedor_".$data["supplier_id"]."' type='checkbox'/>";
                                    $html.=  "</td>";
                                    
                                    $html.=  "<td class='center' style=' border: 1px solid #BDBDDB;'><a href='".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$data["supplier_id"]."'>".$data["name"]."</a></td>";   
                                    if(!empty($data['especialidad'])){
                                          $html.=  "<td class='center' style=' border: 1px solid #BDBDDB;'>".$data['especialidad']."</td>";
                                    }else{
                                          $html.=  "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                    if(!empty($data['cv_id'])){
                                        $html.=  "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxTrue.png'></td>";
                                    }
                                    else{
                                        $html.=  "<td class='center' style=' border: 1px solid #BDBDDB;'><img  style='vertical-align:middle; margin: 10px 0px;' src='".$CFG_GLPI["root_doc"]."/pics/CheckBoxFalse.png'></td>";
                                    }
                                    
                                    $facturacion=substr(number_format($data['facturacion'], 0, '', '.'),0,strlen(number_format($data['facturacion'], 0, '', '.')));
                                    
                                    $html.=  "<td class='center' style=' border: 1px solid #BDBDDB;'>".$facturacion."</td>";
                                    if(!empty($data['calidad'])){
                                        $html.=  "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['calidad']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['calidad']."</td>";
                                    }
                                    else{
                                        $html.= "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['plazo'])){
                                        $html.=  "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['plazo']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['plazo']."</td>";
                                    }
                                    else{
                                        $html.= "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['costes'])){
                                        $html.=  "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['costes']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['costes']."</td>";
                                    }
                                    else{
                                        $html.= "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['cultura'])){
                                         $html.=  "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['cultura']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['cultura']."</td>";
                                    }
                                    else{
                                        $html.= "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                     if(!empty($data['suministros_y_subcontratistas'])){
                                         $html.=  "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['suministros_y_subcontratistas']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['suministros_y_subcontratistas']."</td>";
                                    }
                                    else{
                                        $html.= "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }
                                    if(!empty($data['sys_y_medioambiente'])){
                                        $html.=  "<td class='center' style=' border: 1px solid #BDBDDB; font-weight: bold; color: black ; text-shadow:  2 white; background-image: url(".$CFG_GLPI["root_doc"]."/pics/valoracion_".getColorValoracion($data['sys_y_medioambiente']).".png); background-repeat: no-repeat;  background-position: center;'>".$data['sys_y_medioambiente']."</td>";
                                    }
                                    else{
                                        $html.= "<td class='center' style=' border: 1px solid #BDBDDB;'></td>";
                                    }

                           $html.=  "</tr>";
                        
	}
	
	$html.=  "</table></div>";

                
       /* $nombre_pdf="Lista de proveedores seleccionados.pdf";
        //exportamos el contrnido de la variable $html a pdf, y el pdf tendra el nombre de $nombre_pdf
         include ("../../../dompdf/output.php");*/
        echo $html;
        
        
        
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