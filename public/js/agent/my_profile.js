/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let URL = $('meta[name="base-path"]').attr('content');

$(function(){
    //Validate Form
    $("#form-upload-profile").validate({
        ignore: [],
        focusInvalid: false,
        rules: {
            'profile' : {
                required : true,
                extension: "png|jpe?g",
                filesize: 256000
            },
        },
        messages: {
            'profile' : {
                required : "Please upload file",
                extension: "Please upload valid file, [png, jpg, jpeg]",
                filesize: "File size should be less than 256 kb"
            },
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
            $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
        }
    });

    $("#form-profile-edit").validate({
        focusInvalid: false,
        rules: {
            'first_name' : { required : true },
            'last_name' : { required : true }
        },
        messages: {
            'first_name' : { required: "Please enter first name" },
            'last_name' : { required: "Please enter last name" }
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
            $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
        }
    });

    $.validator.addMethod(
        "regex",
        function(value, element) {
            var re = new RegExp("^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$");
            return this.optional(element) || re.test(value);
        },
        "The password must contain a minimum of 1 lower case character, 1 upper case character, 1 digit and 1 special character"
    );

    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    });

    $("#form-change-password").validate({
        focusInvalid: false,
        rules: {
            password : {
                required: true,
                minlength : 8,
                regex: true
            },
            confirm_password : {
                required: true,
                equalTo : "#password"
            }
        },
        messages: {
            'password' : {
                required: "Please enter password.",
                minlength: "The new password must be at least 8 characters long.",
            },
            'confirm_password' : {
                required: "Please confirm your password.",
                equalTo: "Confirm password should be same as new password.",
            }
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
            $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
        }
    });
});

$(function (){

    $('#form-profile-edit-submit').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: URL + '/agent/user/update',
            data: $('#form-profile-edit').serialize(),
            async : true,
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                    window.location.reload();
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
            }
        });
    });

    $('#form-submit-upload-profile').on('click', function (e) {
        e.preventDefault();
        if($("#form-upload-profile").valid()) {
            let form_data = new FormData($('#form-upload-profile')[0]);
            $.ajax({
                type: 'post',
                url: URL + '/agent/user/update-profile',
                processData: false,
                contentType: false,
                data: form_data,
                success: function (response) {
                    if(response.status === true) {
                        trigger_pnofify('success', 'Successful', response.message);
                        window.location.reload();
                    } else {
                        trigger_pnofify('error', 'Something went wrong', response.message);
                    }
                }
            });
        }
    });

});

function showPassword(id, ele)
{
    if($("#"+id).attr('type') == 'text') {
        $("#"+id).attr('type', 'password');
        $(ele).removeClass('icon-eye').addClass('icon-eye-off');
    } else {
        $("#"+id).attr('type', 'text');
        $(ele).removeClass('icon-eye-off').addClass('icon-eye');
    }
}
