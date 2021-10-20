

let URL = $('meta[name="base-path"]').attr('content');

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

});

function startCampaign(_id) {
    if(_id && confirm('Are you sure to start campaign?')) {
        $.ajax({
            type: 'post',
            url: URL + '/agent/campaign/start-campaign/' + _id,
            dataType: 'json',
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', 'Campaign Started.');
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    } else {

    }
}
