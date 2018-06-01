<?php

GLOBAL $DB,$CFG_GLPI;
$objCommonDBT=new CommonDBTM;

echo "<form name='searchformSupplier' method='get' action='".$CFG_GLPI["root_doc"]."/front/supplier.php'>";
echo"<table class='tab_cadre_fixe' style='width:35%; text-align-last: center; '>";
			
                                               echo"<th colspan='9' style='background-color:#BDBDBD; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>Busqueda de proveedores</th></tr>";
			echo"<tr class='tab_bg_1 center' style='background-color:#D8D8D8; border: 20px solid #BDBDDB;'>";
				echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Nombre') . "</td>";
				echo "<td  style='border-top: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>";
				Html::autocompletionTextField($objCommonDBT,'nombre_proveedor');
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center' style='background-color:#D8D8D8; border: 20px solid #BDBDDB;'>";
				echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('CIF') . "</td>";
				echo "<td style='border-top: 2px solid #BDBDBD;  border-right: 2px solid #BDBDBD;'>";
				Html::autocompletionTextField($objCommonDBT,'cif');
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center ' style='background-color:#D8D8D8; border: 20px solid #BDBDDB;'>";
				echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD;'>" . __('Proyecto') . "</td>";
				echo "<td  style='border-top: 2px solid #BDBDBD;  border-right: 2px solid #BDBDBD;'>";
				Html::autocompletionTextField($objCommonDBT,'nombre_proyecto');
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center' style='background-color:#D8D8D8; border: 20px solid #BDBDDB;'>";
				echo "<td style='font-weight:bold; background-color:#E6E6E6; border-top: 2px solid #BDBDBD; border-left: 2px solid #BDBDBD; border-right: 2px solid #BDBDBD; border-bottom: 2px solid #BDBDBD;'>" . __('Código Proyecto') . "</td>";
				echo "<td  style='border-top: 2px solid #BDBDBD;  border-right: 2px solid #BDBDBD; border-bottom: 2px solid #BDBDBD;'>";
				Html::autocompletionTextField($objCommonDBT,'codigo_proyecto');
				echo "</td>";
			echo "</tr>";

			echo"<tr class='tab_bg_1 center'  style='border: 0px; background-color:#f8f7f3;'>";
                                                            echo "<td colspan='2'><input type='submit' name='search' value='Siguiente' class='submit'/></td>";
			echo "</tr>";

			
echo "</table>";
echo "</form>";