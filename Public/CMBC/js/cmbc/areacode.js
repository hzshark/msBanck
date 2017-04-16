
function districtcode() {
	$.ajax({
		url : "/CMBC/Areacodes", // 后台webservice里的方法名称
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