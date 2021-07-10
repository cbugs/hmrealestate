document.addEventListener('DOMContentLoaded', function(){
    window.$ = window.jQuery;
    'use strict';

    $(function () {

        let form = $('.bootstrap-form');

        // On form submit take action, like an AJAX call
        $(document).on("submit", '.bootstrap-form', function (e) {

            form = $(this);

            var submitbtn = $(form.find("input[type='submit']"));
            

            var formValid = true;

            if (this.checkValidity() == false || formValid == false) {
                $(this).addClass('was-validated');
                e.preventDefault();
                e.stopPropagation();
            } else {
                if ($(this).hasClass('ajax')) {
                    e.preventDefault();
                    e.stopPropagation();

                    var url = form.attr('action');

                    $(form.find('.alert-success')[0]).addClass("d-none");
                    $(form.find('.alert-danger')[0]).addClass("d-none");
                    submitbtn.attr('disabled',true);
                    $.ajax({
                           type: form.attr('method'),
                           url: url,
                           data: form.serialize(), // serializes the form's elements.
                           success: function(data)
                           {
                                $(form.find('.alert-success')[0]).removeClass("d-none");
                                $(form.find('.alert-success')[0]).text(data.message);
          
                                document.getElementById(form.attr("id")).reset();

                                if(data.redirect !== undefined){
                                    window.location = data.redirect;
                                }

                                submitbtn.attr('disabled',false);
                                $($(form).attr("id")+' :input, :checked').removeClass('is-valid is-invalid');
                                $(form).removeClass('was-validated');
                           },
                           error: function(data)
                           {
                                $(form.find('.alert-danger')[0]).removeClass("d-none");
                                $(form.find('.alert-danger')[0]).text(data.responseJSON.message);

                                submitbtn.attr('disabled',false);
                                $($(form).attr("id")+' :input, :checked').removeClass('is-valid is-invalid');
                                $(form).removeClass('was-validated');
                           }
                         });

                } else if ($(this).hasClass('normal')) {

                } else {
                    
                }
            }


            // postHeight();

            // setTimeout(() => {
            //     postHeight();
            // }, 100);

        });

        // On every :input focusout validate if empty
        $(':input').on("blur", function () {
            let fieldType = this.type;

            switch (fieldType) {
                case 'text':
                case 'password':
                case 'textarea':
                case 'hidden':
                    validateText($(this));
                    break;
                case 'email':
                    validateEmail($(this));
                    break;
                case 'checkbox':
                    validateCheckBox($(this));
                    break;
                case 'select-one':
                    validateSelectOne($(this));
                    break;
                case 'select-multiple':
                    validateSelectMultiple($(this));
                    break;
                default:
                    break;
            }
        });


        // On every :input focusin remove existing validation messages if any
        $(':input').on("click", function () {

            $(this).removeClass('is-valid is-invalid');

        });

        // On every :input focusin remove existing validation messages if any
        $(':input').on("keydown", function () {

            $(this).removeClass('is-valid is-invalid');

        });

        // Reset form and remove validation messages
        $(':reset').on("click", function () {
            $(':input, :checked').removeClass('is-valid is-invalid');
            $(form).removeClass('was-validated');
        });

    });

    // Validate Text and password
    function validateText(thisObj) {
        let fieldValue = thisObj.val();
        if (fieldValue.length > 1) {
            $(thisObj).addClass('is-valid');
        } else {
            $(thisObj).addClass('is-invalid');
        }
    }

    // Validate Email
    function validateEmail(thisObj) {
        let fieldValue = thisObj.val();
        let pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;

        if (pattern.test(fieldValue)) {
            $(thisObj).addClass('is-valid');
        } else {
            $(thisObj).addClass('is-invalid');
        }
    }

    // Validate CheckBox
    function validateCheckBox(thisObj) {

        if ($(':checkbox:checked').length > 0) {
            $(thisObj).addClass('is-valid');
        } else {
            $(thisObj).addClass('is-invalid');
        }
    }

    // Validate Select One Tag
    function validateSelectOne(thisObj) {

        let fieldValue = thisObj.val();

        if (fieldValue != null) {
            $(thisObj).addClass('is-valid');
        } else {
            $(thisObj).addClass('is-invalid');
        }
    }

    // Validate Select Multiple Tag
    function validateSelectMultiple(thisObj) {

        let fieldValue = thisObj.val();

        if (fieldValue.length > 0) {
            $(thisObj).addClass('is-valid');
        } else {
            $(thisObj).addClass('is-invalid');
        }
    }

}, false);