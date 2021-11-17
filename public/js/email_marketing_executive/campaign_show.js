

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

    $('#form-upload-ebb-submit').on('click', function (e) {
        e.preventDefault();
        let form_data = new FormData($('#form-upload-ebb')[0]);
        $.ajax({
            url: URL +'/email-marketing-executive/campaign/upload-ebb-file/' + $('meta[name="ca-eme-id"]').attr('content'),
            processData: false,
            contentType: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if(response.status === true) {
                    $('#modal-upload-ebb').modal('hide');
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    $('#modal-upload-ebb').modal('hide');
                    trigger_pnofify('error', 'Error while processing request', response.message);
                }
            }
        });
    });

});

function submitCampaign(_id) {
    if(_id && confirm('Are you sure to submit campaign?')) {
        $.ajax({
            type: 'post',
            url: URL + '/email-marketing-executive/campaign/submit-campaign/' + _id,
            dataType: 'json',
            success: function (response) {
                if(response.status === true) {
                    $('#div-upload-ebb').css('display', 'none');
                    $('#div-submit-campaign').css('display', 'none');
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    } else {

    }
}
