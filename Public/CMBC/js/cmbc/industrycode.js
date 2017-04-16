
$(function(){
	//alert(22);

	var url = "/City/tbindex.html";
    var cityBox = $("#city_select_three_auto_0");
	var data = $(".citySelect").attr("data-value");
	var citys = null;
	if(data){
		citys = data.split(",");
	}
	function loadComplete(ele){
		var index = $(ele).index();
		//console.log(ele, index);
        $(ele).prepend('<option value="">'+$(ele).attr('placeholder')+'</option>').attr("disabled",null).nextAll("select").each(function(index, el) {
        	$(el).html('<option value="">'+$(el).attr('placeholder')+'</option>');
        });
		if(citys && index < citys.length && citys[index]){
			$(ele).val(citys[index]).change();
		}else{
            $(ele).change();
        }
	}
	function loadCity($slt){
		if($slt.index() == 0){
			parent = 1;
		}else{
			parent = $slt.prev("select").children("option[value]:selected").val();
		}
		//console.log('parent',parent);
		if(parent){
			//$slt.attr('placeholder',$slt.find('option:not([value]):first').val())
			$slt.load(url, {parent:parent}, function(){
				loadComplete(this);
			}).attr("disabled","disabled").find('option:not([value]):first').text("正在加载...");
		}
	}
	loadCity(cityBox.find("select:first"));
	cityBox.find("select").change(function(){
		if($(this).next("select").length){
			loadCity($(this).next("select:first"));
		}
	});
});


function industry() {
	var apicode = $("#apiCode").val();
	var url ="";
	if (apicode == "0005"){
		url = "CMBC/IndustryWX"
	}else{

		if (apicode == "0007"){
			url = "CMBC/IndustryZFB"
		}else{
			url = "CMBC/IndustryQQ"
		}
	}
	$.ajax({
		url : url,
		type : "post",
		dataType : "json",
		contentType : "application/json",
		traditional : true,
		data : {
			root_code : $("#province_code").val(),
			level:3,
			p_code:$("#city_code").val(),
		},
		success : function(data) {
			for ( var i in data) {
				var jsonObj = data[i];
				var optionstring = "";
				for (var j = 0; j < jsonObj.length; j++) {
					optionstring += "<option value=\"" + jsonObj[j].ID + "\" >"
							+ jsonObj[j].chinesename + "</option>";
				}
				$("#dpdField1").html(
						"<option value='请选择'>请选择...</option> " + optionstring);
			}
		},
		error : function(msg) {
			alert("出错了！");
		}
	});
};

function getcitycode() {
	$.ajax({
		url : "/CMBC/Areacodes", // 后台webservice里的方法名称
		type : "post",
		dataType : "json",
		contentType : "application/json",
		traditional : true,
		data : {
			level:2,
			root_code:$("#province_code").val(),
		},
		success : function(data) {
			for ( var i in data) {
				var jsonObj = data[i];
				var optionstring = "";
				for (var j = 0; j < jsonObj.length; j++) {
					optionstring += "<option value=\"" + jsonObj[j].ID + "\" >"
							+ jsonObj[j].chinesename + "</option>";
				}
				$("#dpdField1").html(
						"<option value='请选择'>请选择...</option> " + optionstring);
			}
		},
		error : function(msg) {
			alert("出错了！");
		}
	});
};