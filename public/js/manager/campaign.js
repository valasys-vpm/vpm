/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let CAMPAIGN_TABLE;
let URL = $('meta[name="base-path"]').attr('content');
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
                        start_date: $("#filter_start_date").val(),
                        end_date: $("#filter_end_date").val(),
                        campaign_status_id: $("#filter_campaign_status_id").val(),
                        delivery_day: $("#filter_delivery_day").val(),
                        due_in: $("#filter_due_in").val(),
                        country_id: $("#filter_country_id").val(),
                        region_id: $("#filter_region_id").val(),
                        campaign_type_id: $("#filter_campaign_type_id").val(),
                        campaign_filter_id: $("#filter_campaign_filter_id").val()
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
                    return '<a href="'+URL+'/manager/campaign/view-details/'+btoa(row.id)+'" class="text-dark double-click" title="View campaign details">'+row.name+'</a>';
                }
            },
            {
                orderable: false,
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

                    html += '<a href="'+URL+'/manager/campaign/view-details/'+btoa(row.id)+'" class="btn btn-outline-info btn-rounded btn-sm" title="View Campaign Details"><i class="feather icon-eye mr-0"></i></a>';
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
            let status_id  = data.campaign_status_id;
            if(data.children.length) {
                status_id = data.children[0].campaign_status_id;
            }
            switch (status_id) {
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
                    CAMPAIGN_TABLE.ajax.reload();
                }
            });

        } else {
        }
    });

    jQuery.validator.addMethod("nonEmptyValue", function(value, element) {
        if(value.length>0) {
            if(value.trim().length>0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }, "Please enter valid data");

    $("#modal-campaign-form").validate({
        focusInvalid: false,
        rules: {
            'first_name' : {
                required : true,
                nonEmptyValue: true
            },
            'middle_name' : {
                nonEmptyValue: true
            },
            'last_name' : {
                required : true,
                nonEmptyValue: true
            },
            'employee_code' : {
                required : true,
                remote : {
                    url : URL + '/manager/campaign/validate-employee-code',
                    data : {
                        employee_code : function(){
                            return $("#employee_code").val();
                        },
                        user_id : function(){
                            if($('#user_id').val() === '') {
                                return '';
                            } else {
                                return $('#user_id').val();
                            }
                        }
                    }
                }
            },
            'email' : {
                required : true,
                email : true,
                remote : {
                    url : URL + '/admin/user/validate-email',
                    data : {
                        email : function(){
                            return $("#email").val();
                        },
                        user_id : function(){
                            if($('#user_id').val() === '') {
                                return '';
                            } else {
                                return $('#user_id').val();
                            }
                        }
                    }
                }
            },
            'reporting_user_id' : { required : true },
            'role_id' : { required : true },
            'department_id' : { required : true },
            'designation_id' : { required : true },
            'status' : { required : true }
        },
        messages: {
            'first_name' : { required: "Please enter first name" },
            'last_name' : { required: "Please enter last name" },
            'employee_code' : {
                required: "Please enter employee code",
                remote: "Employee code already exists"
            },
            'email' : {
                required: "Please enter email",
                remote: "Email already exists"
            },
            'reporting_user_id' : { required : "Please select reporting user" },
            'role_id' : { required: "Please select role" },
            'department_id' : { required: "Please select department" },
            'designation_id' : { required: "Please select designation" }
        },
        errorPlacement: function errorPlacement(error, element) {
            var $parent = $(element).parents('.form-group');

            // Do not duplicate errors
            if ($parent.find('.jquery-validation-error').length) {
                return;
            }

            $parent.append(
                error.addClass('jquery-validation-error small form-text invalid-feedback')
            );
        },
        highlight: function(element) {
            var $el = $(element);
            var $parent = $el.parents('.form-group');

            $el.addClass('is-invalid');

            // Select2 and Tagsinput
            if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                $el.parent().addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            if($(element).attr('aria-invalid') === 'false') {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        }
    });

    $('#form-import-campaigns-submit').on('click', function (e) {
        e.preventDefault();
        let form_data = new FormData($('#form-import-campaigns')[0]);
        let url = '';
        $.ajax({
            url: URL +'/manager/campaign/import',
            processData: false,
            contentType: false,
            data: form_data,
            type: 'post',
            xhr: function () {
                let xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 2) {
                        if (xhr.status === 201) {
                            xhr.responseType = "json";
                        } else {
                            xhr.responseType = "blob";
                        }
                    }
                };
                return xhr;
            },
            success: function(response, status, xhr) {
                if(xhr.status === 201) {
                    if(response.status === true) {
                        //$('#modal-import-campaigns').modal('hide');
                        trigger_pnofify('success', 'Successful', response.message);
                    } else {
                        trigger_pnofify('error', 'Error while processing request', response.message);
                    }
                } else {
                    let date = new Date();
                    let blob = new Blob([response], {type: '' + xhr.getResponseHeader("content-type")})
                    console.log(blob);
                    let a = $('<a />'), url = window.URL.createObjectURL(blob);
                    a.attr({
                        'href': url,
                        'download': 'Invalid_Campaigns_'+date.getTime()+'.xlsx',
                        'text': "click"
                    }).hide().appendTo("body")[0].click();
                    trigger_pnofify('warning', 'Invalid Data', 'Campaigns imported with errors, please check excel file to invalid data.');
                }
                $('#modal-import-campaigns').modal('hide');
                CAMPAIGN_TABLE.ajax.reload();
            }
        });

    });

});

function addCampaign()
{
    resetModalForm();
    $('#modalCampaign').modal('show');
}

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
                CAMPAIGN_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

function resetModalForm()
{
    $('#modal-campaign-form').find("input,textarea,select").val('').removeClass('is-invalid').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").removeClass('is-invalid').end();
    $('#modal-heading').text('Add new campaign');
    $('#modal-form-button-submit').text('Save');
    $('#status').val('1');
}
