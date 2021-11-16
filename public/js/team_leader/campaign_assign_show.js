let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
let DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

//Initializations
$(function(){

    $("#filter_job_level").select2({
        placeholder: " -- Select Job Level(s) --"
    });

    $("#filter_job_role").select2({
        placeholder: " -- Select Job Role(s) --"
    });

    $("#filter_employee_size").select2({
        placeholder: " -- Select Employee Size(s) --"
    });

    $("#filter_revenue").select2({
        placeholder: " -- Select Revenue(s) --"
    });

    $("#filter_country").select2({
        placeholder: " -- Select Country(s) --"
    });

    $("#filter_state").select2({
        placeholder: " -- Select State(s) --"
    });

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

    $('#form-get-data-submit').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: URL + '/team-leader/campaign-assign/get-data',
            data: $('#form-get-data').serialize(),
            async : true,
            success: function (response) {
                if(response.status === true) {
                    if(response.data.length) {
                        $('#div-result-get-data').css('display','block');
                        $('#div-result-get-data').parent('div.card-footer').css('display','block');
                        $('#result-record-found').html(response.data.length);
                        $('#data_ids').val(response.data);
                    } else {
                        $('#data_ids').val('');
                    }
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    });

    $('#form-assign-data-submit').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: URL + '/team-leader/campaign-assign/assign-data',
            data: $('#form-assign-data').serialize(),
            async : true,
            success: function (response) {
                if(response.status === true) {
                    $('#result-record-found').html('');
                    $('#div-result-get-data').css('display','none');
                    $('#count-agent-data').html(response.countAgentData);
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    });

});

function viewAssignmentDetails(id) {
    $.ajax({
        type: 'get',
        url: $('meta[name="base-path"]').attr('content') + '/team-leader/campaign-assign/view-assignment-details/' + id,
        success: function (response){
            if(response.status === true) {
                console.log(response.data);
                let data = null;
                let html = '';
                if(response.data.length) {
                    $.each(response.data, function (key, value) {
                        html += '' +
                            '<tr>\n' +
                            '   <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;"></i></td>\n' +
                            '   <td>'+ value.user.first_name +' '+ value.user.last_name +'</td>\n' +
                            '   <td>'+ value.display_date +'</td>\n' +
                            '   <td>'+ value.allocation +'</td>\n' +
                            '   <td>'+ value.user_assigned_by.first_name +' '+ value.user_assigned_by.last_name +'</td>\n' +
                            '   <td></td>\n' +
                            '</tr>';
                        '';
                    });

                    $("#modal-view-assignment-details").find('tbody').html(html);

                    $("#modal-view-assignment-details").modal('show');

                } else {
                    trigger_pnofify('warning', 'Error while processing request', 'Data Not Found');
                }


            } else {
                trigger_pnofify('error', 'Error while processing request', response.message);
            }
        }
    });
}
