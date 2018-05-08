<?php

GLOBAL $DB,$CFG_GLPI;
$objCommonDBT=new CommonDBTM;

echo "<form name='searchformSupplier' method='get' action='/BOVIS-PROVEEDORES/front/supplier.php'>";
echo"<table class='tab_cadre_fixe'><tbody>";
			


			echo"<th colspan='6'>Filtro Proveedores</th></tr>";
			echo"<tr class='tab_bg_1 center'>";
				echo "<td>" . __('BIM') . "</td>";
				echo "<td>";
				Dropdown::showFromArray('bim', array('' =>'Todos',-1 =>'------', 1=>'Sí' , 0 =>'No'));
				echo "</td>";

				echo "<td>" . __('LEED') . "</td>";
				echo "<td>";
				Dropdown::showFromArray('leed', array('' =>'Todos',-1 =>'------', 1=>'Sí' , 0 =>'No'));
				echo "</td>";

				echo "<td>" . __('BREEAM') . "</td>";
				echo "<td>";
				Dropdown::showFromArray('breeam', array('' =>'Todos',-1 =>'------', 1=>'Sí' , 0 =>'No'));
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'>";
				echo "<td colspan='6'><input type='submit' name='search' value='Filtrar' class='submit'/></td>";
			echo "</tr>";

			
echo "</table>";
echo "</form>";