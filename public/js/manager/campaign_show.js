

//Initializations
$(function(){
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

    $('#modal-form-attach-specification-submit').on('click', function (e) {
        e.preventDefault();
        let form_data = new FormData($('#modal-form-attach-specification')[0]);

        $.ajax({
            url: $('meta[name="base-path"]').attr('content') +'/manager/campaign/attach-specification/'+$('#campaign_id').val(),
            processData: false,
            contentType: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if(response.status === true) {
                    //If no specification remove all list
                    if($('.specification-li').length === 0) {
                        $('#specification_ul').html('');
                    }
                    let html = '';
                    $.each(response.data, function(key, value){
                        html += '<li class="media d-flex m-b-15 specification-li">\n\
                                    <div class="m-r-20 file-attach">\n\
                                        <i class="far fa-file f-28 text-muted"></i>\n\
                                    </div>\n\
                                    <div class="media-body">\n\
                                        <a href="'+ $('meta[name="base-path"]').attr('content') + '/public/storage/campaigns/'+$('#campaign_campaign_id').val()+value.file_name+'" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Click to view"><span class="m-b-5 d-block text-primary">'+value.file_name+'</span></a>\n\
                                    </div>\n\
                                    <div class="float-right text-muted">\n\
                                        <a href="javascript:void(0);" onclick="removeSpecification(this, \''+btoa(value.id)+'\');"><i class="fas fa-times f-24 text-danger"></i></a>\n\
                                    </div>\n\
                                </li>';
                    });
                    $('#specification_ul').append(html);
                    $('#modal-attach-specification').modal('hide');
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Error while processing request', response.message);
                }
            }
        });

    });

});

function editCampaignDetails()
{
    $('#country_id').select2({
        placeholder: " -- Select Country(s) --",
        dropdownParent: $('#modal-edit-campaign-details')
    });
    $('#region_id').select2({
        placeholder: " -- Region(s) --",
        dropdownParent: $('#modal-edit-campaign-details')
    });
    $('#modal-edit-campaign-details').modal('show');
}

function editPacingDetails(id)
{
    if(confirm("Are you sure to edit pacing details?")) {
        $.ajax({
            type: 'get',
            url: $('meta[name="base-path"]').attr('content') + '/manager/campaign/edit-pacing-details/' + id,
            success: function (response){
                if(response.status === true) {
                    $('#start_date').bootstrapMaterialDatePicker('setDate', new Date(response.data.start_date));
                    $('#end_date').bootstrapMaterialDatePicker('setMinDate', new Date(response.data.start_date));
                    $('#end_date').bootstrapMaterialDatePicker('setDate', new Date(response.data.end_date));
                    $('#allocation').val(response.data.allocation);
                    $('#deliver_count').val(response.data.deliver_count);
                    $('#campaign_status_id').val(response.data.campaign_status_id);

                    if(response.data.campaign_status_id === 6) {
                        $("#div-shortfall-count").show();
                        $("#shortfall_count").removeAttr('disabled');
                        $('#shortfall_count').val(response.data.shortfall_count);
                    } else {
                        $("#div-shortfall-count").hide();
                        $("#shortfall_count").attr('disabled','disabled');
                        $('#shortfall_count').val(0);
                    }
                    $("#modal-edit-pacing-details").modal('show');
                    //trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Error while processing request', response.message);
                }
            }
        });
    }
}

function editSubAllocations(id)
{

}

function removeSpecification(_this, specification_id) {
    if(confirm("Are you sure to remove specification?")) {
        $.ajax({
            url: $('meta[name="base-path"]').attr('content') + '/manager/campaign/remove-specification/' + specification_id,
            success: function (response){

                if(response.status === true) {
                    //remove specification from list
                    $(_this).parents('.specification-li').remove();

                    if($('.specification-li').length === 0) {
                        var html = '<li class="media d-flex m-b-15"> <a href="javascript:void(0);" class="m-b-5 d-block text-warning">No File Attached</a> </div> </li>';
                        $('#specification_ul').html(html);
                    }
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Error while processing request', response.message);
                }
            }
        });
    }
}

