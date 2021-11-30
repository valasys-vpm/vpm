/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let CAMPAIGN_ISSUE_TABLE;
let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

$(function (){

    CAMPAIGN_ISSUE_TABLE = $('#table-campaign-issues').DataTable({
        "lengthMenu": [ [5,500,400,300,200,100,-1], [5,500,400,300,200,100,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": URL + '/manager/campaign-issue/get-issues',
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
                render: function (data, type, row) {
                    return '<a href="'+URL+'/manager/campaign/view-details/'+btoa(row.campaign.id)+'" class="text-dark double-click" title="' + row.campaign.name + '">'+row.campaign.campaign_id+'</a>';
                }
            },
            {
                render: function (data, type, row) {
                    switch (row.priority) {
                        case 'low': return '<span class="badge badge-pill badge-info" style="padding: 5px;min-width:50px;"> Low</span>';
                        case 'normal': return '<span class="badge badge-pill badge-warning" style="padding: 5px;min-width:50px;"> Normal</span>';
                        case 'high': return '<span class="badge badge-pill badge-danger" style="padding: 5px;min-width:50px;"> High</span>';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    switch (parseInt(row.status)) {
                        case 1: return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;"> Closed</span>';
                        case 0: return '<span class="badge badge-pill badge-warning" style="padding: 5px;min-width:50px;"> Open</span>';
                    }
                }
            },
            {
                data: 'title'
            },
            {
                data: 'description'
            },
            {
                data: 'user.full_name'
            },
            {
                render: function (data, type, row) {
                    let date = new Date(row.created_at);
                    return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+MONTHS[date.getMonth()]+'/'+date.getFullYear();
                }
            },
            {
                render: function (data, type, row) {
                    if (row.closed_by) {
                        return row.closed_by_user.full_name;
                    } else {
                        return ' - ';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    if (row.closed_by) {
                        let date = new Date(row.updated_at);
                        return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+MONTHS[date.getMonth()]+'/'+date.getFullYear();
                    } else {
                        return ' - ';
                    }
                }
            },
            {
                orderable: false,
                render: function (data, type, row) {
                    let html = '';
                    html += '<a href="javascript:void(0);" onclick="closeCampaignIssue(\''+btoa(row.id)+'\');" class="btn btn-outline-info btn-rounded btn-sm" title="Close Issue"><i class="feather icon-edit mr-0"></i></a>';
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

        },
        order:[]
    });

    $('#modal-form-button-submit').on('click', function (e) {
        e.preventDefault();
        if($("#modal-campaign-form").valid()) {
            let url = '';
            if($(this).text() === 'Save') {
                url = URL + '/manager/campaign/store';
            } else if ($(this).text() === 'Update') {
                url = URL + '/manager/campaign/update/'+$('#campaign_id').val();
            } else {
                resetModalForm();
                trigger_pnofify('error', 'Something went wrong', 'Please try again');
            }

            $.ajax({
                type: 'post',
                url: url,
                data: $('#modal-campaign-form').serialize(),
                success: function (response) {
                    if(response.status === true) {
                        resetModalForm();
                        $('#modalCampaign').modal('hide');
                        trigger_pnofify('success', 'Successful', response.message);
                    } else {
                        trigger_pnofify('error', 'Something went wrong', response.message);
                    }
                    CAMPAIGN_ISSUE_TABLE.ajax.reload();
                }
            });

        } else {
        }
    });

    $('#form-close-issue-submit').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: URL + '/manager/campaign-issue/update/' + $('#modal-close-issue').find('input[name="id"]').val(),
            data: $('#form-close-issue').serialize(),
            async : true,
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                    $('#modal-close-issue').modal('hide');
                    CAMPAIGN_ISSUE_TABLE.ajax.reload();
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    });

});

function editCampaign(id)
{
    $.ajax({
        type: 'post',
        url: URL + '/manager/campaign/edit/'+btoa(id),
        dataType: 'json',
        success: function (response) {
            resetModalForm();
            if(response.status === true) {
                $('#modal-heading').text('Edit campaign');
                $('#modal-form-button-submit').text('Update');

                $('#campaign_id').val(btoa(response.data.id));

                $('#designation_id').val(response.data.designation_id);

                $('#status').val(response.data.status);

                $('#modalCampaign').modal('show');
            } else {
                trigger_pnofify('error', 'Something went wrong', response.message);
            }
        }
    });
}

function deleteCampaign(id)
{
    if(confirm('Are you sure to delete this campaign?')) {
        $.ajax({
            type: 'post',
            url: URL + '/manager/campaign/destroy/'+btoa(id),
            dataType: 'json',
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                CAMPAIGN_ISSUE_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

function closeCampaignIssue(_issue_id) {
    $('#modal-close-issue').find('input[name="id"]').val(_issue_id);
    $('#modal-close-issue').modal('show');
}
