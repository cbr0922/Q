$(document).ready(function() {
		$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
    $('#receiverinfoform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
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
                    },
					/*remote: {
                        type: 'POST',
                        url: '../member/member.php?checktype=username&action=CheckExit',
                        message: 'email已經被註冊使用',
                        delay: 500
                    }*/
                }
            },						receiver_mobile: {                validators: {                    notEmpty: {                        message: '請輸入手機，不可空白'                    },                    /*phone: {                        message: 'Please supply a vaild phone number with area code'                    }*/                }            },            receiver_tele: {                validators: {                    notEmpty: {                        message: '請輸入市話，不可空白'                    },                    /*phone: {                        message: 'Please supply a vaild phone number with area code'                    }*/                }            },			county3: {                validators: {                    notEmpty: {                        message: '請選擇'                    }                }            },			province3: {                validators: {                    notEmpty: {                        message: '請選擇城市'                    }                }            },			city3: {                validators: {                    notEmpty: {                        message: '請選擇地區'                    }                }            },			/*othercity2: {                validators: {                    notEmpty: {                        message: '請輸入郵地區號'                    }                }            },*/            addr: {                validators: {                     stringLength: {                        min: 4,						message: '請輸入至少4個字數'                    },                    notEmpty: {                        message: '請輸入地址，不可空白'                    }                }            },
		}

    })

	$("#receiversave").click(function(){
		$('#receiverinfoform').data('bootstrapValidator').validate();
		//alert($('#receiverinfoform').data('bootstrapValidator').isValid());
		if($('#receiverinfoform').data('bootstrapValidator').isValid()){
			$.ajax({
				type : 'post',
				url : 'shopping_ajax_receiverinfo.php',
				data : $('form').serialize(),
				success : function(result) {
					showpage("shopping_ajax_receiver.php","","showreceiver");
					$('#ajax').modal('hide');
				}
			});
		}
	});
});