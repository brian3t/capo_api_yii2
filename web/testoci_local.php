<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
 $conn = oci_connect('carpoolnowdb', 'Duip34jitjit-', '52.8.1.171/orcl');
var_dump($conn);
$stid = oci_parse($conn, 'select * from cuser');
oci_execute($stid);

echo "table\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
