<?php
class PluginComproveedoresSubvaluation extends CommonDBTM{
	static $rightname="plugin_comproveedores";
	var $can_be_translated=true;

	static function getTypeName($nb=0){
		return _n('SubEvaluación', 'SubEvaluaciónes', $nb, 'comproveedores');
	}
}
?>