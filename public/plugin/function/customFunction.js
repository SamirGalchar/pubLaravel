// JavaScript Document
	function startLoaderAjax(){
		$("body").append("<div id='ajax_loader_42160' style='background:black;opacity:0.7;position:fixed;top:0;bottom:0;width:100%;height:100%;z-index:99999999;display:none;'><div style='margin:200px auto; width:300px;'><img width='300' src='"+SITE_IMG_JS+"/loading_home.gif' alt=''></div></div>");
		$("#ajax_loader_42160").show();
	}
	function stopLoaderAjax(){
		$("#ajax_loader_42160").hide();
		$("#ajax_loader_42160").remove();
	}
	function ajaxCall(url,param){
		startLoaderAjax();
		var tmpData="";
		$.ajax({
			type: "POST",
			url: url,
			async:false,
			data: param,
			success: function(data){
				tmpData=JSON.parse(data);
			}
		});
		stopLoaderAjax();
		return tmpData;
	}
	function showNotification(returnData){
		var selector='';
		if(returnData.selector=="" || returnData.selector==undefined){
			selector="#alertDiv";
		}
		else {
			selector = returnData.selector;
		}
		var htmlString='<div class="alert rosegold-bg grey_color alert-block alert-dismissible fade show mb-0  text-'+returnData.cls+'"><strong class="grey_color">'+returnData.msg+'</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		$(selector).html(htmlString);
		if(returnData.redirect=="yes" && returnData.redirectUrl!=''){
			setTimeout(function(){
				window.location.href = returnData.redirectUrl;
			}, 1000);
		}
	}
	function initFormValidate(rules, message, selector, errorPlacement){

		if(selector=="" || selector==undefined){
			selector=".frmValidate";
		}
		$(selector).validate({
			onkeyup: false,
			errorElement: 'div',
			ignore: [],
			errorClass: 'pull-left error',
			focusInvalid: false,
			ignoreTitle: true,
			invalidHandler: function (event, validator) { //display error alert on form submit
				$('.alert-danger', $('.login-form')).show();
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
				$(e).remove();
			},
			rules:rules,
			messages: message,
			errorPlacement:errorPlacement
		});
	}

	var req_editor=function(value, element, params) {
		if($(params[0]).html()=='' || $(params[0]).html()=='<br>'){
			return false;
		}
		else{
			$(params[1]).val($(params[0]).html());
			return true;
		}
	};
	//$.validator.addMethod("required_editor", req_editor ,"Value could not be empty");

	function htmlEscape(str) {
		return String(str)
				.replace(/&/g, '&amp;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#39;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;');
	}
