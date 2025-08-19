<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for any select elements with the class 'select2'
        $('.select2').select2();

        $.validator.addMethod("alphabetsOnly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        }, "Please enter letters only");

        $.validator.addMethod("customEmail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value);
        }, "Please enter a valid email address");

        //jquery validation for the form
        $('#adminForm').validate({
            ignore: [],
            rules: {
                first_name: {
                    required: true,
                    minlength: 3,
                    alphabetsOnly: true
                },
                last_name: {
                    required: true,
                    minlength: 3,
                    alphabetsOnly: true
                },
                email: {
                    required: true,
                    email: true,
                    customEmail: true
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 7,
                    maxlength: 15,
                }
            },
            messages: {
                first_name: {
                    required: "Please enter a first name",
                    minlength: "First name must be at least 3 characters long"
                },
                last_name: {
                    required: "Please enter a last name",
                    minlength: "Last name must be at least 3 characters long"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter a valid email address"
                },
                mobile: {
                    required: "Please enter mobile no.",
                    digits: "Please enter a valid mobile number",
                    minlength: "Mobile number must be at least 7 digits long",
                    maxlength: "Mobile number must not exceed 15 digits"
                }
            },
            submitHandler: function(form) {
                const $btn = $('#saveBtn');
                const isUpdate = $btn.text().trim().toLowerCase() === 'update';
                $btn.prop('disabled', true).text(isUpdate ? 'Updating...' : 'Saving...');
                form.submit();
            },

            errorElement: 'div',
            errorClass: 'text-danger custom-error',
            errorPlacement: function(error, element) {
                $('.validation-error').hide(); // hide blade errors
                error.insertAfter(element);
            }
        });
    });
</script>