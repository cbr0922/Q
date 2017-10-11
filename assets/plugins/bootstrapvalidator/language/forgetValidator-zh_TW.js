$(document).ready(function() {
    $('#forgetform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: '請輸入Email，不可空白'
                    },
                    emailAddress: {
                        message: 'Email無效'
                    },
					          remote: {
                        type: 'POST',
                        url: '../member/member.php?checktype=username&action=CheckExit3',
                        message: '此Email帳號不存在',
                        delay: 500
                    }
                }
            },
            inputcode: {
                validators: {
                      stringLength: {
                        min: 5,
                        max: 5,
                        message:'請輸入5個字數'
                    },
                    notEmpty: {
                        message: '請輸入驗證碼，不可空白'
                    }
                }
            },
		}

        })
});
