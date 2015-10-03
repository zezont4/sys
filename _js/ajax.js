function FillList(FatherValue1, NextListID1, OptionID1, OptionName1, SQLString1, NothingFoundString1, ChoseString1) {
    try {
        var url1 = "/sys/filllist.php?" +
            "FatherValue2=" + FatherValue1 +
            "&OptionID2=" + OptionID1 +
            "&OptionName2=" + OptionName1 +
            "&SQLString2=" + SQLString1 +
            "&NothingFoundString2=" + NothingFoundString1 +
            "&ChoseString2=" + ChoseString1;
//alert(url1);

        var xmlhttp;
        if (window.XMLHttpRequest) { // code for IE7+,Firefox,Chrome,Opera,Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6,IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(NextListID1).innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", url1, true);
        xmlhttp.send();
    } catch (err) {
        txt = "There was an error on this page.\n\n";
        txt += "Error description: " + err.message + "\n\n";
        txt += "Click OK to continue.\n\n";
        alert(txt);
    }
}


function ClearList(ListID) {
    document.getElementById(ListID).options.length = 0;
}

function SetSelectedIndex(ListID, Index) {
    document.getElementById(ListID).selectedIndex = Index;
}