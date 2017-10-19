function getChar(event) {
  if (event.which == null) { // IE
    if (event.keyCode < 32) return null; // спец. символ
    return String.fromCharCode(event.keyCode)
  }

  if (event.which != 0 && event.charCode != 0) { // все кроме IE
    if (event.which < 32) return null; // спец. символ
    return String.fromCharCode(event.which); // остальные
  }

  return null; // спец. символ
}

//Название группы только из цифр!
$(document).on('keypress', '.new_group', function(e) {
  e = e || event;

  if (e.ctrlKey || e.altKey || e.metaKey) return;

  var chr = getChar(e);

  if (chr == null) return;

  if (chr < '0' || chr > '9') {
    return false;
  }
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
$(document).on('click', '.weekend',function(e) {
	if($(this).hasClass("active")){
		$(this).removeClass("active");
		var data = "del_day=" + $(this).attr("for");
	}
	else {
		$(this).addClass("active");
		var data = "add_day=" + $(this).attr("for");
	}

	$.ajax({
	       data: data,
	       type: "post",
	       url: "../functions.php"
	});	
});

//Удаление группы
$(document).on('click', '.del_group', function(e){
	if(confirm("Вы уверены, что хотите удалить группу "+$(this).attr("data-group")+"?")){
		var data = "del_group=" + $(this).attr("data-group");
		var group = $(this).parent();
		$.ajax({
		       data: data,
		       type: "post",
		       url: "../functions.php",
					 success: function(data) {
					   group.remove();
					   $(".groups_table").empty();
					   $(".groups").html(data);
					 }
		});
	}
	else return false;

	//Чтобы не срабатывал клик на родителя
	e.stopPropagation();
});

//Создание группы
$(document).on('keypress', '.new_group', function(e){
		if(e.keyCode == 13){
			//Группа
			var group = $(this).val();

			//Кура берётся из 2-й цифры названия
			var course = "&course="+group.charAt(1);

			//Если курс больше 4
			if(group.charAt(1) > 4){
				$(".error").html("Курс не должен быть больше 4!");
				$(".error").fadeIn();
				return false;
			}

			//Если изменили название группы
			var old_group = $(this).parent().attr("data-group");

			//Не сработает, если название не из 3-ч цифр
			if(group.length == 3){
				if(old_group != undefined){
					data = "new_group="+group+course+"&old_group="+old_group;
				}
				else data = "new_group="+group+course;				
			  $.ajax({
			    type: "post",
			    url: "../functions.php",
			    data: data,
			    success: function(data) {
						   $(".groups").html(data);
						 }
			  });
			}
		}
});

$(document).on('click', '.edit_group', function(){
	var group = $(".group_name").attr("data-group");
	$(".group_name").html("<input class='new_group' maxlength='3' value='"+group+"' type='text'><div style='display: none;' class='error'></div>");
});

//Добавление студента
$(document).on('submit', '#add_student', function(e) {
	  var form = $(this);
	  var group = $(this).find("input[name='new_s_name']").attr("data-group");
	  group = "&selected_group="+group;
	  $.ajax({
	    type: "post",
	    url: "../functions.php",
	    data: form.serialize()+group,
	    success: function(data) {
				   $(".groups_table").html(data);
				 }
	  });
	  //отмена действия по умолчанию для кнопки submit
	  e.preventDefault(); 
});

//Удаление студента
$(document).on('click', '.del_stud', function(e) {
	  var student = "del_stud="+$(this).attr("data-student");
	  student = student + "&group="+$(this).attr("data-group");
	  $.ajax({
	    type: "post",
	    url: "../functions.php",
	    data: student,
	    success: function(data) {
				   $(".groups_table").html(data);
				 }
	  });
	  //отмена действия по умолчанию для кнопки submit
	  e.preventDefault(); 
});


//------Псоещение-------
$(document).on("click", ".date_on", function(){
	$("td .active").removeClass("active");
	$(this).addClass("active");

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
	var mark = "new_mark="+$(this).val();
	var student = "&mark_st="+$(this).attr("data-student");
	var date = "&mark_date="+$(this).attr("data-date");
	var mark_type = "&mark_type="+$(this).attr("name");
	$.ajax({
		type: "post",
		url: "../functions.php",
    data: mark+student+date+mark_type
	});
});