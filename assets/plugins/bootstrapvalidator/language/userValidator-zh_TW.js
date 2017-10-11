$(document).ready(function() {
    $('#userform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
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
			/*truename: {
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
			sex: {
                validators: {
                    notEmpty: {
                        message: '請選擇性別'
                    }
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
			/*county: {
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
			othercity: {
                validators: {
                    notEmpty: {
                        message: '請輸入郵地區號'
                    }
                }
            },
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
            },*/
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

		}

        })
});