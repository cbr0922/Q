$(document).ready(function() {
    $('#regform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            realname: {
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
			truename: {
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
			sex: {
                validators: {
                    notEmpty: {
                        message: '請選擇性別'
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
			old_pwd2: {
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
			f_pwd20: {
                validators: {
                    notEmpty: {
                        message: '請輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                    identical: {
                        field: 's_pwd2',
                        message: '密碼與再次輸入密碼不相同'
                    },
                }
            },
			s_pwd2: {
                validators: {
                    notEmpty: {
                        message: '請輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                    identical: {
                        field: 'f_pwd20',
                        message: '密碼與再次輸入密碼不相同'
                    },
                }
            },
			password: {
                validators: {
                    notEmpty: {
                        message: '請輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                    identical: {
                        field: 'passwd2',
                        message: '密碼與再次輸入密碼不相同'
                    },
                }
            },
            passwd2: {
                validators: {
                    notEmpty: {
                        message: '請輸入再次輸入密碼，不可空白'
                    },
					stringLength: {
                        min: 6,
						message: '請輸入至少6個數字'
                    },
                    identical: {
                        field: 'password',
                        message: '密碼與再次輸入密碼不相同'
                    },
                }
            },
			mobile: {
                validators: {
                    notEmpty: {
                        message: '請輸入手機，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: '請輸入市話，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
			county: {
                validators: {
                    notEmpty: {
                        message: '請選擇'
                    }
                }
            },
			province: {
                validators: {
                    notEmpty: {
                        message: '請選擇城市'
                    }
                }
            },
			city: {
                validators: {
                    notEmpty: {
                        message: '請選擇地區'
                    }
                }
            },
			/*othercity: {
                validators: {
                    notEmpty: {
                        message: '請輸入郵地區號'
                    }
                }
            },*/
            address: {
                validators: {
                     stringLength: {
                        min: 4,
						message: '請輸入至少4個字數'
                    },
                    notEmpty: {
                        message: '請輸入地址，不可空白'
                    }
                }
            },
            byear: {
                validators: {
                    notEmpty: {
                        message: '請選擇年份'
                    }
                }
            },
			bmonth: {
                validators: {
                    notEmpty: {
                        message: '請選擇月份'
                    }
                }
            },
			bday: {
                validators: {
                    notEmpty: {
                        message: '請選擇日期'
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
            isok: {
                validators: {
                    notEmpty: {
                        message: '請勾選同意會員條款'
                    }
                }
            },
		}

        })
});