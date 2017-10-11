$(document).ready(function() {
    $('#changeform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			
			old_pwd: {
                validators: {
                    notEmpty: {
                        message: '請輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                }
            },
			f_pwd: {
                validators: {
                    notEmpty: {
                        message: '請輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                    /*identical: {
                        field: 's_pwd',
                        message: '密碼與再次輸入密碼不相同'
                    },*/
                }
            },
			s_pwd: {
                validators: {
                    notEmpty: {
                        message: '請輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                    identical: {
                        field: 'f_pwd',
                        message: '密碼與再次輸入密碼不相同'
                    },
                }
            },
		}

    })
});