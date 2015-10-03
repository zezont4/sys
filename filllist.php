<?php require_once('Connections/localhost.php');?>
<?php
$FatherValue        = $_GET['FatherValue2'];
$OptionID           = $_GET['OptionID2'];
$OptionName         = $_GET['OptionName2'];
$SQLString          = $_GET['SQLString2'];
$NothingFoundString = $_GET['NothingFoundString2'];
$ChoseString        = $_GET['ChoseString2'];
?>
<?php $FatherID_RSAll = "-1";
if (isset($_GET['FatherValue2'])) {
    $FatherID_RSAll = $_GET['FatherValue2'];
}
mysqli_select_db($localhost,$database_localhost);
$query_RSAll = sprintf($SQLString,GetSQLValueString($FatherID_RSAll,"int"));
$RSAll = mysqli_query($localhost,$query_RSAll)or die(mysqli_error($localhost));
$row_RSAll       = mysqli_fetch_assoc($RSAll);
$totalRows_RSAll = mysqli_num_rows($RSAll);
?>
<?php if ($totalRows_RSAll > 0) // Show if recordset not empty 
    {?>

<option value><?PHP echo $ChoseString;?></option>
<?php } else {?>
<option value><?PHP echo $NothingFoundString;?></option>
<?php } // Show if recordset not empty ?>
<?php do {?>
<option value="<?php echo $row_RSAll[$OptionID];?>"><?php echo $row_RSAll[$OptionName];?></option>
<?php } while ($row_RSAll = mysqli_fetch_assoc($RSAll));
$rows = mysqli_num_rows($RSAll);
if ($rows > 0) {
    mysqli_data_seek($RSAll,0);
    $row_RSAll = mysqli_fetch_assoc($RSAll);
}?>
<?php mysqli_free_result($RSAll);?>