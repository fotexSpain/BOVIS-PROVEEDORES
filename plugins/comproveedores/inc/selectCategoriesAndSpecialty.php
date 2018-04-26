
<?php


	use Glpi\Event;

	include ("../../../inc/includes.php");

	$opt['comments']= false;
	$opt['addicon']= false;
	
	if($_GET['tipo']=='categoria'){

		$opt['specific_tags']=array('onchange' => 'cambiarEspecialidades(value)');
		$opt['condition']='glpi_plugin_comproveedores_roltypes_id='.$_GET['idRolType'];
	
		Dropdown::show('PluginComproveedoresCategory',$opt);

	}else{

		//Descomentar cuando este puento los id de category en la tabla specialty
		$opt['condition']='glpi_plugin_comproveedores_categories_id='.$_GET['idCategories'];
	
		Dropdown::show('PluginComproveedoresSpecialty',$opt);
	}
	
	
