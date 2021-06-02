jQuery(document).ready(function($) {

    $('#donate_select_submit').on('click' , function (e) {
        e.preventDefault();
        let selectedVal = $('#donate_select').val();

        let selectedDonatesIds = Array.from(document.querySelectorAll('input[name="chkDonates"]:checked'))
            .map(e => $(e).val());
        
        if (selectedVal === '0'){
            alert('لطفا یک وضعیت را انتخاب نمایید!')
        } else {
            let nonce = $('#donate_select_nonce').val();

            let data = {
                action: 'payDonate',
                security : ZARINADMINAJAX.security,
                nonce: nonce,
                selectedDonatesIds: selectedDonatesIds
            };
            $.ajax({
                url: ZARINADMINAJAX.ajaxurl,
                type: 'POST',
                data: data,
                beforeSend: function () {
console.log('before');
                },
                success: function (res , xhr) {
                    console.log(res);

                },error:function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);


                }
                ,complete:function () {
                    console.log('completed');

                    //registerSubmitBtn.html(register_frm_submit_btn_txt).attr('disabled', false);
                },
                timeout:10000
            });
        }

        
    })
});


