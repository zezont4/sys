<?php
class Validate{
    private $_passed=false,
    $_errors=array(),
    $_db=null;

    public function __construct(){
        $this->_db = new DB();
    }

    public function check($source,$items=array()){
        foreach($items as $item=>$rules){
            foreach($rules as $rule=>$rule_value){
                $en_ar=explode('|',$item);
                $item_en=$en_ar[0];
                $item_ar=$en_ar[1];
                $value  = escape($source[$item_en]);
                $item_en = escape($item_en);
                if($rule==='required' && $value===''){
                    $this->addError("الحقل ({$item_ar}) مطلوب");
                }else if(!empty($value)){
                    switch($rule){
                        case 'min':
                        if(mb_strlen($value,'UTF-8') < intval($rule_value)){
                            $this->addError("الحقل ({$item_ar}) يجب أن لا يقل عن ({$rule_value}) من الحروف أو الأرقام");
                        }
                        break;
                        case 'max':
                        if(mb_strlen($value,'UTF-8') > $rule_value){
                            $this->addError("الحقل ({$item_ar}) يجب أن لا يجتاز ({$rule_value}) من الحروف أو الأرقام.");
                        }
                        break;
                        case 'matches':
                        $mtch=explode('|',$rule_value);
                        $mtch_en=$mtch[0];
                        $mtch_ar=$mtch[1];
                        if($value != $source[$mtch_en]){
                            $this->addError("الحقل ({$mtch_ar}) يجب أن يتطابق مع الحقل التالي ({$item_ar})");
                        }
                        break;
                        case 'unique':
                        $sql="select {$item_en} from {$rule_value} where {$item_en} = :{$item_en}";
                        $row = $this->_db->query($sql, array(':'.$item_en => $value));
                        $check = count($row);
                        if($check>0){
                            $this->addError("{$item_ar} ({$value}) موجود سابقا");
                        }
                        break;
                        case 'unique2':
                        $unique=explode('|',$rule_value);
                        if(count($unique)===3) {
                            $unique1=$unique[0];
                            $unique2=$unique[1];
                            $unique3=$unique[2];
                            $sql="select {$item_en} from {$unique1} where {$item_en} = :{$item_en} and {$unique2} <> :{$unique2}";
                            $row = $this->_db->query($sql, array(':'.$item_en => $value,':'.$unique2 => $unique3));
                            $check = count($row);
                            if($check>0){
                                $this->addError("{$item_ar} ({$value}) موجود سابقا");
                            }
                        }
                        break;
                    }
                }
            }
        }
        if(empty($this->_errors)){
            $this->_passed=true;
        }
        return $this;
    }
    public function addError($error){
        $this->_passed=false;
        $this->_errors[]=$error;
    }
    public function errors(){
        return $this->_errors;
    }
    public function passed(){
        return $this->_passed;
    }

    public function showAllErrors() {
        if($this->_errors){
            //            echo '<pre>',print_r($this->_errors),'</pre>';
            echo '<div class="clearfix">';
            foreach($this->_errors as $error) {
                echo '<div class="alert alert-warning">',$error,'</div>';
            }
            echo '</div>';
        }
    }

}
?>
