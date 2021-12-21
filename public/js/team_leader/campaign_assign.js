/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let CAMPAIGN_TABLE;
let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

$(function (){

    $("#campaign_list").select2({
        placeholder: " --- Select Campaign ---",
    });

    $("#user_list").select2({
        placeholder: " --- Select User(s) ---",
    });

});


$(function (){

    CAMPAIGN_TABLE = $('#table-campaigns').DataTable({
        "lengthMenu": [ [10,500,400,300,200,100,-1], [10,500,400,300,200,100,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": URL + '/team-leader/campaign-assign/get-assigned-campaigns',
            data: {
                filters: function (){
                    let obj = {
                    };
                    localStorage.setItem("filters", JSON.stringify(obj));
                    return JSON.stringify(obj);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
        },
        "columns": [
            {
                data: 'campaign.campaign_id'
            },
            {
                render: function (data, type, row) {
                    return '<a href="'+URL+'/team-leader/campaign-assign/view-details/'+btoa(row.id)+'" class="text-dark double-click" title="View campaign details">'+row.campaign.name+'</a>';
                }
            },
            {
                orderable: false,
                render: function (data, type, row) {
                    return row.agents.length;
                }
            },
            {
                render: function (data, type, row) {
                    let date = new Date(row.campaign.start_date);
                    return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+MONTHS[date.getMonth()]+'/'+date.getFullYear();
                }
            },
            {
                render: function (data, type, row) {
                    let date = new Date(row.display_date);
                    return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+MONTHS[date.getMonth()]+'/'+date.getFullYear();
                }
            },
            {
                render: function (data, type, row) {
                    let deliver_count = parseInt(row.agent_lead_total_count);
                    let allocation = parseInt(row.allocation);
                    let shortfall_count = parseInt(row.campaign.shortfall_count);

                    if(shortfall_count) {
                        return deliver_count + ' <span class="text-danger" title="Shortfall Count">('+ shortfall_count +')</span>'+' / '+ allocation;
                    } else {
                        return deliver_count + ' / '+ allocation;
                    }

                }
            },
            {
                render: function (data, type, row) {
                    let status_id  = row.campaign.campaign_status_id;
                    let campaign_type = '';
                    if(row.campaign.parent_id) {
                        status_id = row.campaign.campaign_status_id;
                        campaign_type = ' (Incremental)'
                    }
                    switch (parseInt(status_id)) {
                        case 1: return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;"> Live'+campaign_type+' </span>';
                        case 2: return '<span class="badge badge-pill badge-warning" style="padding: 5px;min-width:50px;"> Paused'+campaign_type+' </span>';
                        case 3: return '<span class="badge badge-pill badge-danger" style="padding: 5px;min-width:50px;"> Cancelled'+campaign_type+' </span>';
                        case 4: return '<span class="badge badge-pill badge-primary" style="padding: 5px;min-width:50px;"> Delivered'+campaign_type+' </span>';
                        case 5: return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;"> Reactivated'+campaign_type+' </span>';
                        case 6: return '<span class="badge badge-pill badge-secondary" style="padding: 5px;min-width:50px;"> Shortfall'+campaign_type+' </span>';
                    }
                }
            },
            {
                orderable: false,
                render: function (data, type, row) {
                    let html = '';

                    html += '<a href="'+URL+'/team-leader/campaign-assign/view-details/'+btoa(row.id)+'" class="btn btn-outline-info btn-rounded btn-sm" title="View Campaign Details"><i class="feather icon-eye mr-0"></i></a>';

                    return html;
                }
            },
        ],
        "fnDrawCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('.dark-left-toolbar').each(function() {
                var id = $(this).data('id');
                $(this).toolbar({
                    content: '#toolbar-options-' + id,
                    position: 'left',
                    style: 'dark'
                });
            });

            $('.double-click').click(function() {
                return false;
            }).dblclick(function() {
                window.location = this.href;
                return false;
            });
        },
        "createdRow": function(row, data, dataIndex){
            let status_id  = data.campaign.campaign_status_id;
            if(data.campaign.children.length) {
                status_id = data.campaign.children[0].campaign_status_id;
            }
            switch (parseInt(status_id)) {
                case 1:
                    $(row).addClass('border-live');
                    break;
                case 2:
                    $(row).addClass('border-paused');
                    break;
                case 3:
                    $(row).addClass('border-cancelled');
                    break;
                case 4:
                    $(row).addClass('border-delivered');
                    break;
                case 5:
                    $(row).addClass('border-reactivated');
                    break;
                case 6:
                    $(row).addClass('border-shortfall');
                    break;
            }
        },
        order:[]
    });

    $('#button-campaign-assign').on('click', function(e) {
        let campaign_id = $("#campaign_list").val();
        let user_list = $("#user_list").val();
        let html = '';

        $("#modal-campaign-assign").find('.modal-body').html(html);

        html = getCampaignCard_html(campaign_id, user_list);
        $("#campaign_assign_ratl_id").val($("#campaign_list_"+campaign_id).data('caratl-id'));
        $("#modal-campaign-assign").find('.modal-body').html(html);

        $("#modal-campaign-assign").modal('show');
    });

    $("#button-reset-form-campaign-assign").on('click', function(){
        $("#form-campaign-assign").find('input').val('');
        $("#form-campaign-assign").find('select').val('').trigger('change');
    });

});


function getCampaignCard_html(_campaign_id, _user_list) {
    let html ='';

    let allocation = $("#campaign_list_"+_campaign_id).data('allocation')/(_user_list.length);
    let balance_allocation = $("#campaign_list_"+_campaign_id).data('allocation')%(_user_list.length);

    let end_date = new Date($("#campaign_list_"+_campaign_id).data('end-date'));

    html += '' +
        '<div class="card border border-info rounded">' +
        '   <h5 class="card-header" style="padding: 10px 25px;">'+$("#campaign_list_"+_campaign_id).data('name')+'</h5>' +
        '   <input type="hidden" name="campaign_id" value="'+_campaign_id+'">' +
        '   <div class="card-body" style="padding: 15px 25px;">' +
        '       <div class="row">' +
        '           <div class="col-md-6">' +
        '               <div class="row">' +
        '                   <div class="col-md-5"><h6 class="card-title">Allocation</h6></div>' +
        '                   <div class="col-md-7"><h6 class="card-title">: '+$("#campaign_list_"+_campaign_id).data('allocation')+'</h6></div>' +
        '               </div>' +
        '               <div class="row">' +
        '                   <div class="col-md-5"><h6 class="card-title">End Date</h6></div>' +
        '                   <div class="col-md-7"><h6 class="card-title">: '+$("#campaign_list_"+_campaign_id).data('end-date')+'</h6></div>' +
        '               </div>' +
        '               <div class="row">' +
        '                   <div class="col-md-5"><h6 class="card-title">Select Reporting Format File</h6></div>' +
        '                   <div class="col-md-7"><h6 class="card-title">: <input type="file" name="reporting_file"></h6></div>' +
        '               </div>' +
        '           </div>' +
        '           <div class="col-md-6 border-left">' +
        '               <h5 class="card-title mb-2">User(s) to Assign</h5>' +
        '               <hr class="m-0" style="margin-bottom: 5px !important;">' +
                        getUserAssignCard_html(0, _user_list, allocation, balance_allocation) +
        '           </div>' +
        '       </div>' +
        '   </div>' +
        '</div>';

    return html;
}

function getUserAssignCard_html(_key, _user_list, allocation, balance_allocation) {
    let html = '';

    $.each(_user_list, function (key, value){

        html += '<div class="row p-1">' +
            '   <div class="col-md-5"><h6 class="card-title">'+$("#user_list_"+value).data('name')+'</h6></div>' +
            '   <input type="hidden" name="users['+key+'][user_id]" value="'+value+'">' +
            '   <div class="col-md-7">' +
            '       <input type="text" name="users['+key+'][allocation]" class="form-control form-control-sm" value="'+ ( (key === (_user_list.length -1)) ? Math.floor((allocation + balance_allocation)) : Math.floor(allocation) ) +'" style="height: 30px;">' +
            '   </div>' +
            '</div>';
    });

    return html;
}
