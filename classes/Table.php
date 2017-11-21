<?php
/**
* هذا الكلاس يقوم ببناء جدول متكامل من خلال بعض المعطيات
*/
class Table extends TableCustom{
    private $rowsLimit;
    private $rowsAll;
    private $totlatRows;
    private $class2;
    private $tooltip;
    private $sortByDB;
    private $_pdo;
    private $ID='';
    private $title='';
    private $titleblock='';
    private $rowsCount=10;
    private $sqlQuery;
    private $arrayOfValues=array();
    private $sqlOrderBy='';
    private $searchForm=false;
    private $headerSorting=false;
    private $formWhereSql;
    public $zAutoNo;
    public $startRow;
    private $finalColsArray=array();
    private $DBSumField = array();

    /**
     * <b>رقم مميز للجدول</b>
     * <i>اختياري</i>
     * ويستخدم إذا كنت تريد استخدام أكثر من جدول في نفس الصفحة
     * بحيث تعطي كل جدول رقم يختلف عن الآخر
     * @param {Number} $ID رقم الجدول
     */
    public function setID($ID){
        $this->ID=$ID;
    }

    private function flowScript() {
        //		$( "#'.$this->ID.'" ).toggleClass( "ui-table-reflow ui-table" );
        return
            '<script>
            $("#changeTable'.$this->ID.'").click(function () {
            $( "#'.$this->ID.'" ).toggleClass( "ui-table ui-table-reflow ui-responsive" );
            var dr = $( "#'.$this->ID.'" ).attr( "data-role" );
            if(dr=="table") {
                $( "#'.$this->ID.'" ).attr( "data-role", "table1" );
            } else {
                $( "#'.$this->ID.'" ).attr( "data-role", "table" );
            }
        });
        </script>';
    }

    /**
     * <b>إضافة مجموع لعمود معين في نهاية الجدول</b>
     * @param String $DBFieldName اسم الحقل من قاعدة البيانات
     */
    public function addSum($DBFieldName) {
        $this->DBSumField[$DBFieldName]=0;
    }
    /**
     * إضافة عنوان فوق الجدول
     * <i>اختياري</i>
     * @param String $title نص العنوان
     */
    public function setTitle($title){
        $this->title="<caption>{$title}<caption>";
    }
    /**
     *إضافة بلوك عنوان منسق
     * <i>اختياري</i>
     * @param String $title نص العنوان
     */
    public function setTitleblock($title){
        $this->titleblock.= "<ol class='breadme'><legend>{$title}</legend></ol>";
    }
    /**
     * عدد الصفوف في كل صفحة
     * <i>اختياري والأصل عرض 20 صف في كل صفحة</i>
     * @param Number [$rowsCount=20] عدد الصفوف
     */
    public function setRowsCount($rowsCount=20){
        $this->rowsCount=$rowsCount;
    }
    /**
     * عبارة الاستعلام
     * وبدون عبارة ORDER BY
     * مثال
     * <i>SELECT * FROM table1 where field1=5 </i>
     * @param String $sqlQuery               عبارة الاستعلام
     * @param Array  [$arrayOfValues=array()] array like : array(':name' => 'name')
     */
    public function setSqlQuery($sqlQuery,$arrayOfValues=array()){
        $this->sqlQuery=$sqlQuery;
        $this->arrayOfValues=$arrayOfValues;
    }
    /**
     * أسماء الحقول التي تريد ترتيب الجدول بناء عليها
     * <i>اختياري</i>
     * مثال
     * <i>Field1,Field2 ASC</i>
     * @param String $sqlOrderBy أسماء الحقول مفصولة بفاصلة
     */
    public function setSqlOrderBy($sqlOrderBy){
        $this->sqlOrderBy=$sqlOrderBy;
    }
    /**
     * هذه الدالة تقوم بإضافة الحقول  إلى الجدول
     * وهي مصفوفة متعددة لكل صف في الجدول فيها
     * 1- نوع الحقل <i>('zAutoNo' , 'zField' , 'zDate' , 'zLink|index.php?StId=' , 'zImage|index.php?StId=|img1.png')</i>
     * 2- اسم رأس الجدول
     * 3- اسم الحقل من القاعدة
     * 4- كلاس عام
     * 5- رقم الشروط مفصولة بعلامة \
     * <b>أمثلة</b>
     * <i>
     * addColumn('zAutoNo'                       , 'م'        ,    ''     , ''          ,'')
     * addColumn('zField'                        , 'الاسم'     , 'StName1' , ''          ,'2|3')
     * addColumn("zLink|index.php?StID="         ,'تعديل'     ,'StID'     ,''           ,'')
     * addColumn("zImage|index.php?StID=|img.png",'تفاصيل'    ,'StID'     ,'noPaddings' ,'')
     * addColumn('zCustom|1'                     ,'التاريخ'   ,''         ,''           ,'')
     *
     * </i>
     * @param String [$columnType='zField'] نوع الحقل وهو أحد الأنواع التالية : ('zAutoNo','zField','zDate','zLink|index.php?StId=','zImage|index.php?StId=|img1.png','zCustom|1')
     * @param String [$columnLable='']      عنوان رأس العمود
     * @param String [$DBFiledName='']      اسم الحقل في قاعدة البيانات
     * @param String [$columnClass='']      كلاس عام يطبق على العمود كاملا
     * @param Number [$conditionalClass=''] أرقام الكلاسات المشروطة ويفصل بين الأرقام بـ | هذه العلامة
     */
    public function addColumn($columnType='zField',$columnLable='',$DBFiledName='',$columnClass='',$conditionalClass='') {
        array_push($this->finalColsArray,array($columnType,$columnLable,$DBFiledName,$columnClass,$conditionalClass));
    }

    /**
     * أضافة خيار الفرز والبحث للجدول
     * @param Boolean [$value=true] true = show form
     */
    public function addSearchForm($value=true){
        $this->searchForm=$value;
    }
    /**
     * تمكين ترتيب الأعمدة عند الضغط على العنوان العلوي
     * @param Boolean [$value=true] true = show form
     */
    public function addHeaderSorting($value=true){
        $this->headerSorting=$value;
    }
    public function __construct($ID=''){
        $this->_pdo = new DB();
        $this->ID=$ID;
    }

    public function getDBRowCount() {
        $DBRowCount = $this->_pdo->query($this->sqlQuery,$this->arrayOfValues);
        return count($DBRowCount);
    }

    /**
     * هذه الدالة تقوم ببناء الجدول بناء على المعطيات المطلوبة
     */
    public function render(){
        // خيارات ترتيب حقول الجدول من قاعدة البيانات
        if(Input::get($this->ID.'sortby')){
            $this->sortByDB=' ORDER BY '.Input::get($this->ID.'sortby');
        } else {
            $this->sortByDB= ($this->sqlOrderBy!='') ? ' ORDER BY '.$this->sqlOrderBy : '';
        }

        $searchField=Input::get($this->ID.'search');
        $comboField =Input::get($this->ID.'dbFieldName');
        $searchType =Input::get($this->ID.'searchType');
        if ($searchField!='' && $comboField!='-1') {
            // check if there is a (WHERE) statment in the (sqlQuery) th add the (searchForm) (WHERE) statment
            if(stristr($this->sqlQuery,' WHERE ')){
                $this->formWhereSql=' and ';
            } else {
                $this->formWhereSql=' WHERE ';
            }
            switch($searchType){
                //بحث متطابق
                case 1:
                $this->formWhereSql .= $comboField." = '".$searchField."'";
                break;
                //يحتوي على
                case 2:
                $this->formWhereSql .= $comboField." LIKE '%".$searchField."%'";
                break;
                //يبدأ بـ
                case 3:
                $this->formWhereSql .= $comboField." LIKE '".$searchField."%'";
                break;
                //ينتهي بـ
                case 4:
                $this->formWhereSql .= $comboField." LIKE '%".$searchField."'";
                break;
            }
            $this->sqlQuery .=$this->formWhereSql;
        }
        // جلب البيانات من قاعدة البيانات حسب المعطيات من الدالة
        //إما جميع البيانات أو عدد معين من الصفوف في كل صفحة
        if($this->rowsCount==0){//if you want to show all the data from DB
            $this->rowsLimit = $this->_pdo->query($this->sqlQuery.$this->sortByDB,$this->arrayOfValues);
        } else { // if you want to show some data only
            // get rows count to know the number of page
            $this->rowsAll = $this->_pdo->query($this->sqlQuery.$this->sortByDB,$this->arrayOfValues);
            $this->totlatRows = count($this->rowsAll);
            $pageNum = (isset($_GET[$this->ID.'pageNum'])) ? $_GET[$this->ID.'pageNum'] : 0;
            $this->startRow = max (($pageNum * $this->rowsCount)-$this->rowsCount,0);
            $totalRows = $this->totlatRows;
            $this->rowsLimit = $this->_pdo->query($this->sqlQuery.$this->sortByDB." LIMIT {$this->startRow},{$this->rowsCount}",$this->arrayOfValues);
            $totalPages = ceil($totalRows/$this->rowsCount)-1;

//            echo $this->sqlQuery.$this->sortByDB." LIMIT {$this->startRow},{$this->rowsCount}";
//            echo '<pre>';
//            print_r($this->arrayOfValues);
//            echo '</pre>';
        }

        //إضافة خيارات البحث
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
        }
        if($this->searchForm==true){
            $srch="<div class='row col-md-10 '>";
            $srch.= "<form name='{$this->ID}SearchForm' action='' method='post' class='form-horizontal'><div class='form-group'>";
            $srch.=        "<div class='col-sm-3'>";
            $srch.=        "<input type='text' class='form-control' placeholder='بحث' name='{$this->ID}search' value='".Input::get($this->ID.'search')."' style='padding-top: 10px'>";
            $srch.=        "</div>";
            $srch.=        "<div class='col-sm-3'>";
            $srch.=        "<select name='{$this->ID}dbFieldName' class='form-control' style='padding-top: 4px'>";
            $srch.=            "<option value='-1'>مصدر البحث...</option>";
            foreach($this->finalColsArray as $value){
                $colType=explode('|',$value[0]);
                $colType=$colType[0];
                if($colType==='zField'){
                    $srch.=     "<option ".($comboField==$value[2] ? 'selected' : '')." value='{$value[2]}'>{$value[1]}</option>";
                }
            }
            $srch.=        "</select>";
            $srch.=        "</div>";
            $srch.=        "<div class='col-sm-3'>";
            $srch.=        "<select name='{$this->ID}searchType' class='form-control' style='padding-top: 2px'>";
            $srch.=            "<option ".($searchType==2 ? 'selected' : '')." value='2'>يحتوي على ...</option>";
            $srch.=            "<option ".($searchType==1 ? 'selected' : '')." value='1'>بحث متطابق</option>";
            $srch.=            "<option ".($searchType==3 ? 'selected' : '')." value='3'>يبدأ بـ...</option>";
            $srch.=            "<option ".($searchType==4 ? 'selected' : '')." value='4'>ينتهي بـ...</option>";
            $srch.=        "</select>";
            $srch.=        "</div>";
            $srch.=        "<div class='col-sm-2'>";
            $srch.=        "<input name='submit' type='submit'  value='بحث' class='form-control btn btn-default'>";
            $srch.=        "</div></div>";
            $srch.= "</form></div><div><br></div><br><hr><br>";
        }
        // بداية بناء الجدول

        echo "<div class='content'>";
        echo $this->titleblock;
        echo "<div class='padding'>";
        echo $srch;
//        echo '<a id="changeTable'.$this->ID.'" class="changeTable">تغيير تنسيق الجدول</a>';
//        echo $this->flowScript();
        echo '<table id="'.$this->ID.'" data-role="table" class="table table-striped">';
        echo '<thead>';
        echo $this->title;
        // create table headers
        foreach($this->finalColsArray as $value) {
            $headerClass= ($value[3]=='') ? "" : "class ='{$value[3]}'";
            $colType=explode('|',$value[0]);
            $colType=$colType[0];
            switch ($colType){
                case 'zField':
                case 'zDate':
                // ascend and descend order
                $lastSort=escape(trim(Input::get($this->ID.'sortby')));
                $newSort =escape(trim($value[2].' ASC '));
                $url='';

                if($lastSort==$newSort) {
                    $url = modify_url(array($this->ID."sortby" => $value[2].' DESC '));
                } else {
                    $url = modify_url(array($this->ID."sortby" => $value[2].' ASC '));
                }

                if($this->headerSorting===true) {
                    echo "<td ",$headerClass,">",
                    "<a href='",$url,"'>",$value[1],"</a>".
                        "</td>";
                } else {
                    echo "<td ",$headerClass,">",$value[1],"</td>";
                }
                break;

                default:
                echo "<td ".$headerClass.">".$value[1]."</td>";
            }
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if($totalRows===0){
            echo "<tr><td colspan='",count($this->finalColsArray),"'>لا توجد بيانات ...</td></tr>";
        }
        //create the rest of the table
        $this->zAutoNo=$this->startRow+1;
        foreach($this->rowsLimit as $row) {
            echo '<tr>';
            foreach($this->finalColsArray as $value) {
                $class1='';
                $this->class2='';
                $allClasses='';
                $colType=explode('|',$value[0]);
                $colType=$colType[0];
                $value1 = '';
                switch ($colType) {
                    case 'zField':
                    $value1 = $row->$value[2];

                    foreach($this->DBSumField as $fld => $sum) {
                        if($value[2]===$fld) {
                            $this->DBSumField[$fld] +=intval($value1);
                        }
                    }
                    break;

                    case 'zAutoNo':
                    $value1 = $this->zAutoNo;
                    break;

                    case 'zDate':
                    $value1 = substr($row->$value[2],0,4).'/'.substr($row->$value[2],4,2).'/'.substr($row->$value[2],6,2);
                    break;

                    case 'zLink':
                    $z_link= explode('|',$value[0]);
                    $value1 =   "<a href='{$z_link[1]}{$row->$value[2]}'>".$value[1]."</a>";
                    break;

                    case 'zButton':
                    $z_link= explode('|',$value[0]);
                    $value1 =   "<a class='button-primary' href='{$z_link[1]}{$row->$value[2]}'>".$value[1]."</a>";
                    break;

                    case 'zImage':
                    $z_image= explode('|',$value[0]);
                    $value1 = "<a href='{$z_image[2]}{$row->$value[2]}'>".
                        "<img src='{$z_image[1]}' alt='{$value[1]}'>"."</a>";
                    break;

                    case 'zCustom':
                    $z_custom = explode('|',$value[0]);
                    $value1 = $this->zCustomCell($z_custom[1],$value,$row);
                    break;
                }

                $class1 = $value[3];
                $this->tooltip ='';
                if($value[4]===''){
                    $this->class2 ='';
                } else { // check for any conditoin
                    $class_and_tooltip=$this->addCondition($value,$row);
                    $this->class2 .=$class_and_tooltip[0];
                    $this->tooltip ='title="'.$class_and_tooltip[1].'" ';
                }
                if($class1=='' && $this->class2 == ''){//if there isn't any classes
                    $allClasses='';
                } else {// if there is a class or a condition
                    $allClasses="class ='{$class1}{$this->class2}'";
                }
                echo "<td {$allClasses} {$this->tooltip}>",$value1,"</td>";
            }
            $this->zAutoNo++;
            echo '</tr>';
        }
        if (count($this->DBSumField)) {
            $countCols=count($this->DBSumField);
            $countSums=0;
            $i=0;
            echo "<tr class='sum_row'>";
            foreach($this->finalColsArray as $value) {
                $isSum=false;
                foreach($this->DBSumField as $fld => $sum) {
                    if($value[2]===$fld) {
                        $sumF=$sum;
                        $isSum=true;
                        $countSums++;
                    }
                }
                if($countSums===1){$countSums++; echo "<td class='sum_title' colspan='{$i}'>المجموع</td>";}
                if($isSum===true) {
                    echo "<td>{$sumF}</td>";
                } else {
                    if($countSums>1){ echo "<td></td>";}
                }
                $i++;
            }
        }
        echo "</tbody></table></div>";// End of the table
        $pagination = new Zebra_Pagination($this->ID);
        $pagination->records($totalRows);
        $pagination->records_per_page($this->rowsCount);
        $pagination->render();
        echo "</div>";
    }



}
?>
