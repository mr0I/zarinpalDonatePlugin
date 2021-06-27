jQuery(document).ready(function($) {

    $('#donate_select_submit').on('click' , function (e) {
        e.preventDefault();
        let selectedVal = $('#donate_select').val();
        const donate_select_submit_btn = $(this);

        let selectedDonatesIds = Array.from(document.querySelectorAll('input[name="chkDonates"]:checked'))
            .map(e => $(e).val());

        if (selectedVal === '0' || selectedDonatesIds.length === 0){
            alert('لطفا یک وضعیت یا یک مورد را انتخاب نمایید!');
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
                    donate_select_submit_btn.html('در حال انجام...').attr('disabled',true);
                },success: function (res , xhr) {
                    const response= JSON.parse(res.trim());
                    console.log(response);
                    if (response.result === 'Authenticate Error') alert('خطای اعتبارسنجی') ;
                    alert('تعداد ' + response.count + 'آیتم آپدیت شد.');
                    window.location.reload();
                },error:function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },complete:function () {
                    donate_select_submit_btn.html('انجام').attr('disabled',false);
                },timeout:10000
            });
        }


    })
});


