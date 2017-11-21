<?php
require_once(__DIR__ . '/functions.php');
$OptionID = isset($_GET['OptionID2']) ? $_GET['OptionID2'] : '';
$OptionName = isset($_GET['OptionName2']) ? $_GET['OptionName2'] : '';
$SQLString = isset($_GET['SQLString2']) ? str_replace('$', '%s', $_GET['SQLString2']) : '';
$NothingFoundString = isset($_GET['NothingFoundString2']) ? $_GET['NothingFoundString2'] : '';
$ChoseString = isset($_GET['ChoseString2']) ? $_GET['ChoseString2'] : '';

$FatherID_RSAll = isset($_GET['FatherValue2']) ? $_GET['FatherValue2'] : -1;
if (!$OptionID) exit();
$query_RSAll = sprintf($SQLString, GetSQLValueString($FatherID_RSAll, "int"));
$RSAll = mysqli_query($localhost, $query_RSAll) or die(mysqli_error($localhost));
$row_RSAll = mysqli_fetch_assoc($RSAll);
$totalRows_RSAll = mysqli_num_rows($RSAll);
if ($totalRows_RSAll > 0) {
    ?>
    <option value><?PHP echo $ChoseString; ?></option>
<?php } else { ?>
    <option value><?PHP echo $NothingFoundString; ?></option>
<?php } // Show if recordset not empty ?>
<?php do { ?>
    <option value="<?php echo $row_RSAll[$OptionID]; ?>"><?php echo $row_RSAll[$OptionName]; ?></option>
<?php } while ($row_RSAll = mysqli_fetch_assoc($RSAll));
$rows = mysqli_num_rows($RSAll);
if ($rows > 0) {
    mysqli_data_seek($RSAll, 0);
    $row_RSAll = mysqli_fetch_assoc($RSAll);
}