
let MONTHS = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
let DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
let HOLIDAYS = [];
let HOLIDAY_LIST = [];

//Initializations
$(function(){

    $.ajax({
        url: $('meta[name="base-path"]').attr('content') + '/manager/holiday/get-holiday-list',
        type: "get",
        success: function(response) {
            if(response.status === true) {
                HOLIDAYS = response.data;
                $.each(HOLIDAYS, function (index, row) {
                    HOLIDAY_LIST.push(row.date);
                });
            } else {
                HOLIDAY_LIST = [];
            }
        }
    });

    // classic editor
    $(window).on('load', function() {
        // classic editor
        ClassicEditor.create(document.querySelector('.classic-editor')).catch(error => {
            console.error(error);
        });
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

    //Auto select regions
    $("#country_id").change(function () {
        let regions = [];
        $.each($(this).children('option:selected'), function () {
            regions.push($(this).data('region-id'));
        });
        $("#region_id").val(regions);
        $("#region_id").select2('destroy').select2({
            placeholder: " -- Region(s) --",
        });
    });

    $("#campaign_status_id").change(function (){
        if(parseInt($(this).val()) === 6) {
            $("#div-shortfall-count").show();
            $("#shortfall_count").removeAttr('disabled');
        } else {
            $("#div-shortfall-count").hide();
            $("#shortfall_count").attr('disabled','disabled');
        }
    });

    //Validate Form
    $("#modal-form-update-campaign-details").validate({
        focusInvalid: false,
        rules: {
            'name' : { required : true },
            'v_mail_campaign_id' : {
                required: false,
                remote : {
                    url : $('meta[name="base-path"]').attr('content')+'/manager/campaign/validate-v-mail-campaign-id',
                    data: { campaign_id : $('#campaign_id').val() }
                }
            },
            'campaign_filter_id' : { required : true },
            'campaign_type_id' : { required : true },
            'country_id[]' : { required : true },
        },
        messages: {
            'name' : { required : "Please enter campaign name" },
            'v_mail_campaign_id' : {
                remote : "V-Mail Campaign Id already exists"
            },
            'campaign_filter_id' : { required : "Please select campaign filter" },
            'campaign_type_id' : { required : "Please select campaign tye" },
            'country_id[]' : { required : "Please select country(s)" },
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

    //Initialize date picker for edit pacing details
    $('#start_date').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        format: 'D-MMM-YYYY',
        dropdownParent: $('#modal-edit-pacing-details'),
        switchOnClick : true,
    }).on('change', function(e, date) {
        $('#end_date').bootstrapMaterialDatePicker('setMinDate', date);
    });

    $('#end_date').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        format: 'D-MMM-YYYY',
        dropdownParent: $('#modal-edit-pacing-details'),
        switchOnClick : true,
    });

    //Update total sub-allocation count
    $('body').on('keyup', ".sub-allocation",function () {
        let total = 0;

        $('body').find('.sub-allocation').each(function(){
            if($(this).val() !== '') {
                total = total + parseInt($(this).val());
            }
        });
        total = (total > 0) ? total : 0;
        $("#total-sub-allocation").html(total);
        if(total > parseInt($("#campaign_allocation").val())) {
            $(this).val('');
            $(this).keyup();
        }
    });
});


$(function(){

    $('#modal-form-update-campaign-details-submit').on('click', function (e) {
        e.preventDefault();
        if($("#modal-form-update-campaign-details").valid()) {
            $.ajax({
                type: 'post',
                url: $('meta[name="base-path"]').attr('content') + '/manager/campaign/update/'+$('#campaign_id').val(),
                data: $('#modal-form-update-campaign-details').serialize(),
                success: function (response) {
                    console.log(response);
                    if(response.status === true) {
                        $('#modal-edit-campaign-details').modal('hide');
                        trigger_pnofify('success', 'Successful', response.message);
                        window.location.reload();
                    } else {
                        trigger_pnofify('error', 'Something went wrong', response.message);
                    }
                }
            });
        } else {
            trigger_pnofify('error', 'Invalid Data', 'Please enter valid details');
        }
    });

});

function viewAssignmentDetails(id) {
    $.ajax({
        type: 'get',
        url: $('meta[name="base-path"]').attr('content') + '/manager/campaign-assign/view-assignment-details/' + id,
        success: function (response){
            if(response.status === true) {
                console.log(response.data);
                let data = null;
                let html = '';

                if(response.data.resultRATLs.length) {
                    data = response.data.resultRATLs;
                }

                if(response.data.resultVMs.length) {
                    data = response.data.resultVMs;
                }

                $.each(data, function (key, value) {
                    html += '' +
                        '<tr>\n' +
                        '   <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;"></i></td>\n' +
                        '   <td>'+ value.user.first_name +' '+ value.user.last_name +'</td>\n' +
                        '   <td>'+ value.display_date +'</td>\n' +
                        '   <td>'+ value.allocation +'</td>\n' +
                        '   <td>'+ value.agents.length +'</td>\n' +
                        '   <td>'+ value.user_assigned_by.first_name +' '+ value.user_assigned_by.last_name +'</td>\n' +
                        '   <td></td>\n' +
                        '</tr>';
                        '';
                });

                $("#modal-view-assignment-details").find('tbody').html(html);

                $("#modal-view-assignment-details").modal('show');
            } else {
                trigger_pnofify('error', 'Error while processing request', response.message);
            }
        }
    });
}

function getRowUserAssigned_html(data) {
    let html = '';
    console.log(data);
    html = '' +
        '<tr>\n' +
        '   <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;"></i></td>\n' +
        '   <td>'+ data.user.first_name +' '+ data.user.last_name +'</td>\n' +
        '   <td>'+ data.display_date +'</td>\n' +
        '   <td>'+ data.allocation +'</td>\n' +
        '   <td>'+ data.agents.length +'</td>\n' +
        '   <td>'+ data.user_assigned_by.first_name +' '+ data.user_assigned_by.last_name +'</td>\n' +
        '   <td></td>\n' +
        '</tr>';

    return html;
}
