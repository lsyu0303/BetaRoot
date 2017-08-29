$(function(){
	$(document).ready( function(){ 
		//复选按钮
		$(".Tab_checkbox[type='checkbox']").each(function(){
			var a = $(this).attr("name"),
				v = $(this).attr("value"),
				t = $(this).attr("title");
			if(this.checked){
				$(this).replaceWith('<div class="Tab_checkbox"><input type="checkbox" name="' + a + '" value="' + v + '" checked="checked"><dl class="on"><span>' + t + '</span><i class="icon ai-check"></i></dl></div>');
			}else{
				$(this).replaceWith('<div class="Tab_checkbox"><input type="checkbox" name="' + a + '" value="' + v + '"><dl><span>' + t + '</span><i class="icon ai-check"></i></dl></div>');
			}
			
		});
		//单选按钮
		$(".Tab_radio[type='radio']").each(function(){
			var a = $(this).attr("name"),
				v = $(this).attr("value");
			if(this.checked){
				$(this).replaceWith('<div class="Tab_radio"><input type="radio" name="' + a + '" value="' + v + '" checked="checked"><dl class="on"><i class="icon ai-radio-on"></i><span>' + v + '</span></dl></div>');
			}else{
				$(this).replaceWith('<div class="Tab_radio"><input type="radio" name="' + a + '" value="' + v + '"><dl><i class="icon ai-radio"></i><span>' + v + '</span></dl></div>');
			}
			
		});
	});	
	  
	$(".Tab_form").on("click",".Tab_checkbox",function(){
		if($(this).find('dl').hasClass("on")){
			$(this).find('dl').removeClass('on');
			$(this).find("input[type='checkbox']").attr('checked', false);
		}else{
			$(this).find('dl').addClass('on');
			$(this).find("input[type='checkbox']").attr('checked', true);
		}
	});	
	
	$(".Tab_form").on("click",".Tab_radio",function(){  
		if(!this.checked){
			$(this).find("dl").addClass('on');
			$(this).find("dl i").addClass('ai-radio-on').removeClass('ai-radio');
			$(this).find("input[type='radio']").attr('checked', true);
			$(this).siblings().find('dl').removeClass('on');
			$(this).siblings().find('dl i').removeClass('ai-radio-on').addClass('ai-radio');
			$(this).siblings().find("input[type='radio']").attr('checked', false);
		}
	});
	$(".Tab_form").on("click",".Tab_select",function(){  
		var i = $(this).index('.Tab_select');
		$(this).find("dl").show();
		$(this).find("dd").click(function(){
			var v = $(this).text();
			var n = $(this).index();
			$(this).addClass('on').siblings().removeClass();
			$(".Tab_select").eq(i).find('.select-input').val(v);
			$(".Tab_select").eq(i).find("option").eq(n+1).attr('selected', true).siblings().attr('selected', false);
		});
		$(".Tab_select").mouseleave(function(){
			$(this).find("dl").hide();
		});
	});
	
	// TAB多栏选卡
	var tab = $(".Tab_tab .tab-nav li");
	tab.click(function(){
		$(this).addClass('on').siblings().removeClass();
        $('.Tab_tab_con').eq(tab.index(this)).show().siblings().hide();
    });
	
	//全选 反选
	$(".Tab_checkAll").click(function(){
		var name = this.id.substring(0,this.id.length-3);
		if(this.checked){
			$("#"+name).find("input[name='"+name+"[]']").each(function(){this.checked=true;});
		}else{
			$("#"+name).find("input[name='"+name+"[]']").each(function(){this.checked=false;});
		}
		checkAll(name);
	});
	$(".Tab_check").click(function() {
		var name = $(this).parents().parents().parents().attr('id');
		checkAll(name);
	});
	function checkAll(name){
		var list = document.getElementsByClassName("Tab_checkAll");
		for(var i=0;i<list.length;i++){
		   if(list[i].id == name+"All" || list[i].id == name+"Alb"){
			   list[i].checked=($("#"+name).find("input[name='"+name+"[]']").length == $("#"+name).find("input[name='"+name+ "[]']:checked").length ? true : false);
		   }
		}
	};
	
	//开关按钮
	$(".Tab_on").click(function(){
		var parent = $(this).parents('.btn-on');
		$(this).removeClass('off').addClass('on');
		$('.Tab_off',parent).removeClass('on-off').addClass('off-off');
		$('.Tab_on',parent).removeClass('on-off').addClass('on-on');
		$('.Tab_open',parent).attr('checked', true);
		$('.Tab_shut',parent).attr('checked', false);
	});
	$(".Tab_off").click(function(){
		var parent = $(this).parents('.btn-on');
		$(this).removeClass('off').addClass('on');
		$('.Tab_on',parent).removeClass('on-on').addClass('on-off');
		$('.Tab_off',parent).removeClass('off-off').addClass('off-on');
		$('.Tab_shut',parent).attr('checked', true);
		$('.Tab_open',parent).attr('checked', false);
	});

	// 数量加减
	$ (".Tab_num .jian").click (function (){
		var me = $ (this), txt = me.next (":text");
		var val = parseFloat (txt.val ());
		if (!val) {
			txt.val (1);
		}else if(val < 0){
			var shu = Math.abs(val);
			/* txt.val (-(shu + 1)); */
			txt.val (1);
		}else if(val > 0){
			if(val == 1){
				txt.val (1);  
			}else{
				txt.val (val - 1);			
			}
		}
		var sum = 0;
	});
	$(".Tab_num .jia").click (function (){
		var me = $ (this), txt = me.prev (":text");
		var val = parseFloat (txt.val ());
		if(val >= 1){
			txt.val (val + 1);   
		}else if(val < 0){
			if(val == -1){
				txt.val ("");  
			}else{
				var shu = Math.abs(val);
				txt.val (-(shu - 1));			
			}
		}else{
			txt.val (1);			
		}
		var sum = 0;
	});

	
	$("#info").click(function(){
		var id = $("#link").val();
		 $.getJSON(url,{id:id},function(data){
			
			/* var description = ""; 
			for(var i in data){   
				var property=data[i];   
				description+=i+" = "+property+"\n";  
			}   
			alert(description); */
			
			$('#num_iid').val(data['item']);
			$('#title').val(data['title']);
			if(/taobao\.com/.test(id)){
				document.getElementById('mall').value = '1';
			}else{
				document.getElementById('mall').value = '2';
			}
			$('#shop').val(data['shop']);
			$('#shopid').val(data['shopid']);
			
			$('#pict_url').val(data['img']);
			$('#value').val(data['value']);
			$('#price').val(data['price']);
			$('#ratio').val(data['ratio']);
			$('#deal').val(data['deal'])
			
		});
	});
});
$(function(){
	$("textarea").froalaEditor({
	    language: "zh_cn",
	})
});
