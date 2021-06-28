jQuery(document).ready(function($) {

    const EZD_Amount_Input = document.getElementById("EZD_Amount_Input");
    const EZD_Name_Input = document.getElementById("EZD_Name_Input");

    (EZD_Amount_Input != null)? EZD_Amount_Input.defaultValue="10000" : '';


    $('#erima_add_donate_frm').on('change' , function () {
        let name_val = EZD_Name_Input.value;
        let select_val = $('#EZD_Amount_Select').val();

        if (name_val !== '' && name_val !== null && select_val !== '0') {
            $('.EZD_Submit').attr('disabled' , false);
        } else {
            $('.EZD_Submit').attr('disabled' , true);
        }
    });

    $('#EZD_Amount_Select').on('change' , function () {
        let val = $(this).val();
        if (val === 'others'){
            $(EZD_Amount_Input).css('display' , 'block');
        } else {
            $(EZD_Amount_Input).css('display' , 'none');
            $('#EZD_Amount_Input').val(Number(val));
        }
    });

    // seperate digits
    $.fn.digits = function () {
        return this.each(function () {
            $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        })
    };
    $('.digits').digits();

});