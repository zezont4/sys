<?php
require_once('functions.php');
ini_set('max_execution_time', 0);
set_time_limit(0);
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}
//طي قيد الطلاب المرتبطين بحلقات مخفيه
$query_select = "SELECT * from 0_halakat where hide=1";
$select = mysqli_query($localhost, $query_select) or die(mysqli_error($localhost));
$row_select = mysqli_fetch_assoc($select);
do {
    $updateSQL = sprintf("UPDATE 0_students SET hide=1 WHERE StHalaqah=%s",
        GetSQLValueString($row_select['AutoNo'], "int")
    );
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        echo $Result1;
    }
} while ($row_select = mysqli_fetch_assoc($select));

//5 مقاطع
/*
$query_select = sprintf("SELECT * FROM er_shahadah sh, er_ertiqaexams ex where sh.ExamNo = ex.AutoNo and ex.ErtiqaID<=4",
    GetSQLValueString($manhaj_class, "int")
);
$select = mysqli_query($localhost, $query_select) or die(mysqli_error($localhost));
$row_select = mysqli_fetch_assoc($select);
do {
    $sora_count = 5;
    $total_discount = 0;
    $total_degree = intval($sora_count) * 20;;
    for ($i = 1; $i < ($sora_count + 1); $i++) {
        $total_discount += doubleval($row_select['Sora' . $i . 'Discount']);
    }
    $final_degree = doubleval($total_degree) - doubleval($total_discount);
    $updateSQL = sprintf("UPDATE `er_shahadah` SET Degree=%s WHERE ExamNo=%s",
        GetSQLValueString($final_degree, "double"),
        GetSQLValueString($row_select['ExamNo'], "int")
    );
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        echo $Result1;
    }
} while ($row_select = mysqli_fetch_assoc($select));
*/

//عشر مقاطع
/*
$query_select = sprintf("SELECT * FROM er_shahadah sh, er_ertiqaexams ex where sh.ExamNo = ex.AutoNo and ex.ErtiqaID>=5 and ex.ErtiqaID<=8",
    GetSQLValueString($manhaj_class, "int")
);
$select = mysqli_query($localhost, $query_select) or die(mysqli_error($localhost));
$row_select = mysqli_fetch_assoc($select);
do {
    $sora_count = 10;
    $total_discount = 0;
    $total_degree = intval($sora_count) * 10;;

    for ($i = 1; $i < ($sora_count + 1); $i++) {
        $total_discount += doubleval($row_select['Sora' . $i . 'Discount']);
    }
    $final_degree = doubleval($total_degree) - doubleval($total_discount);
    $updateSQL = sprintf("UPDATE `er_shahadah` SET Degree=%s WHERE ExamNo=%s",
        GetSQLValueString($final_degree, "double"),
        GetSQLValueString($row_select['ExamNo'], "int")
    );
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        echo $Result1;
    }
} while ($row_select = mysqli_fetch_assoc($select));
*/

//عشرين بدون تجويد
/*
$query_select = sprintf("SELECT * FROM er_shahadah sh, er_ertiqaexams ex where sh.ExamNo = ex.AutoNo and ex.ErtiqaID=10",
    GetSQLValueString($manhaj_class, "int")
);
$select = mysqli_query($localhost, $query_select) or die(mysqli_error($localhost));
$row_select = mysqli_fetch_assoc($select);
do {
    $sora_count = 20;
    $total_discount = 0;
    $total_degree = intval($sora_count) * 10;;

    for ($i = 1; $i < ($sora_count + 1); $i++) {
        $total_discount += doubleval($row_select['Sora' . $i . 'Discount']);
    }
    $final_degree = doubleval($total_degree) - doubleval($total_discount);
    $updateSQL = sprintf("UPDATE `er_shahadah` SET Degree=%s WHERE ExamNo=%s",
        GetSQLValueString($final_degree, "double"),
        GetSQLValueString($row_select['ExamNo'], "int")
    );
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        echo $Result1;
    }
} while ($row_select = mysqli_fetch_assoc($select));
*/


//عشرين مع تجويد
/*
$query_select = sprintf("SELECT * FROM er_shahadah sh, er_ertiqaexams ex where sh.ExamNo = ex.AutoNo and ex.ErtiqaID=9",
    GetSQLValueString($manhaj_class, "int")
);
$select = mysqli_query($localhost, $query_select) or die(mysqli_error($localhost));
$row_select = mysqli_fetch_assoc($select);
do {
    $sora_count = 20;
    $total_discount = 0;
    $total_degree = intval($sora_count) * 10;;

    for ($i = 1; $i < ($sora_count + 1); $i++) {
        $total_discount += doubleval($row_select['Sora' . $i . 'Discount']);
    }
    $final_degree = doubleval($total_degree) - doubleval($total_discount) + $total_discount + doubleval($row_select['Ek_slok']) + doubleval($row_select['	Ek_mwathbah']);
    $updateSQL = sprintf("UPDATE `er_shahadah` SET Degree=%s WHERE ExamNo=%s",
        GetSQLValueString($final_degree, "double"),
        GetSQLValueString($row_select['ExamNo'], "int")
    );
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        echo $Result1;
    }
} while ($row_select = mysqli_fetch_assoc($select));
*/