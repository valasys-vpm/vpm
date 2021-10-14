/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let CAMPAIGN_TABLE;
let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

$(function (){

    $("#campaign_list").select2({
        placeholder: " -- Select Campaign(s) --",
    });

    $("#user_list").select2({
        placeholder: " -- Select User(s) --",
    });

});


$(function (){

    CAMPAIGN_TABLE = $('#table-campaigns').DataTable({
        "lengthMenu": [ [500,400,300,200,100,-1], [500,400,300,200,100,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": URL + '/manager/campaign/get-campaigns',
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
                data: 'campaign_id'
            },
            {
                render: function (data, type, row) {
                    return '<a href="'+URL+'/manager/campaign-assign/view-details/'+btoa(row.id)+'" class="text-dark double-click" title="View campaign details">'+row.name+'</a>';
                }
            },
            {
                render: function (data, type, row) {
                    let deliver_count = row.deliver_count;
                    let allocation = row.allocation;

                    if(row.children.length) {
                        $.each(row.children, function (key, value) {
                            allocation = allocation + value.allocation;
                            deliver_count = deliver_count + value.deliver_count;
                        });
                    }

                    let percentage = (deliver_count/allocation)*100;
                    percentage = percentage.toFixed(2);
                    return '<div class="progress" style="height: 20px;width:100px;border:1px solid lightgrey;"><div class="progress-bar '+ (parseInt(percentage) < 100 ? 'bg-warning text-dark' : 'bg-success text-light' ) +'" role="progressbar" aria-valuenow="'+percentage+'" aria-valuemin="0" aria-valuemax="100" style="width: '+percentage+'%;font-weight:bold;">&nbsp;'+percentage+'%</div></div>';
                }
            },
            {
                render: function (data, type, row) {
                    let date = new Date(row.start_date);
                    return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+MONTHS[date.getMonth()]+'/'+date.getFullYear();
                }
            },
            {
                render: function (data, type, row) {
                    let date = new Date(row.end_date);
                    if(row.children.length) {
                        date = new Date(row.children[0].end_date);
                    }
                    return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+MONTHS[date.getMonth()]+'/'+date.getFullYear();
                }
            },
            {
                render: function (data, type, row) {
                    let deliver_count = row.deliver_count;
                    let allocation = row.allocation;
                    let shortfall_count = row.shortfall_count;

                    if(row.children.length) {
                        $.each(row.children, function (key, value) {
                            allocation = allocation + value.allocation;
                            deliver_count = deliver_count + value.deliver_count;
                            if(value.campaign_status_id === 6) {
                                shortfall_count = value.shortfall_count;
                            }
                        });
                    }

                    if(shortfall_count) {
                        return deliver_count + ' <span class="text-danger" title="Shortfall Count">('+ shortfall_count +')</span>'+' / '+ allocation;
                    } else {
                        return deliver_count + ' / '+ allocation;
                    }

                }
            },
            {
                render: function (data, type, row) {
                    let status_id  = row.campaign_status_id;
                    let campaign_type = '';
                    if(row.children.length) {
                        status_id = row.children[0].campaign_status_id;
                        campaign_type = ' (Incremental)'
                    }
                    switch (status_id) {
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

                    html += '<a href="'+URL+'/manager/campaign-assign/view-details/'+btoa(row.id)+'" class="btn btn-outline-info btn-rounded btn-sm" title="View Campaign Details"><i class="feather icon-eye mr-0"></i></a>';
                    //html += '<a href="'+URL+'/manager/campaign/edit/'+btoa(row.id)+'" class="btn btn-outline-dark btn-rounded btn-sm" title="Edit Campaign Details"><i class="feather icon-edit mr-0"></i></a>';
                    //html += '<div id="toolbar-options-'+row.id+'" class="hidden">';
                    //html += '<a href="javascript:;" onclick="window.location.href=\''+URL+'/manager/campaign/view-deatails/'+btoa(row.id)+'\'"><i class="feather icon-eye"></i></a>';
                    //html += '<a href="javascript:;" onclick="deleteCampaign('+row.id+')"><i class="feather icon-trash-2"></i></a>';
                    //html += '</div>';

                    //html += '<div data-toolbar="campaign-options" class="btn-toolbar btn-dark btn-toolbar-dark dark-left-toolbar" id="dark-left-toolbar-'+row.id+'" data-id="'+row.id+'"><i class="feather icon-settings"></i></div>';

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
            switch (data.campaign_status_id) {
                case 1:
                    $(row).addClass('border-live');
                    break;
                case 2:
                    $(row).addClass('border-paused');
                    break;
            }
            /*if( data[2] ==  `someVal`){
                $(row).addClass('redClass');
            }*/
        }
    });

    $('#button-campaign-assign').on('click', function(e) {
        let campaign_list = $("#campaign_list").val();
        let user_list = $("#user_list").val();
        let html = '';

        $("#modal-campaign-assign").find('.modal-body').html(html);

        $.each(campaign_list, function (key, value){
            let display_date = new Date($("#campaign_list_"+value).data('end-date'));
            let tempDate = new Date($("#campaign_list_"+value).data('end-date'));



        });

        $("#modal-campaign-assign").find('.modal-body').html(html);

        $("#modal-campaign-assign").modal('show');
    });

    $("#button-reset-form-campaign-assign").on('click', function(){
        $("#form-campaign-assign").find('input').val('');
        $("#form-campaign-assign").find('select').val('').trigger('change');
    });

});


function getCampaignCard_html(key, value) {
    let html ='';

    html += '' +
        '<div class="card border border-info rounded">' +
        '   <h5 class="card-header" style="padding: 10px 25px;">'+$("#campaign_list_"+value).data('name')+'</h5>' +
        '   <input type="hidden" name="data['+key+'][campaign_id]" value="'+value+'">' +
        '   <div class="card-body" style="padding: 15px 25px;">' +
        '       <div class="row">' +
        '           <div class="col-md-5">' +
        '               <div class="row">' +
        '                   <div class="col-md-6"><h6 class="card-title">Allocation</h6></div>' +
        '                   <div class="col-md-6"><h6 class="card-title">: '+$("#campaign_list_"+value).data('allocation')+'</h6></div>' +
        '               </div>' +
        '               <div class="row">' +
        '                   <div class="col-md-6"><h6 class="card-title">End Date</h6></div>' +
        '                   <div class="col-md-6"><h6 class="card-title">: '+$("#campaign_list_"+value).data('end-date')+'</h6></div>' +
        '               </div>' +
        '               <div class="row">' +
        '                   <div class="col-md-6"><h6 class="card-title">Display Date</h6></div>' +
        '                   <div class="col-md-6"><h6 class="card-title">: <input type="date" name="data['+key+'][display_date]" placeholder="DD/MMM/YYY" value="'+tempDate+'"> </h6></div>' +
        '               </div>' +
        '           </div>' +
        '           <div class="col-md-7 border-left">' +
        '               <h5 class="card-title mb-2">User(s) to Assign</h5>' +
        '               <hr class="m-0" style="margin-bottom: 5px !important;">' +
        '           </div>' +
        '       </div>' +
        '   </div>' +
        '</div>';

    return html;
}

function getUserAssignCard_html() {
    let html = '';

    html = '<div class="row p-1">' +
        '<div class="col-md-5"><h6 class="card-title">'+$("#user_list_"+user_id).data('name')+'</h6></div>' +
        '<input type="hidden" name="data['+key+'][users]['+key2+'][user_id]" value="'+user_id+'">' +
        '<div class="col-md-7">' +
        '<input type="text" name="data['+key+'][users]['+key2+'][allocation]" class="form-control form-control-sm" value="'+Math.floor(divide)+'" style="height: 30px;">' +
        '</div>' +
        '</div>';

    return html;
}
