
let CAMPAIGN_TABLE;
let URL = $('meta[name="base-path"]').attr('content');

$(function (){

    CAMPAIGN_TABLE = $('#table-campaigns').DataTable({
        "lengthMenu": [ [10,20,50,100,'all'], [10,20,50,100,'All'] ],
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
                render: function (data, type, row) {
                    if(row.logged_on) {
                        return '<i class="fas fa-circle text-c-green m-r-15" style="font-size: 20px;" title="Online" data-toggle="tooltip" data-placement="top"></i>';
                    } else {
                        return '<i class="fas fa-circle text-c-red m-r-15" style="font-size: 20px;" title="Offline" data-toggle="tooltip" data-placement="top"></i>';
                    }
                }
            },
            {
                data: 'employee_code',
            },
            {
                data: 'first_name',
            },
            {
                data: 'email',
            },
            {
                data: 'role.name',
            },
            {
                data: 'department.name',
            },
            {
                data: 'designation.name',
            },
            {
                render: function (data, type, row) {
                    switch (row.status) {
                        case 1: return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;">Active</span>';
                        case 0: return '<span class="badge badge-pill badge-danger" style="padding: 5px;min-width:50px;">Inactive</span>';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    return moment(row.created_at).format('DD MMM, YYYY [at] HH:mm A');
                }
            },
            {
                render: function (data, type, row) {
                    return moment(row.updated_at).format('DD MMM, YYYY [at] HH:mm A');
                }
            },
            {
                orderable: false,
                render: function (data, type, row) {
                    let html = '';

                    html += '<div id="toolbar-options-'+row.id+'" class="hidden">';
                    html += '<a href="javascript:;" onclick="editCampaign('+row.id+')"><i class="feather icon-edit"></i></a>';
                    html += '<a href="javascript:;" onclick="deleteCampaign('+row.id+')"><i class="feather icon-trash-2"></i></a>';
                    html += '</div>';

                    html += '<div data-toolbar="campaign-options" class="btn-toolbar btn-dark btn-toolbar-dark dark-left-toolbar" id="dark-left-toolbar-'+row.id+'" data-id="'+row.id+'"><i class="feather icon-settings"></i></div>';

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
        },
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
