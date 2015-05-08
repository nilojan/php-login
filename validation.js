$(document).ready(function () { // when DOM is ready
    $('#RegisterForm').validate({ // initialize the plugin
        rules: {
            username: {
                required: true,
                minlength: 4
            }
            emasil: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            }
        },
        submitHandler: function (form) {
            return true;
        }
    });

});