jQuery(document).ready(function($) {

    $('#EZD_Amount_Select').on('change' , function () {
        let val = $(this).val();
        const EZD_Amount_Input = document.getElementById("EZD_Amount_Input");


        if (val === 'others'){
            $(EZD_Amount_Input).css('display' , 'block');
        } else {
            $(EZD_Amount_Input).css('display' , 'none');
            $('#EZD_Amount_Input').val(Number(val));
        }
    })
});