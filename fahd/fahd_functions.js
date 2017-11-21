function do_calculate() {
    var bnd_total = 0;
    $('.bnd_degree').each(function (i, obj) {
        var i_val = $(this).val();
        if (i_val > 0) {
            bnd_total += parseFloat($(this).val());
        }
    });
    //console.log(bnd_total);
    //التأكد من أن مجموع المرتقيات والبراعم لا يتجاوز ٢٠
    var ertiqa_degree = $('#f_2a_d').val();
    var bra3m_degree = $('#f_2b_d').val();
    var ertiqa_and_bra3m_over_flow = (parseFloat(ertiqa_degree) + parseFloat(bra3m_degree)) - 20;
    var over_20 = ertiqa_and_bra3m_over_flow > 0 ? Math.abs(ertiqa_and_bra3m_over_flow) : 0;
    //console.log(over_20);

    $('#full_degree').val(parseFloat(bnd_total) - parseFloat(over_20));
}


function chk_change(chk_id, bnd_val) {
    $('#f_' + chk_id + "_n").click(function () {
        var ch = $('#f_' + chk_id + "_n").is(':checked');
        if (ch === true) {
            $('#f_' + chk_id + "_d").val(parseInt(bnd_val, 10));
        } else {
            $('#f_' + chk_id + "_d").val('0');
        }
        do_calculate();
    });
}

function txt_change(txt_id, bnd_val, calc_type, max_val) {
    var txt_t_change = "";
    if (calc_type == 2 || calc_type == 3) {
        txt_t_change = ',#f_' + txt_id + '_t';
    }
    $('#f_' + txt_id + '_n' + txt_t_change).keyup(function () {
        var txt_val = parseInt($('#f_' + txt_id + "_n").val(), 10);
        var d_resault = 0;
        if (txt_val > 0) {
            if (calc_type === 0) {
                d_resault = roundMe(txt_val * parseFloat(bnd_val, 10), 2);

            } else if (calc_type == 1) {
                d_resault = roundMe(txt_val * 2 / 10, 3);

            } else if (calc_type == 2) {
                d_resault = roundMe(txt_val * 20 / parseInt($('#f_' + txt_id + "_t").val(), 10), 3);

            } else if (calc_type == 3) {
                d_resault = roundMe(txt_val * 5 / parseInt($('#f_' + txt_id + "_t").val(), 10), 2);

            }
            if (d_resault > max_val) {
                $('#f_' + txt_id + "_d").val(max_val);
            } else {
                $('#f_' + txt_id + "_d").val(d_resault);
            }
        } else {
            $('#f_' + txt_id + "_d").val('0');
        }
        do_calculate();
    });
}

function cmbo_change(cmbo_id, cmbo_values) {
    $('#f_' + cmbo_id + '_n').change(function () {
        var path1 = cmbo_values.split(',');
        var cmbo_val = parseInt($('#f_' + cmbo_id + "_n").val(), 10);
        var d_resault = 0;
        if (cmbo_val > 0) {
            d_resault = roundMe(path1[cmbo_val] * 1, 2);
            $('#f_' + cmbo_id + "_d").val(d_resault);
        } else {
            $('#f_' + cmbo_id + "_d").val('0');
        }
        do_calculate();
    });
}

function get_teachers_st_count(teacher_no) {
    $.get("/sys/basic/ajax_students_count.php", {
            TID: teacher_no
        })
        .done(function (data) {
            $('#f_2a_t,#f_2b_t').val(data);
            $('#f_2a_t,#f_2b_t').trigger('keyup');
        });
}

function get_ertiqa_st_count(teacher_no, f_t_date1) {
    $.get("/sys/ertiqa/ajax_success_count.php", {
            TID: teacher_no,
            f_t_date: f_t_date1
        })
        .done(function (data) {
            $('#f_2a_n').val(data);
            $('#f_2a_n').trigger('keyup');
        });
}

function get_bra3m_st_count(teacher_no, f_t_date1) {
    $.get("/sys/ertiqa/ajax_bra3m_count.php", {
            TID: teacher_no,
            f_t_date: f_t_date1
        })
        .done(function (data) {
            $('#f_2b_n').val(data);
            $('#f_2b_n').trigger('keyup');
        });
}

$(document).ready(function () {
    do_calculate();
    //$('#f_t_date').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
    //$('#f_6a_t').val(3)
    $('#f_6a_t,#f_2a_n,#f_2b_n').attr("readonly", "readonly");

    txt_change("1a", 0, 1, 20);

    txt_change("2a", 0, 2, 20);
    txt_change("2b", 0, 2, 20);

    chk_change("3a", 2);
    chk_change("3b", 4);

    cmbo_change("4a", "0,1,2,3,4,4");
    chk_change("4b", 6);

    chk_change("5a", 5);
    chk_change("5b", 3);
    chk_change("5c", 2);

    txt_change("6a", 0, 3, 5);

    chk_change("7a", 7);

    cmbo_change("8a", "0,1,2,3,4,5,6,7,8,12");

    chk_change("9a", 5);

    txt_change("10a", 0.2, 0, 5);

    txt_change("11a", 1, 0, 2);

    chk_change("12a", 3);
    chk_change("13a", 2);

    cmbo_change("14a", "0,1,2,3,3");

    chk_change("15a", 0);
    chk_change("15b", 0);
    //var s_count='<?php echo $_GET["TID"];?>';
    var tody_h_date = $('#f_t_date').val();
    //$('#f_2a_t,#f_2b_t').trigger('keyup');
});

