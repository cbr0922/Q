$(document).ready(function() {
    $('#regform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            /*realname: {
                validators: {
                        stringLength: {
                        min: 2,
						message: '請輸入至少2個字數'
                    },
                        notEmpty: {
                        message: '請輸入真實姓名，不可空白'
                    }
                }
            },*/
			en_firstname: {
                validators: {

                        notEmpty: {
                        message: '請輸入姓氏，不可空白'
                    }
                }
            },
			en_secondname: {
                validators: {

                        notEmpty: {
                        message: '請輸入名字，不可空白'
                    }
                }
            },
			certcode: {
                validators: {

                        notEmpty: {
                        message: '請輸入護照號碼，不可空白'
                    }
                }
            },
			bornCountry: {
                validators: {

                        notEmpty: {
                        message: '請選擇國籍/地區'
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
                    },
					remote: {
                        type: 'POST',
                        url: '../member/member.php?checktype=username&action=CheckExit',
                        message: 'email已經被註冊使用',
                        delay: 500
                    }
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
                }
            },
            passwd2: {
                validators: {
                    notEmpty: {
                        message: '請輸入再次輸入密碼，不可空白'
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
                    remote: {
                      type: 'POST',
                      url: '../member/member.php?checktype=mobile&action=CheckExit4',
                      message: '此手機號碼已經被註冊使用',
                      delay: 500
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
            /*phone: {
                validators: {
                    notEmpty: {
                        message: '請輸入市話，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*
                }
            },*/
			county: {
                validators: {
                    notEmpty: {
                        message: '請選擇國籍/地區'
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
			/*city: {
                validators: {
                    notEmpty: {
                        message: '請選擇地區'
                    }
					callback: {
                      message: '請選擇地區',
                      callback: function(value, validator) {
                        var city = validator.getFieldElements('city').val();
                        if (city == '') {
                          validator.updateStatus('othercity', 'INVALID');
                          return false;
                        } else {
                          validator.updateStatus('othercity', 'VALID');
                          return true;
                        }
                      }
                    }
                }
            },
			othercity: {
                validators: {
                    notEmpty: {
                        message: '請輸入郵地區號'
                    }
                }
            },*/
            address: {
                validators: {
                    notEmpty: {
                        message: '請輸入地址，不可空白'
                    },
					regexp: {
                        regexp: /.{3,}號/,
                        message: '為方便宅配按時送達，請填寫正確詳細地址喔！至少需填至號!'
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
