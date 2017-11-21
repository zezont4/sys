<?php
class TableCustom{
	/**
     * إضافة حقول وروابط مخصصة داخل الخلية
     * @param   Number $customID رقم الحقل المخصص
     * @param   Array  $value    المصفوفة التي تحتوي على بيانات الجدول
     * @param   Array  $row      السطر الحالي المجلوب من قاعدة البيانات
     * @returns String الشكل النهائي للحقل
     */
	public function zCustomCell($customID,$value,$row) {
		switch ($customID) {
			case 1:
			return stringToDate($row->std_startDate);
			break;

			case 2:
			$reporsts="<a href='college_st_report.php?id_st={$row->std_id_id}&name_s={$row->std_name}' class='btn btn-default'><span class='glyphicon glyphicon-list-alt'></span>";
			$pdo=new DB();
			$dbResult_report = $pdo->query('SELECT id FROM report where std_id_id=:std_id_id and check_ok=:check_ok', array(':std_id_id'=>$row->std_id_id,':check_ok'=>2));
			if (count($dbResult_report)>0){ $reporsts.="<span class=' badgemy'>".count($dbResult_report)."</span>";} 
			$reporsts.="</a>";
			return $reporsts;
			break;

			case 3:
			$pdo=new DB();
			$dbResultx = $pdo->row('SELECT * FROM report_final where id_st=:id_st',array(':id_st'=>$row->std_id_id));


			if ($dbResultx->id>0)
			{
$ok_report=($dbResultx->ok_report==0) ? ' glyphicon glyphicon-time ' : ' glyphicon glyphicon-thumbs-up '; 
  $title=($dbResultx->ok_report==0) ? '  تقرير يحتاج اعتماد  ' : ' تقرير  تم اعتماده ';
              
         
				$reporsts="<a href='college_report_finl.php?id_st=$dbResultx->id&name_s=$row->std_name' title='$title' class='btn btn-default'><span class='glyphicon glyphicon-list-alt'></span><span class='$ok_report'></span></a>";
            
			}else{ 				
				$reporsts="<div class='btn btn-default'><span class='glyphicon glyphicon-remove removeme'></span></div>";}
			return $reporsts;
			break;
		}
	}

	/**
* هذه الدالة تفيد في عمل شروط
* بحيث تضيف الشرط داخل الدالة
* وعند مطابقة الشرط للخلية ، يتم إضافة كلاس لها
* @param Array $value المصفوفة التي تحتوي على بيانات الجدول
* @param Array $row   السطر الحالي المجلوب من قاعدة البيانات
*/
	public function addCondition($value,$row) {
		$multiClasses= explode('|',$value[4]);
		foreach ($multiClasses as $cl){
			switch ($cl){
				case 1:
				if ($row->std_startDate==1){
					return array(' top_red_border bg_yellow','');
				}
				break;
			}
		}
		//        echo $this->class2;
	}
}
?>
