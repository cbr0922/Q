$(document).ready(function() {
    $('#shoppingform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
		//訂購人資訊
            /*true_name: {
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
			other_tel: {
                validators: {
                    notEmpty: {
                        message: '請輸入手機，不可空白'
                    },
                    remote: {
                        type: 'POST',
                        url: '../member/member.php?checktype=mobile&action=CheckExit4',
                        message: '此手機號碼已經被註冊使用',
                        data: {
                          user_id:$('[name="user_id"]').val()
                        },
                        delay: 500
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
			/*tel: {
                validators: {
                    notEmpty: {
                        message: '請輸入市話，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*
                }
            },*/
			county2: {
                validators: {
                    notEmpty: {
                        message: '請選擇'
                    }
                }
            },
			province2: {
                validators: {
                    notEmpty: {
                        message: '請選擇城市'
                    }
                }
            },
			/*city2: {
                validators: {
                    notEmpty: {
                        message: '請選擇地區'
                    }
                }
            },*/
			addr2: {
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
            ifok: {
                validators: {
                    notEmpty: {
                        message: '請勾選同意會員條款'
                    }
                }
            },
		//收貨人資訊
			receiver_name: {
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
			receiver_email: {
                validators: {
                    notEmpty: {
                        message: '請輸入Email，不可空白'
                    },
                    emailAddress: {
                        message: 'Email無效'
                    }
                }
            },
			receiver_mobile: {
                validators: {
                    notEmpty: {
                        message: '請輸入手機，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
			receiver_tele: {
                validators: {
                    notEmpty: {
                        message: '請輸入市話，不可空白'
                    },
                    /*phone: {
                        message: 'Please supply a vaild phone number with area code'
                    }*/
                }
            },
			/*city: {
                validators: {
                    notEmpty: {
                        message: '請選擇地區'
                    }
                }
            },*/
			addr: {
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
			HomeTimeType: {
                validators: {
                    notEmpty: {
                        message: '請選擇宅配時間'
                    }
                }
            },
			/*datepicker: {
                validators: {
                    notEmpty: {
                        message: '請選擇配送日期'
                    }
                }
            },*/
			//發票
			invoice_num: {
                validators: {
                     stringLength: {
                        min: 8,
						max: 8,
						message: '請輸入8個數字'
                    },
                    notEmpty: {
                        message: '請輸入統一編號'
                    }
                }
            },
			invoiceform: {
                validators: {
                    notEmpty: {
                        message: '請輸入發票抬頭'
                    }
                }
            },
			ifinvoice: {
                validators: {
                    notEmpty: {
                        message: '請選擇發票'
                    }
                }
            },
		}

    })
});
