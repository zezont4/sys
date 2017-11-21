<?php
/**
 *  يقوم هذا الكلاس بعمل الحقول الأساسية للنماذج (Form)
 *  @author     عبدالعزيز الطيار<zezont@gmail.com>
 *  @version    0.1 (last revision: December 26, 2014)
 *  @package    Zezo_Form
 */
class Form {
    private $formNameAndID;
    private $formMethod;
    private $formAction;
    private $formTitle;
    private $elemetsArray=array();

    /**
     * المتطلبات الأساسية لبداية الفورز
     * @param String [$formNameAndID='form1'] اسم الفورم
     * @param String [$formMethod='POST']     GET or POST
     * @param String [$formAction='#']        صفحة التنفيذ
     */
    public function __construct($formNameAndID='form1',$formMethod='POST',$formAction='#') {
        $this->formNameAndID=$formNameAndID;
        $this->formMethod=$formMethod;
        $this->formAction=$formAction;
    }

    private function getClass($classes) {
        return ($classes!='' ? "class='{$classes}'" :'');
    }

    private function getrequired($required) {
        return ($required===true ? 'required' :'');
    }
    private function createBlock($alphaOmegaNewLine,$elemetsArray=array(),$DBRow=array()){
        $alphaOmegaNewLine=trim($alphaOmegaNewLine);
        $max_width=($elemetsArray[3]>16) ? 16 : $elemetsArray[3];
        $f_width=array('','one','two','three','four','five','six','seven','eight',
                       'nine','ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen');
        switch ($elemetsArray[0]){
            case 'text':
            case 'password':
            case 'radio':
            case 'select':
            $blk= "<div class='{$f_width[$max_width]} columns {$alphaOmegaNewLine}'>";
            $blk.=        "<label class='fieldLabel' for='{$elemetsArray[2]}'>{$elemetsArray[1]}</label>";
            $blk.=      $elemetsArray[4];
            $blk.="</div>";
            return $blk;
            break;

            case 'submit':
            $blk= "<div class='{$f_width[$max_width]} columns btn {$alphaOmegaNewLine}'>";
            $blk.=      $elemetsArray[4];
            $blk.="</div>";
            return $blk;
            break;

            case 'emptySpace':
            $blk= "<div class='offset-by-{$f_width[$max_width]} one columns {$alphaOmegaNewLine}'>";
            $blk.=      "&nbsp;";
            $blk.="</div>";
            return $blk;
            break;

            case 'submitBlock';
            $blk= "<div class='submit'>";
            $blk.=      $elemetsArray[4];
            $blk.="</div>";
            return $blk;
            break;

            case 'titleBlock':
            case 'customCode';
            case 'hidden';
            return $elemetsArray[4];
            break;

            case 'br':
            return $elemetsArray[4];
            break;
        }
    }

    /**
     * إضافة سطر جديد
     */
    public function addNewLine() {
        $element ="<br class='clear'>";
        array_push($this->elemetsArray,array('br','','','',$element));
    }
    /**
     * إضافة فراغ من جهة اليمين
     * @param Number $number عدد الفراغات
     */
    public function addEmptySpace($number) {
        $element ="<br class='clear'>";
        array_push($this->elemetsArray,array('emptySpace','','',$number,$element));
    }
    /**
     * إضافة مربع عنوان
     * @param String $title [[Description]]
     */
    public function addTitleBlock($title) {
        $element ="<div class='title'><h2>{$title}</h2></div>";
        array_push($this->elemetsArray,array('titleBlock','','','',$element));
    }
    /**
     * إضافة إي كود مخصص
     * @param String $customCode [[Description]]
     */
    public function addCustom($customCode) {
        $element =$customCode;
        array_push($this->elemetsArray,array('customCode','','','',$element));
    }

    /**
     * إضافة حقل نصي
     * @param String  $label            عنوان الحقل النصي
     * @param String  $nameAndID        الاسم بالإنجليزي والآيدي
     * @param String  [$value='']       القيمة أو النص
     * @param Number  [$fieldWidth=4]   عرض الحقل من ١ إلى ١٦
     * @param Boolean [$required=flase] هل الحقل إلزامي أم لا
     * @param String  [$classes='']     كلاس يطبق على الحقل
     * @param String  [$others='']      أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addText($label,$nameAndID,$value='',$fieldWidth=4,$required=flase,$classes='',$others='') {
        $value = ($value!='' ? "value='{$value}'" :'');
        $element="<input type='text' name='{$nameAndID}' id='{$nameAndID}' placeholder='{$label}' {$value} {$this->getClass($classes)} {$this->getrequired($required)} {$others}>";
        array_push($this->elemetsArray,array('text',$label,$nameAndID,$fieldWidth,$element));
    }

    /**
     * إضافة حقل مخفي
     * @param String  $nameAndID        الاسم بالإنجليزي والآيدي
     * @param String  [$value='']       القيمة أو النص
     * @param Boolean [$required=flase] هل الحقل إلزامي أم لا
     * @param String  [$others='']      أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addHidden($nameAndID,$value='',$required=flase,$others='') {
        $value = ($value!='' ? "value='{$value}'" :'');
        $element="<input type='hidden' name='{$nameAndID}' id='{$nameAndID}' {$value} {$this->getrequired($required)} {$others}>";
        array_push($this->elemetsArray,array('hidden','',$nameAndID,'',$element));
    }

    /**
     * إضافة حقل كلمة مرور
     * @param String  $label            عنوان الحقل النصي
     * @param String  $nameAndID        الاسم بالإنجليزي والآيدي
     * @param String  [$value='']       القيمة أو النص
     * @param Number  [$fieldWidth=4]   عرض الحقل من ١ إلى ١٦
     * @param Boolean [$required=flase] هل الحقل إلزامي أم لا
     * @param String  [$classes='']     كلاس يطبق على الحقل
     * @param String  [$others='']      أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addPassword($label,$nameAndID,$value='',$fieldWidth=4,$required=flase,$classes='',$others='') {
        $value = ($value!='' ? "value='{$value}'" :'');
        $element="<input type='password' name='{$nameAndID}' id='{$nameAndID}' placeholder='{$label}' {$value} {$this->getClass($classes)} {$this->getrequired($required)} {$others}>";
        array_push($this->elemetsArray,array('password',$label,$nameAndID,$fieldWidth,$element));
    }


    /**
     * إضافة زر
     * @param String $label          عنوان الزر
     * @param String $nameAndID      الاسم بالإنجليزي والآيدي
     * @param Number [$fieldWidth=4] عرض الحقل من ١ إلى ١٦
     * @param String [$classes='']   كلاس يطبق على الحقل
     * @param String [$others='']    أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addSubmit($label,$nameAndID,$fieldWidth=4,$classes='',$others='') {
        $value = ($label!='' ? "value='{$label}'" :'');
        $element="<input type='submit' name='submit' {$value} {$this->getClass($classes)} {$others}>";
        array_push($this->elemetsArray,array('submit',$label,'',$fieldWidth,$element));
    }
    /**
     * إضافة زر داخل بلوك
     * @param String $label          عنوان الزر
     * @param String $nameAndID      الاسم بالإنجليزي والآيدي
     * @param Number [$fieldWidth=4] عرض الحقل من ١ إلى ١٦
     * @param String [$classes='']   كلاس يطبق على الحقل
     * @param String [$others='']    أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addSubmitBlock($label,$nameAndID,$fieldWidth=4,$classes='',$others='') {
        $value = ($label!='' ? "value='{$label}'" :'');
        $element='<div class="invalid-form-error-message"></div>';
        $element.="<input type='submit' name='submit' {$value} {$this->getClass($classes)} {$others}>";
        array_push($this->elemetsArray,array('submitBlock',$label,'',$fieldWidth,$element));
    }

    /**
     * إضافة خيارات متعددة
     * @param String  $label            عنوان الزر
     * @param String  $nameAndID        الاسم بالإنجليزي والآيدي
     * @param Number  $selectedValue    الخيار المحدد
     * @param Number  [$fieldWidth=4]   عرض الحقل من ١ إلى ١٦
     * @param Array   [$values=array(]  قائمة المحتويات مثال : $radio1=array('1'=>'name2', '0'=>'name1');
     * @param Boolean [$required=flase] هل الحقل إلزامي أم لا
     * @param String  [$classes='']     كلاس يطبق على الحقل
     * @param String  [$others='']      أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addRadio($label,$nameAndID,$selectedValue,$fieldWidth=4,$values,$required=flase,$classes='',$others='') {
        //        $lastRadio=count($values);
        $i=0;

        $element='<p>';
        foreach($values as $key => $radio) {
            $i++;
            $_required='';
            if($i===1){$_required=$this->getrequired($required);}
            $checked = ($selectedValue==$key && $selectedValue!='') ? "checked='checked'" :'';
            $value = "value='{$key}'";
            //            $element.="<label class='select'>";
            $element.=      "<input {$checked} type='radio' name='{$nameAndID}' id='{$nameAndID}_{$key}' {$value} {$this->getClass($classes)} {$_required} {$others}>";
            $element.=$radio;
            //            $element.="</label>";
            $element.="&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        $element.='</p>';
        array_push($this->elemetsArray,array('radio',$label,$nameAndID,$fieldWidth,$element));
    }

    /**
     * إضافة مربع خيارات
     * @param String  $label            عنوان المربع
     * @param String  $nameAndID        الاسم بالإنجليزي والآيدي
     * @param Number  $selectedValue    الخيار المحدد
     * @param Number  [$fieldWidth=4]   عرض الحقل من ١ إلى ١٦
     * @param Array   [$values=array(]  قائمة المحتويات مثال : $radio1=array('1'=>'name2', '0'=>'name1');
     * @param Boolean [$required=flase] هل الحقل إلزامي أم لا
     * @param String  [$classes='']     كلاس يطبق على الحقل
     * @param String  [$others='']      أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addCheckbox($label,$nameAndID,$selectedValue,$fieldWidth=4,$values,$required=flase,$classes='',$others='') {
        $i=0;
        $element='<p>';
        foreach($values as $key => $radio) {
            $i++;
            $rq='';
            if($i===1){$rq=$this->getrequired($required);}
            $checked = ($selectedValue==$key && $selectedValue!='') ? "checked='checked'" :'';
            $value = "value='{$key}'";
            //            $element.="<label class='select'>";
            $element.=      "<input {$checked} type='checkbox' name='{$nameAndID}' id='{$nameAndID}_{$key}' {$value} {$this->getClass($classes)} {$rq} {$others}>";
            $element.=$radio;
            //            $element.="</label>";
            $element.="&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        $element.='</p>';
        array_push($this->elemetsArray,array('radio',$label,$nameAndID,$fieldWidth,$element));
    }

    /**
     * إضافة قائمة خيارات
     * @param String  $label            عنوان الزر
     * @param String  $nameAndID        الاسم بالإنجليزي والآيدي
     * @param Number  $selectedValue    الخيار المحدد
     * @param String  [$fieldWidth=4]   عرض الحقل من ١ إلى ١٦
     * @param Array   [$values=array(]  قائمة المحتويات مثال : $radio1=array('1'=>'name2', '0'=>'name1');
     * @param Boolean [$required=flase] هل الحقل إلزامي أم لا
     * @param String  [$classes='']     كلاس يطبق على الحقل
     * @param String  [$others='']      أي إضافات إخرى تضاف قبل إغلاق التاق
     */
    public function addSelect($label,$nameAndID,$selectedValue,$fieldWidth=4,$values,$required=flase,$classes='',$others='',$firstOption='') {
        //        $element='';
        $element="<select name='{$nameAndID}' id='{$nameAndID}' {$this->getrequired($required)} {$this->getClass($classes)} {$others}>";
        if($firstOption) {
            $element.= "<option value=''>".$firstOption."</option>";
        }
        foreach($values as $key => $option) {
            $checked='';
            if($selectedValue==$key && $selectedValue!=''){$checked="selected";}
//            $checked = ($selectedValue==$key ? "selected" :'');
            $value = "value='{$key}'";
            $element.=      "<option {$value} {$checked}>".$option."</option>";
        }
        $element.="</select>";
        array_push($this->elemetsArray,array('select',$label,$nameAndID,$fieldWidth,$element));
    }
    /**
     * بدء عملية التنفيذ
     */
    public function render() {
        echo '<div class="content">';
        echo    "<form method='{$this->formMethod}' id='{$this->formNameAndID}' name='{$this->formNameAndID}' action='{$this->formAction}' data-parsley-validate>";
        $x=0;
//		$y=0;//لنسخة الآيباد بحيث يكون حقلين في السطر
        foreach ($this->elemetsArray as $key => $element) {
            $omegaNewLine='';
            $alphaNewLine='';
            //من أجل عمل سطر جديد تلقائيا عندما يكون مجموع عرض العناصر أكبر من ١٦

            if($x===0)  {$alphaNewLine='alpha';}
            $x+=$element[3];
//			$y+=$element[3];
            if($x>16){echo "<br class='clear'>";}
            if($this->elemetsArray[$key+1][0]==='br') {$x=0; $omegaNewLine='omega';}
            //            if($this->elemetsArray[$key-1][0]==='br') {$x=0;}
            if($x>= 16) {$omegaNewLine='omega';}
            echo $this->createBlock($omegaNewLine.' '.$alphaNewLine,$element);
            if($x>= 16) {$x=0;echo "<br class='clear'>";}
//			if($y>= 8) {$y=0;echo "<br class='clear_mobile'>";}
        }
        echo "<input type='hidden' name='token' value='".Token::generate($this->formNameAndID)."'>";
        echo "<input type='hidden' name='{$this->formNameAndID}' value='{$this->formNameAndID}'>";
        echo    "</form>";
        echo '</div>';
    }
}
?>
