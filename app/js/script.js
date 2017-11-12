$(document).on('submit', '.form-login',function(e) {
	var form = $(this);
	$.ajax({
	       data: form.serialize(),
	       type: "post",
	       url: "functions.php",
	       success: function(data) {
	       		//trim удаляет лишние пробелы, которые есть в response
	       		if($.trim(data) === "no_errors") { window.location.href = '/pages/group' }
	       		else $(".login__error").html($.trim(data));
	       }
	});	
	e.preventDefault();
});

$(function(){
    $('#logout').click(function(){
        if(confirm('Вы точно хотите выйти?')) {
            return true;
        }
        return false;
    });
});


//Добавление, удаление выходного дня при клике
//+ посещение
$(document).on('click', '.weekend',function(e) {
	//Если в "Посещении" дата - выходной, то false
	if($(this).hasClass("date_off")){ return false; }

	if($(this).hasClass("weekend__red")){
		$(this).removeClass("weekend__red");
		var data = "del_day=" + $(this).attr("for");
	}
	else {
		$(this).addClass("weekend__red");
		var data = "add_day=" + $(this).attr("for");
	}

	$.ajax({
	       data: data,
	       type: "post",
	       url: "../functions.php"
	});	
});

//Изменение группы
$(document).on('keypress', '.edit_group', function(e){
		if(e.keyCode == 13){
			//Новая группа
			var group = $(this).val();

			//Курc берётся из 2-й цифры названия
			var course = "&course="+group.charAt(1);

			//Если курс больше 4
			if(group.charAt(1) > 4 || group.charAt(1) < 1){
				$(".error").html("Курс должен быть от 1 до 4!");
				$(".error").fadeIn(300);
				return false;
			}

			//Старое название группы
			var old_group = $(this).parent().attr("data-group");

			//Не сработает, если название не из 3-х цифр
			if(group.length == 3){
				if(old_group != undefined){
					data = "edit_group="+group+course+"&old_group="+old_group;
				}
				else data = "new_group="+group+course;				
			  $.ajax({
			    type: "post",
			    url: "../functions.php",
			    data: data,
			    success: function(data) {
			    			if(data == "group_exists"){
			    				$(".error").html("Такая группа уже есть!");
			    				$(".error").fadeIn(300);
			    			}
						  	else if(data == "not_numeric"){
						  		$(".error").html("Название только из цифр!");
						  		$(".error").fadeIn(300);
						  	}
						  	else $(".group").html(data);
						 }
			  });
			}
			else {
	  		$(".error").html("Минимум 3 цифры!");
	  		$(".error").fadeIn(300);				
			}
		}
});

//Первое создание группы
$(document).on('keypress', '.new_group', function(e){
		if(e.keyCode == 13){
			//Новая группа
			var group = $(this).val();

			//Курc берётся из 2-й цифры названия
			var course = "&course="+group.charAt(1);

			//Если курс больше 4
			if(group.charAt(1) > 4 || group.charAt(1) < 1){
				$(".error").html("Курс должен быть от 1 до 4!");
				$(".error").fadeIn(300);
				return false;
			}

			//Не сработает, если название не из 3-х цифр
			if(group.length == 3){
				data = "new_group="+group+course;				
			  $.ajax({
			    type: "post",
			    url: "../functions.php",
			    data: data,
			    success: function(data) {
						   $(".group").html(data);
						 }
			  });
			}
		}
});

$(document).on('click', '.btn_edit_group', function(){
	var group = $(".group_name").attr("data-group");
	$(".group_name").html("<input class='edit_group' maxlength='3' value='"+group+"' type='text'><div style='display: none;' class='error'></div>");
});

//Добавление студента
$(document).on('submit', '#add_student', function(e) {
	  var form = $(this);
	  var group = $(this).find("input[name='new_s_name']").attr("data-group");
	  group = "&group="+group;
	  $.ajax({
	    type: "post",
	    url: "../functions.php",
	    data: form.serialize()+group,
	    success: function(data) {
				   $(".group_table").html(data);
				 }
	  });
	  //отмена действия по умолчанию для кнопки submit
	  e.preventDefault(); 
});

//Удаление студента
$(document).on('click', '.del_stud', function(e) {
		if(confirm("Вы уверены?")){
		  var student = "del_stud="+$(this).parent().attr("data-student");
		  student = student + "&group="+$(this).parent().attr("data-group");
		  $.ajax({
		    type: "post",
		    url: "../functions.php",
		    data: student,
		    success: function(data) {
					   $(".group_table").html(data);
					 }
		  });
		  //отмена действия по умолчанию для кнопки submit
		  e.preventDefault(); 
		}
});

//Редактирование студента
$(document).on('click', '.edit_stud', function(e) {
	  var old = $(this).parent().find("span").text();
	  $(this).parent().html("<input type='text' class='stud_new_name' value='"+old+"'>");
});
$(document).on('keyup blur', '.stud_new_name', function(e) {
		if (e.type == 'blur' || e.keyCode == '13'){
		  var student = "edit_stud="+$(this).parent().attr("data-student");
		  student += "&group="+$(this).parent().attr("data-group");
		  student += "&new_name="+$(this).val();
		  $.ajax({
		    type: "post",
		    url: "../functions.php",
		    data: student,
		    success: function(data) {
					   $(".group_table").html(data);
					 }
		  });
		  //отмена действия по умолчанию для кнопки submit
		  e.preventDefault();
		}
});


//------Псоещение-------
$(document).on("click", ".date_on", function(){
	$("td .weekend__red").removeClass("weekend__red");
	$(this).addClass("weekend__red");

	var date = "chose_date="+$(this).attr("data-date");
	$.ajax({
		type: "post",
		url: "../functions.php",
    data: date,
    success: function(data) {
			   $(".attend_table").html(data);
			 }
	});
});

$(document).on("blur", ".mark", function(){
	var old = $(this).val();
	if(old != ""){
		$(this).parent().parent().find(".mark").val("");
		$(this).val(old);
		var mark = "new_mark="+$(this).val();
		var student = "&mark_st="+$(this).attr("data-student");
		var date = "&mark_date="+$(this).attr("data-date");
		var mark_type = "&mark_type="+$(this).attr("name");
		$.ajax({
			type: "post",
			url: "../functions.php",
	    data: mark+student+date+mark_type
		});		
	}
});