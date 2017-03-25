<?php

$log = fopen(__DIR__."\logs\del_idle_request.txt", "a");
$message = 'Cronjob starts at ' . date('Y-m-d h:i:s'). "\r\n";

/* SELECT * from "request" WHERE "updated_at" < (SYSDATE - INTERVAL '30' MINUTE);*/

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$conn = oci_connect('CARPOOLNOW', 'ILikeCarpools', 'ccoracle.mwcog.org/prod12c.mwcog.org');

// $stid = oci_parse($conn, 'select table_name from user_tables');
// oci_execute($stid);
//
// while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
//     echo "<tr>\n";
//     foreach ($row as $item) {
//         echo "  <td>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
//     }
//     echo "</tr>\n";
// }
//
//
// $stid = oci_parse($conn, 'select * from "cuser"');
// oci_execute($stid);
//
// while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
//     echo "<tr>\n";
//     foreach ($row as $item) {
//         echo "  <td>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
//     }
//     echo "</tr>\n";
// }

$stid = oci_parse($conn, 'SELECT * from "request" WHERE "updated_at" < (SYSDATE - INTERVAL \'30\' MINUTE) ');
oci_execute($stid);

while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    $message .= "Deleting: ";
    foreach ($row as $item) {
        $message .= ($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."\r\n";
    }
    $message .= "\r\n";
}

$stid = oci_parse($conn, 'DELETE from "request" WHERE "updated_at" < (SYSDATE - INTERVAL \'30\' MINUTE) ');
oci_execute($stid);

$stid = oci_parse($conn, 'UPDATE "cuser" set "cuser_status" = \'idle\' WHERE "updated_at" < (SYSDATE - INTERVAL \'30\' MINUTE) ');
oci_execute($stid);

$message .= "____________________________________________\r\n";
fwrite($log, $message);

return $message;