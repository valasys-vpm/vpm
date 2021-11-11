

let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
let DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

//Initializations
$(function(){

    $('.foo-table').footable({
        "paging": { "enabled": true }
    });

    $('.toggle-pacing-details').click(function (){
        if($(this).hasClass('icon-plus-square')) {
            $(this).removeClass('icon-plus-square').addClass('icon-minus-square');
            $(this).parents('tr').next('tr').show(1000);
        } else {
            $(this).removeClass('icon-minus-square').addClass('icon-plus-square');
            $(this).parents('tr').next('tr').hide(500);
        }
    });

});


$(function(){

    $('#form-submit-campaign-submit').on('click', function (e) {
        e.preventDefault();
        let form_data = new FormData($('#form-submit-campaign')[0]);
        $.ajax({
            url: URL +'/qa-team-leader/campaign-assign/submit-campaign/' + $('meta[name="ca-qatl-id"]').attr('content'),
            processData: false,
            contentType: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if(response.status === true) {
                    $('#div-download-delivery-file').css('display', 'none');
                    $('#div-submit-campaign').css('display', 'none');
                    $('#modal-submit-campaign').modal('hide');
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    $('#modal-submit-campaign').modal('hide');
                    trigger_pnofify('error', 'Error while processing request', response.message);
                }
            }
        });
    });

});

function downloadDelivery(_id) {
    if(_id && confirm('Are you sure to download campaign NPF?')) {
        $.ajax({
            type: 'post',
            url: URL + '/quality-analyst/campaign/download-npf/' + _id,
            dataType: 'json',
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                    return window.location.href = URL + response.file_name;
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    } else {

    }
}
