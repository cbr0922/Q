$(document).ready(function() {
    $('#regform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			company: {
                validators: {
                    notEmpty: {
                        message: '請輸入公司名稱，不可空白'
                    }
                }
            },
            username: {
                validators: {
                        stringLength: {
                        min: 2,
						message: '請輸入至少2個字數'
                    },
                        notEmpty: {
                        message: '請輸入真實姓名，不可空白'
                    }
                }
            },
			companyname: {
                validators: {
                        stringLength: {
                        min: 2,
						message: '請輸入真實姓名，不可空白'
                    },
                        notEmpty: {
                        message: '請輸入真實姓名，不可空白'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: '請輸入Email，不可空白'
                    },
                    emailAddress: {
                        message: 'Email無效'
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: '請輸入電話，不可空白'
                    },
                }
            },
			tel_two: {
                validators: {
                    notEmpty: {
                        message: '請輸入電話，不可空白'
                    },
                }
            },
			mobile:	{
                validators: {
                    notEmpty: {
                        message: '請輸入手機，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
			content: {
                validators: {
                     stringLength: {
                        min: 4,
						message: '請填寫聯絡內容，不可少於4個字'
                    },
                    notEmpty: {
                        message: '請填寫聯絡內容'
                    }
                }
            },
			
			remark: {
                validators: {
                     stringLength: {
                        min: 4,
						message: '請輸入至少4個數字'
                    },
                    notEmpty: {
                        message: '請輸入備註內容'
                    }
                }
            },
			type: {
                validators: {
                    notEmpty: {
                        message: '請選擇聯絡事項'
                    }
                }
            },
			needcount: {
                validators: {
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: '請填寫數字'
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
            }
			
		}

        })
});