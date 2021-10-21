/* ------------------------------------
    Campaign Create Custom Javascript
------------------------------------ */
//Declarations
let URL = $('meta[name="base-path"]').attr('content');

// Initializations
$(function (){

    $('body').on("input", ".only-non-zero-number", function (){
        if(this.value < 1) {
            $(this).val('');
        } else {
            $(this).val(parseInt(this.value));
        }
    });

});


$(function (){

    //Validator Function nonEmptyValue
    jQuery.validator.addMethod("non_empty_value", function(value, element) {

        if(value.length>0) {
            if(value.trim().length>0) {
                $(element).val(value.trim())
                return true;
            } else {
                $(element).val('');
                return false;
            }
        } else {
            return true;
        }
    }, "Please enter data");

    //Validate Form
    $("#form-lead-create").validate({
        ignore: [],
        focusInvalid: false,
        rules: {
            'first_name' : { required : true,non_empty_value: true },
            'last_name' : { required : true,non_empty_value: true },
            'company_name' : { required : true,non_empty_value: true },
            'email_address' : { required : true,non_empty_value: true },
            'specific_title' : { required : true,non_empty_value: true },
            'phone_number' : { required : true,non_empty_value: true },
            'address_1' : { required : true,non_empty_value: true },
            'city' : { required : true,non_empty_value: true },
            'state' : { required : true,non_empty_value: true },
            'zipcode' : { required : true,non_empty_value: true },
            'country' : { required : true,non_empty_value: true },
            'employee_size' : { required : true,non_empty_value: true },
            'revenue' : { required : true,non_empty_value: true },
            'company_domain' : { required : true,non_empty_value: true },
            'company_linkedin_url' : { required : true,non_empty_value: true },
            'linkedin_profile_link' : { required : true,non_empty_value: true },

        },
        messages: {
            'first_name' : { required : "Please enter first name" },
            'last_name' : { required : "Please enter last name" },
            'company_name' : { required : "Please enter company name" },
            'email_address' : { required : "Please enter email address" },
            'specific_title' : { required : "Please enter title" },
            'phone_number' : { required : "Please enter phone number" },
            'address_1' : { required : "Please enter address" },
            'city' : { required : "Please enter city" },
            'state' : { required : "Please enter state" },
            'zipcode' : { required : "Please enter zipcode" },
            'country' : { required : "Please enter country" },
            'employee_size' : { required : "Please enter size" },
            'revenue' : { required : "Please enter revenue" },
            'company_domain' : { required : "Please enter company domain" },
            'company_linkedin_url' : { required : "Please enter url" },
            'linkedin_profile_link' : { required : "Please enter link" },

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
