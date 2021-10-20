
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

    $('.toggle-agent-details').click(function (){
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

function viewAssignmentDetails(id) {
    $.ajax({
        type: 'get',
        url: $('meta[name="base-path"]').attr('content') + '/manager/campaign-assign/view-assignment-details/' + id,
        success: function (response){
            if(response.status === true) {

                let data = null;
                let html = '';

                if(response.data.resultRATLs.length) {
                    data = response.data.resultRATLs;
                    $.each(data, function (key, value) {
                        html += '' +
                            '<tr>\n' +
                            '   <td><i class="feather icon-plus-square toggle-agent-details" style="cursor: pointer;font-size: 17px;" onclick="getAssignedAgents('+ value.id +', this);"></i></td>\n' +
                            '   <td>'+ value.user.first_name +' '+ value.user.last_name +'</td>\n' +
                            '   <td>'+ value.display_date +'</td>\n' +
                            '   <td>'+ value.allocation +'</td>\n' +
                            '   <td>'+ value.agents.length +'</td>\n' +
                            '   <td>'+ value.user_assigned_by.first_name +' '+ value.user_assigned_by.last_name +'</td>\n' +
                            '   <td></td>\n' +
                            '</tr>' +
                        '<tr id="agent-details-'+value.id+'" class="agent-details" style="display: none;">' +
                        '   <td colspan="7" class="bg-light text-left">' +
                        '       <table class="table table-hover foo-table text-center">\n' +
                        '           <thead>\n' +
                        '               <tr class="text-uppercase">\n' +
                        '                   <th class="text-center">Name</th>\n' +
                        '                   <th class="text-center">End Date</th>\n' +
                        '                   <th class="text-center">Allocation</th>\n' +
                        '                   <th class="text-center">Assigned By</th>\n' +
                        '               </tr>\n' +
                        '           </thead>' +
                        '           <tbody>' +
                        '           </tbody>' +
                        '       </table>' +
                        '   </td>' +
                        '</tr>' +
                        '';
                    });
                }

                if(response.data.resultVMs.length) {
                    data = response.data.resultVMs;
                    $.each(data, function (key, value) {
                        html += '' +
                            '<tr>\n' +
                            '   <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;" onclick="getAssignedVendors('+ btoa(value.id) +')"></i></td>\n' +
                            '   <td>'+ value.user.first_name +' '+ value.user.last_name +'</td>\n' +
                            '   <td>'+ value.display_date +'</td>\n' +
                            '   <td>'+ value.allocation +'</td>\n' +
                            '   <td>'+ value.vendors.length +'</td>\n' +
                            '   <td>'+ value.user_assigned_by.first_name +' '+ value.user_assigned_by.last_name +'</td>\n' +
                            '   <td></td>\n' +
                            '</tr>';
                        '';
                    });
                }


                $("#modal-view-assignment-details").find('tbody').html(html);

                $("#modal-view-assignment-details").modal('show');
            } else {
                trigger_pnofify('error', 'Error while processing request', response.message);
            }
        }
    });
}

function getAssignedAgents(_id, _this) {
    let html = '';
    if($(_this).hasClass('icon-plus-square')) {
        $.ajax({
            type: 'get',
            url: $('meta[name="base-path"]').attr('content') + '/manager/campaign-assign/view-assigned-agents/' + btoa(_id),
            success: function (response){
                if(response.status === true) {

                    if(response.data.length) {
                        $.each(response.data, function (key, value) {
                            html += '' +
                                '<tr>\n' +
                                '   <td>'+ value.user.first_name +' '+ value.user.last_name +'</td>\n' +
                                '   <td>'+ value.display_date +'</td>\n' +
                                '   <td>'+ value.allocation +'</td>\n' +
                                '   <td>'+ value.user_assigned_by.first_name +' '+ value.user_assigned_by.last_name +'</td>\n' +
                                '</tr>' +
                                '';
                        });
                        $("#agent-details-"+_id).find('tbody').html(html);
                    } else {
                        $("#agent-details-"+_id).find('tbody').html('<tr><td colspan="4">Campaign not assigned to agents.</td></tr>');
                    }

                } else {
                    trigger_pnofify('error', 'Error while processing request', response.message);
                }
            }
        });
        $(_this).removeClass('icon-plus-square').addClass('icon-minus-square');
        $(_this).parents('tr').next('tr').show(1000);
    } else {
        $(_this).removeClass('icon-minus-square').addClass('icon-plus-square');
        $(_this).parents('tr').next('tr').hide(500);
    }


    return html;
}
