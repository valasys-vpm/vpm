

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

});


function viewAgentLeadDetails(_ca_agent_id) {
    $.ajax({
        type: 'get',
        url: $('meta[name="base-path"]').attr('content') + '/team-leader/campaign/view-agent-lead-details/' + _ca_agent_id,
        success: function (response){
            if(response.status === true) {
                console.log(response.data);
                let data = null;
                let html = '';

                $.each(response.data, function (key, value) {
                    html += '' +
                        '<tr>\n' +
                        '   <td>'+ (key+1) +'</td>\n' +
                        '   <td>'+ value.first_name +'</td>\n' +
                        '   <td>'+ value.last_name +'</td>\n' +
                        '   <td>'+ value.company_name +'</td>\n' +
                        '   <td>'+ value.email_address +'</td>\n' +
                        '   <td>'+ value.phone_number +'</td>\n' +
                        '   <td>'+ value.address_1 +'</td>\n' +
                        '   <td>'+ value.address_2 +'</td>\n' +
                        '   <td>'+ value.city +'</td>\n' +
                        '   <td>'+ value.state +'</td>\n' +
                        '   <td>'+ value.zipcode +'</td>\n' +
                        '   <td>'+ value.country +'</td>\n' +
                        '   <td>'+ value.employee_size +'</td>\n' +
                        '   <td>'+ value.revenue +'</td>\n' +
                        '   <td>'+ value.company_domain +'</td>\n' +
                        '   <td>'+ value.website +'</td>\n' +
                        '   <td>'+ value.company_linkedin_url +'</td>\n' +
                        '   <td>'+ value.linkedin_profile_link +'</td>\n' +
                        '   <td>'+ value.linkedin_profile_sn_link +'</td>\n' +
                        '   <td>'+ moment(value.created_at).format('DD-MMM-YYYY') +'</td>\n' +
                        '</tr>';
                    '';
                });

                $("#modal-view-lead-details").find('tbody').html(html);

                $("#modal-view-lead-details").modal('show');
            } else {
                trigger_pnofify('error', 'Error while processing request', response.message);
            }
        }
    });
}
