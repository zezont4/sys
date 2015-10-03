var month_name = new Array('محرم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جمادى الأولى', 'جمادى الثاني', 'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة');
var weekeay_short = new Array('أح', 'اث', 'ث', 'أر', 'خ', 'ج', 'س');
var weekeay_name = new Array('الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت');
$(document).ready(function () {
    var text_id_name;
    //if you change the class name,then you should change it in the css file.
    var main_class_name = '.zezo_date';
    var main_class_name_no_dot = 'zezo_date';
    var text_position;
    //get today hijri date and store it
    //default date is the date that was in the text field and the chosen date
    var z_default_year;
    var z_default_month;
    var z_default_day;

    var hijri_date = get_row_hijri_date(zezo_get_hijri_date('now'));

    var today_day = hijri_date.substring(6, 8);
    var today_month = hijri_date.substring(4, 6);
    var today_year = hijri_date.substring(0, 4);
    //make the UI only one time
    make_ui();
    //create event handlers
    load_click_events();
    //make text field read only
    $('input[zezo_date=true]').attr('readonly', 'readonly');
    //if the user clicked another fields
    $('input:not([zezo_date=true])').focus(function () {
        z_hide();
    });
    //triger focus event for only the zezo_date fields
    $('input[zezo_date=true]').focus(function () {
        //get the date from the text field
        if ($(this).val().length > 0) {
            //store the date that was in the text field
            var date_array = $(this).val().split('/');
            z_default_year = (date_array[0]);
            z_default_month = (date_array[1]);
            z_default_day = (date_array[2]);
        } else {
            //get today date
            z_default_day = today_day;
            z_default_month = today_month;
            z_default_year = today_year;
        }
        //store focused text field id and positoin,so they will be used later
        text_id_name = (this);
        text_position = $(this).position();

        show_cal();
    });
    function z_hide() {
        $(main_class_name).slideUp(200);
    }

    function load_days() {
        var mm, yyyy, yyyymmdd;
        mm = ((z_default_month < 10) ? '0' + parseInt(z_default_month, 10) : z_default_month);
        yyyy = z_default_year;
        yyyymmdd = String(yyyy) + String(mm) + '01';
        var first_day_no = get_g_date(yyyymmdd, 'yes');
        month_days = z_hejri_months(z_default_year, z_default_month);
        $(main_class_name + ' .d').html('');
        $(main_class_name + ' .d').removeClass('hidden_day');
        $(main_class_name + ' .d').removeAttr('id');
        var corrected_day;
        for (var i1 = 1; i1 < 43; i1++) {
            corrected_day = (i1 - parseInt(first_day_no, 10));
            if (i1 > first_day_no && corrected_day <= month_days) {
                $('.d_' + i1).html(corrected_day);
                $('.d_' + i1).attr('id', 'd_' + corrected_day);
            } else {
                $('.d_' + i1).addClass('hidden_day');
            }
            //weekends
            if ((i1 + 1) % 7 === 0 || i1 % 7 === 0) {
                $('.d_' + i1).addClass('weekend');
            }
        }
        var first_year = z_default_year - 13;
        for (var i8 = 1; i8 < 30; i8++) {
            $(main_class_name + ' .chooze_y[y_order=' + parseInt(i8, 10) + ']').html(first_year);
            $(main_class_name + ' .chooze_y[y_order=' + parseInt(i8, 10) + ']').attr('year_no', first_year);
            first_year++;
        }
        $(main_class_name + ' .d').removeClass('tody');
        $(main_class_name + ' .chooze_m').removeClass('tody');
        $(main_class_name + ' .d').removeClass('d_s');
        $(main_class_name + ' .chooze_m').removeClass('d_s');
        $(main_class_name + ' .chooze_y').removeClass('d_s');
        $(main_class_name + ' .chooze_y').removeClass('tody');

        $(main_class_name + ' .chooze_y[y_order=14]').addClass('d_s');
        $(main_class_name + ' .chooze_y[year_no=' + today_year + ']').addClass('tody');
        if (parseInt(today_year, 10) == parseInt(z_default_year, 10)) {

            //if the selected month equal today month then >>>
            if (parseInt(today_month, 10) == parseInt(z_default_month, 10)) {
                $(main_class_name + ' #d_' + parseInt(today_day, 10)).addClass('tody');
            }

            $(main_class_name + ' .chooze_m[m_no=' + parseInt(today_month, 10) + ']').addClass('tody');
        }
        $(main_class_name + ' .d').removeClass('d_s');
        $(main_class_name + ' .chooze_m').removeClass('d_s');
        $(main_class_name + ' #d_' + parseInt(z_default_day, 10)).addClass('d_s');
        $(main_class_name + ' .chooze_m[m_no=' + parseInt(z_default_month, 10) + ']').addClass('d_s');

        $(main_class_name + ' .z_h_month').attr('id', 'm_' + parseInt(z_default_month, 10));
        $(main_class_name + ' .z_h_month').html(month_name[parseInt(z_default_month, 10) - 1]);

        $(main_class_name + ' .z_h_year').html(z_default_year);

        //change height if there is a day in the last row
        var last_row = $('.d_36').attr('id');
        if (typeof (last_row) === 'undefined') {
            $(main_class_name).css('height', '200px');
        } else {
            $(main_class_name).css('height', '220px');
        }
    }

    //create divs and numbers
    function make_ui() {
        $("body").append('<div class="' + main_class_name_no_dot + '"></div>');
        $(main_class_name).css('display', 'none');
        //Years ##########################
        $(main_class_name).append('<div class="z_head"></div>');
        $(main_class_name + " .z_head").append('<div class="z_ok">إغلاق</div>');
        //$(main_class_name + " .z_head").append('<div class="z_cancel"></div>');
        $(main_class_name + " .z_head").append('<div class="z_clear">مسح</div>');

        $(main_class_name + " .z_head").append('<span class="tody_date">اليوم</span>');
        $(main_class_name + " .z_head").append('<span class="top_icon" id="z_previous"></span>');

        $(main_class_name + " .z_head").append('<div class="year_month"></div>');
        $(main_class_name + " .year_month").append('<a class="z_h_year"></a><div class="clear_fix"></div>');
        $(main_class_name + " .year_month").append('<a class="z_h_month"></a>');

        $(main_class_name + " .z_head").append('<span class="top_icon" id="z_next"></span>');

        $(main_class_name + " .z_head").append('<div class="year_slider"></div>');
        $(main_class_name + " .year_slider").css('display', 'none');
        $(main_class_name + " .year_slider").append('<div class="right_years"></div>');
        $(main_class_name + " .year_slider").append('<div class="middle_years"></div>');
        $(main_class_name + " .year_slider").append('<div class="left_years"></div>');
        for (var i7 = 1; i7 < 10; i7++) {
            $(main_class_name + " .right_years").append('<div class="chooze_y" y_order="' + i7 + '"></div>');
            $(main_class_name + " .middle_years").append('<div class="chooze_y" y_order="' + (i7 + 9) + '"></div>');
            $(main_class_name + " .left_years").append('<div class="chooze_y" y_order="' + (i7 + 18) + '"></div>');
        }
        $(main_class_name + "[y_order='1']").html('سنوات سابقة');
        //months slider
        $(main_class_name + " .z_head").append('<div class="month_slider"></div>');

        $(main_class_name + " .month_slider").css('display', 'none');
        $(main_class_name + " .month_slider").append('<div class="right_months"></div>');
        $(main_class_name + " .month_slider").append('<div class="left_months"></div>');
        for (var i6 = 1; i6 < 7; i6++) {
            $(main_class_name + " .right_months").append('<div class="chooze_m" m_no="' + i6 + '">' + '(' + i6 + ') ' + month_name[i6 - 1] + '</div>');
            $(main_class_name + " .left_months").append('<div class="chooze_m" m_no="' + (i6 + 6) + '">' + '(' + (i6 + 6) + ') ' + month_name[(i6 - 1) + 6] + '</div>');
        }

        //Days (only the div container) ##########################
        $(main_class_name).append('<div class="z_days"></div>');

        $('.z_days').append('<div class="z_day_names"></div>');
        $('.z_days').append('<div class="z_day_no"></div>');

        for (var i5 = 0; i5 < 7; i5++) {
            $(main_class_name + " .z_day_names").append('<span class="c_day_name">' + weekeay_short[i5] + '</span>');
        }

        for (var i1 = 1; i1 < 43; i1++) {
            $(main_class_name + " .z_day_no").append('<span class="d d_' + i1 + '"></span>');
        }
    }

    //show zezo calender
    function show_cal() {
        $(main_class_name).css('top', text_position.top + 25);
        $(main_class_name).css('left', text_position.left);

        $(main_class_name + ' .y').val(z_default_year);
        load_days();

        $(main_class_name).slideDown(200);
    }

    //function set_titles() {
    //$(main_class_name + ' .d').removeAttr('title');
    //$(main_class_name + ' .m').removeAttr('title');
    //$(main_class_name + ' .y').removeAttr('title');
    //$(main_class_name + ' .tody').attr('title','تاريخ اليوم');
    //$(main_class_name + ' .d .d_s').attr('title','اليوم المحدد');
    //$(main_class_name + ' .z_months .d_s').attr('title','الشهر المحدد');
    //$(main_class_name + ' .z_days .d_s').attr('title','اليوم المحدد');
    //}
    //make_ui('.t1');
    //call this every time a calender is shown
    function load_click_events() {
        $('#z_previous').click(function () {
            change_month(-1);
        });
        $('#z_next').click(function () {
            change_month(1);
        });
        //***********************************************
        //day click function
        function day_click(day_no) {
            $(main_class_name + ' .d').removeClass('d_s');
            $(day_no).addClass('d_s');
            z_default_day = $(day_no).html();
            z_show_full_date();
        }

        //Day click
        $(main_class_name + ' .d').click(function () {
            day_click(this);
            z_hide();
        });
        //Day double click
        $(main_class_name + ' .d').dblclick(function () {
            day_click(this);
            z_hide();
        });
        //***********************************************

        function change_month(number) {
            $('.month_slider').slideUp(100);
            z_default_month = parseInt(z_default_month, 10) + parseInt(number, 10);
            if (z_default_month == 13) {
                z_default_month = 1;
                z_default_year++;
            }
            if (z_default_month === 0) {
                z_default_month = 12;
                z_default_year--;
            }
            $(main_class_name + ' .z_h_month').html(month_name[parseInt(z_default_month, 10) - 1]);
            $(main_class_name + ' .z_h_year').html(z_default_year);
            load_days();
            z_show_full_date();
        }


        $(main_class_name + ' .chooze_m').click(function () {
            z_default_month = parseInt($(this).attr('m_no'), 10);
            $(main_class_name + ' .z_h_month').html(month_name[z_default_month]);
            $(main_class_name + ' .month_slider').slideUp(200);
            load_days();
            z_show_full_date();
        });
        $(main_class_name + ' .chooze_y').click(function () {
            z_default_year = $(this).html();
            $(main_class_name + ' .z_h_year').html(z_default_year);
            $(main_class_name + ' .year_slider').slideUp(200);
            load_days();
            z_show_full_date();
        });


        $('.z_h_year').click(function () {
            $('.month_slider').slideUp(200);
            $('.year_slider').slideToggle(200);
        });
        $('.z_h_month').click(function () {
            $('.year_slider').slideUp(200);
            $('.month_slider').slideToggle(200);
        });

        //***********************************************
        // OK click
        $(main_class_name + ' .z_ok').click(function () {
            //z_show_full_date();
            z_hide();
        });
        //set today date 
        $(main_class_name + ' .tody_date').click(function () {
            hijri_date = get_row_hijri_date(zezo_get_hijri_date('now'));
            today_day = hijri_date.substring(6, 8);
            today_month = hijri_date.substring(4, 6);
            today_year = hijri_date.substring(0, 4);
            z_default_day = today_day;
            z_default_month = today_month;
            z_default_year = today_year;
            //$(text_id_name).val(today_full_date);
            z_show_full_date();
            z_hide();
        });

        // Cancel click
        $(main_class_name + ' .z_cancel').click(function () {
            //restore the previous date that was in the text field
            if (date_array.length > 0) {
                $(text_id_name).val(date_array[0] + '/' + date_array[1] + '/' + date_array[2]);
            } else {
                $(text_id_name).val('');
            }
            z_hide();
            z_show_full_date();
        });

        //Clear click
        $(main_class_name + ' .z_clear').click(function () {
            $(text_id_name).val('');
            z_hide();
            $(text_id_name).trigger('change');
            //z_show_full_date()
        });
        //***********************************************
    }

    //fill date in text box
    function z_show_full_date() {
        var dd, mm, yyyy, yyyymmdd;
        dd = ((z_default_day < 10) ? '0' + parseInt(z_default_day, 10) : z_default_day);
        mm = ((z_default_month < 10) ? '0' + parseInt(z_default_month, 10) : z_default_month);
        yyyy = z_default_year;
        yyyymmdd = String(yyyy) + String(mm) + String(dd);
        $(text_id_name).val(yyyy + '/' + mm + '/' + dd);
        $(text_id_name).trigger('change');
        //set_titles();
    }
});