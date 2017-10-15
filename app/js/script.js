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
$(document).on('keypress', '.add_new_group', function(e) {
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

//Вывод таблицы студентов по клику на группу
$(document).on('click', '.course_name', function(e){
	$(".groups .active").removeClass("active");
	$(this).addClass("active");
	var data = "selected_group=" + $(this).attr("data-group");

	$.ajax({
	       data: data,
	       type: "post",
	       url: "../functions.php",
				 success: function(data) {
				   $('.groups_table').html(data);
				 }
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
					 }
		});
	}
	else return false;

	//Чтобы не срабатывал клик на родителя
	e.stopPropagation();
});

//Добавление группы
$(document).on('submit', '.add_group', function(e) {
	  var form = $(this);
	  $.ajax({
	    type: "post",
	    url: "../functions.php",
	    data: form.serialize(),
	    success: function(data) {
				   form.parent().html(data);
				 }
	  });
	  //отмена действия по умолчанию для кнопки submit
	  e.preventDefault(); 
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
	  student = student + "&selected_group="+$(this).attr("data-group");
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