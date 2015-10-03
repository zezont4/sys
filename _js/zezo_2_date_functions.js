//Examples:
//$('#RDate').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
//**************************************************//
//get formated date
if (typeof no_to_date != 'function') { 
	function no_to_date(yyyymmdd) {
		yyyymmdd = String(yyyymmdd);
		return String(yyyymmdd.substring(0,4) + '/' + yyyymmdd.substring(4,6) + '/' + yyyymmdd.substring(6,8));
	}
}
if (typeof add_days != 'function') { 
	function add_days(date1,number_of_days_to_add) {
		var source_date = new Date(no_to_date(date1));
		source_date.setDate(source_date.getDate() + number_of_days_to_add);
		 var d_y = source_date.getFullYear();
		 var d_m = ((parseInt(source_date.getMonth()+1) < 10) ? ('0' + (parseInt(source_date.getMonth()+1))) : (parseInt(source_date.getMonth())+1));
		 var d_d = (source_date.getDate() < 10) ? ('0' + source_date.getDate()) : source_date.getDate();
		 var d_day_no=source_date.getDay();
		 var h_date = new Array(String(d_y),String(d_m),String(d_d),d_day_no);
		return h_date;
	}
}

if (typeof get_g_date != 'function') { 
	function get_g_date(row_hijri_date,only_day_no) {
		var selecte_year2 = get_gre_year_array(row_hijri_date.substr(0,4));
		var selecte_month2 = parseInt(row_hijri_date.substr(4,2),10);
		var selecte_days = parseInt(row_hijri_date.substr(6,2),10);
		var total_days = 0;
		for (var i = 1; i < selecte_month2; i++) {
			total_days = (selecte_year2[i] == 1) ? total_days = total_days + 30 : total_days = total_days + 29;
		}
		date_and_day_no= add_days(selecte_year2[0],(total_days + selecte_days-1));
		if (only_day_no=='yes'){
				return date_and_day_no[3];
		}else{
				return date_and_day_no[0]+date_and_day_no[1]+date_and_day_no[2];
		}
	}
}
//get the number of days in a hijri month
if (typeof z_hejri_months != 'function') {
	function z_hejri_months(z_year,z_mounth) {
		var selecteYear = get_gre_year_array(z_year);
		var selected_month_val = selecteYear[parseInt(z_mounth,10)];
		var selected_month_days;
		if (selected_month_val === 1) {
			selected_month_days = 30;
		} else if (selected_month_val === 0) {
			selected_month_days = 29;
		}
		return selected_month_days;
	}
}
//##########################################
 var d,g_date,d_y,d_m,d_d,d_day_no,g_date_diff;
 //get row hijri date like: 14350606 from date array
 if (typeof get_row_hijri_date != 'function') { 
	function get_row_hijri_date(hijri_array_date){
		d_y = hijri_array_date[0];
		d_m = (hijri_array_date[1] < 10) ? ('0' + hijri_array_date[1]) : hijri_array_date[1];
		d_d = (hijri_array_date[2] < 10) ? ('0' + hijri_array_date[2]) : hijri_array_date[2];
		return String(d_y) + String(d_m) + String(d_d);
	 }
 }
 //get formated hijri date like: 1435/06/06 from date array
if (typeof get_formated_hijri_date != 'function') { 
	function get_formated_hijri_date(hijri_array_date){
		d_y = hijri_array_date[0];
		d_m = (hijri_array_date[1] < 10) ? ('0' + hijri_array_date[1]) : hijri_array_date[1];
		d_d = (hijri_array_date[2] < 10) ? ('0' + hijri_array_date[2]) : hijri_array_date[2];
		return String(d_y) +'/'+ String(d_m) +'/'+ String(d_d);
	 }
	  function get_hijri_day_no(hijri_array_date){
		//console.log(hijri_array_date);
		return hijri_array_date[3];
	 }
 }
 //get deffrence between to grogorian days
var _MS_PER_DAY = 1000 * 60 * 60 * 24;
if (typeof date_diff_in_days != 'function') { 
	function date_diff_in_days(a,b) {
	 // Discard the time and time-zone information.
	 a = new Date(a);
	 b = new Date(b);
	 var utc1 = Date.UTC(a.getFullYear(),a.getMonth(),a.getDate());
	 var utc2 = Date.UTC(b.getFullYear(),b.getMonth(),b.getDate());
	 return Math.floor((utc1 - utc2) / _MS_PER_DAY);
	}
}
//after we get the wanted "selected_year",we do some calculation to get (year,month,day)
if (typeof calculate_hijri_days != 'function') { 
	function calculate_hijri_days(hijri_array) {
	 var total_h_days = 0;
	 var h_year = hijri_array[1];
	 var h_month = 0;
	 var h_day = 0;
	 for (var i = 2; i < 14; i++) {
		 h_day = (g_date_diff + 1) - total_h_days;
		 total_h_days = (hijri_array[i] == 1) ? total_h_days = total_h_days + 30 : total_h_days = total_h_days + 29;
		 if (total_h_days > g_date_diff) {
			 h_month = i - 1;break;
		 }
	 }
	 var full_date_array=new Array([]);
	 full_date_array=[h_year,h_month,h_day,d_day_no];
	 return (full_date_array);
	}
}
var g_date_diff;
if (typeof zezo_get_hijri_date != 'function') { 
	function zezo_get_hijri_date(source_g_date) {
		if (source_g_date == 'now') {
			d = new Date();
		}else{
			d = new Date(no_to_date(source_g_date));
		}
		d_y = d.getFullYear();
		d_m = ((parseInt(d.getMonth()+1) < 10) ? ('0' + (parseInt(d.getMonth()+1))) : (parseInt(d.getMonth())+1));
		d_d = (d.getDate() < 10) ? ('0' + d.getDate()) : d.getDate();
		d_day_no=d.getDay();
		g_date = String(d_y) + String(d_m) + String(d_d);
		s=get_hijri_year_array(g_date);
		g_date_diff = (date_diff_in_days(no_to_date(g_date),no_to_date(s[0])));
		return calculate_hijri_days(s);
	}
}