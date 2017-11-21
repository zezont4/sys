function GetMarkName(Degree, MarkType) {
    var degree = parseFloat(Degree);
    //alert(Degree+'  '+degree);
    var shortMark;
    var longMark;
    if (degree >= 98 && degree <= 100) {
        shortMark = "أ+";
        longMark = "ممتاز مرتفع";
    } else if (degree >= 95 && degree <= 97.99) {
        shortMark = "أ";
        longMark = "ممتاز";
    } else if (degree >= 92 && degree <= 94.99) {
        shortMark = "ب+";
        longMark = "جيد جدا مرتفع";
    } else if (degree >= 88 && degree <= 91.99) {
        shortMark = "ب";
        longMark = "جيد جدا";
    } else if (degree >= 84 && degree <= 87.99) {
        shortMark = "ج+";
        longMark = "جيد مرتفع";
    } else if (degree >= 80 && degree <= 83.99) {
        shortMark = "ج";
        longMark = "جيد";
    } else if (degree < 80) {
        shortMark = "د";
        longMark = "راسب";
    }
    if (MarkType == "short") {
        return shortMark;
    } else if (MarkType == "long") {
        return longMark;
    }
}

function GetMarkMony(MarkShortName, MurtaqaID) {
    var degree = parseFloat(Degree);
    var murtaqaID = parseFloat(MurtaqaID);
    var m1 = 150,
        m2 = 175,
        m3 = 200,
        m4 = 225,
        m5 = 250,
        m6 = 275,
        m7 = 300,
        m8 = 325;
    var MoneyAdd;
    if (MarkShortName == "أ+") {
        MoneyAdd = 50;
    } else if (MarkShortName == "أ") {
        MoneyAdd = 0;
    } else if (MarkShortName == "ب" || MarkShortName == "ب+") {
        MoneyAdd = -10;
    } else if (MarkShortName == "ج" || MarkShortName == "ج+") {
        MoneyAdd = -20;
    } else if (MarkShortName == "د") {
        return 0;
    }

    switch (murtaqaID) {
        case 1:
            return m1 + MoneyAdd;
        case 2:
            return m2 + MoneyAdd;
        case 3:
            return m3 + MoneyAdd;
        case 4:
            return m4 + MoneyAdd;
        case 5:
            return m5 + MoneyAdd;
        case 6:
            return m6 + MoneyAdd;
        case 7:
            return m7 + MoneyAdd;
        case 8:
            return m8 + MoneyAdd;
    }
}
//مكافأة المعلم
function GetMarkMonyForTeacher(MarkShortName, MurtaqaID) {
    var degree = parseFloat(Degree);
    var murtaqaID = parseFloat(MurtaqaID);
    var m1 = 30,
        m2 = 40,
        m3 = 50,
        m4 = 60,
        m5 = 90,
        m6 = 100,
        m7 = 110,
        m8 = 120;
    var MoneyAdd;
    if (MarkShortName == "أ+" || MarkShortName == "أ") {
        MoneyAdd = 0;
    } else if (MarkShortName == "ب" || MarkShortName == "ب+") {
        MoneyAdd = -5;
    } else if (MarkShortName == "ج" || MarkShortName == "ج+") {
        MoneyAdd = -10;
    } else if (MarkShortName == "د") {
        return 0;
    }

    switch (murtaqaID) {
        case 1:
            return m1 + MoneyAdd;
        case 2:
            return m2 + MoneyAdd;
        case 3:
            return m3 + MoneyAdd;
        case 4:
            return m4 + MoneyAdd;
        case 5:
            return m5 + MoneyAdd;
        case 6:
            return m6 + MoneyAdd;
        case 7:
            return m7 + MoneyAdd;
        case 8:
            return m8 + MoneyAdd;
    }
}

//مكافأة المجمع
function GetMarkMonyForEdarah(MarkShortName, MurtaqaID) {
    var degree = parseFloat(Degree);
    var murtaqaID = parseFloat(MurtaqaID);
    var m1 = 15,
        m2 = 20,
        m3 = 25,
        m4 = 30,
        m5 = 35,
        m6 = 40,
        m7 = 45,
        m8 = 50;
    var MoneyAdd;
    if (MarkShortName == "أ+" || MarkShortName == "أ") {
        MoneyAdd = 0;
    } else if (MarkShortName == "ب" || MarkShortName == "ب+") {
        MoneyAdd = -5;
    } else if (MarkShortName == "ج" || MarkShortName == "ج+") {
        MoneyAdd = -10;
    } else if (MarkShortName == "د") {
        return 0;
    }

    switch (murtaqaID) {
        case 1:
            return m1 + MoneyAdd;
        case 2:
            return m2 + MoneyAdd;
        case 3:
            return m3 + MoneyAdd;
        case 4:
            return m4 + MoneyAdd;
        case 5:
            return m5 + MoneyAdd;
        case 6:
            return m6 + MoneyAdd;
        case 7:
            return m7 + MoneyAdd;
        case 8:
            return m8 + MoneyAdd;
    }
}


//خاص بمرتقى الفاتحة
function GetMarkName20(Degree, MarkType) {
    var degree = parseFloat(Degree);
    //alert(Degree+'  '+degree);
    var shortMark;
    var longMark;
    if (degree >= 90 && degree <= 100) {
        shortMark = "أ";
        longMark = "ممتاز";
    } else if (degree >= 80 && degree <= 89.99) {
        shortMark = "ب";
        longMark = "جيد جدا";
    } else if (degree >= 75 && degree <= 79.99) {
        shortMark = "ج";
        longMark = "جيد";
    } else if (degree < 75) {
        shortMark = "د";
        longMark = "راسب";
    }
    if (MarkType == "short") {
        return shortMark;
    } else if (MarkType == "long") {
        return longMark;
    }
}
//خاص بمرتقى الفاتحة
function GetMarkMony20(MarkShortName) {
    var money = 0;
    if (MarkShortName == "أ") {
        money = 5000;
    } else if (MarkShortName == "ب") {
        money = 4700;
    } else if (MarkShortName == "ج") {
        money = 4500;
    } else if (MarkShortName == "د") {
        money = 0;
    }
    return money;
}
//خاص بمرتقى الفاتحة
//مكافأة المعلم
function GetMarkMonyForTeacher20(MarkShortName) {
    // تلغى مكافأة المعلم من الإحصاءات لأنها تصرف من جائزة الحلافي في الحفل
     return 0;
     
    var money = 0;
    if (MarkShortName == "أ") {
        money = 1000;
    } else if (MarkShortName == "ب") {
        money = 1000;
    } else if (MarkShortName == "ج") {
        money = 1000;
    } else if (MarkShortName == "د") {
        money = 0;
    }
    return money;
}

//صغية الريجكس للتاريخ الهجري
/*function HijriDate(value) {
 try{
 return /(^13|^14)\d\d([- \/.\\])([1-9]|0[1-9]|1[012])(\2)([1-9]|0[1-9]|[12][0-9]|30)$/.test(value);
 }catch(er){
 alert(err.message);
 }
 }*/

//اضهار تنبيه إذا كان الفورم خطأ
function showError() {
    $('#form1,#form2').parsley({
        listeners: {
            onFormSubmit: function (isFormValid, event) {
                if (!isFormValid) {
                    alertify.error("يرجى التحقق من البيانات");
                } else {
                    $(".EnableDisable").removeAttr("disabled");
                }
            }
        }
    });
}


//مكافأة سلم البراعم
function GetBra3mMoney(Darajah) {
    switch (Darajah) {
        case '1':
            return '10';
        case '2':
            return '20';
        case '3':
            return '30';
        case '4':
            return '40';
        case '5':
            return '50';

    }
}

function ExamPoints(ErtiqaID, Degree) {
    //alert(ErtiqaID);
    //alert(Degree);
    ErtiqaP = 0;
    switch (ErtiqaID) {
        case '1' :
            ErtiqaP = 1.5;
            break;
        case '2' :
            ErtiqaP = 2;
            break;
        case '3' :
            ErtiqaP = 2.5;
            break;
        case '4' :
            ErtiqaP = 3;
            break;
        case '5' :
            ErtiqaP = 3.5;
            break;
        case '6' :
            ErtiqaP = 4;
            break;
        case '7' :
            ErtiqaP = 4.5;
            break;
        case '8' :
            ErtiqaP = 5;
            break;
    }
    //alert(ErtiqaP);
    return parseFloat((Degree / 100) * ErtiqaP).toFixed(2);
}

function roundMe(n, sig) {
    if (n === 0) return 0;
    var mult = Math.pow(10, sig - Math.floor(Math.log(n < 0 ? -n : n) / Math.LN10) - 1);
    return Math.round(n * mult) / mult;
}
/* ================================ 

 ِAuto complate list */