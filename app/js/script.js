$(function(){
    $('#logout').click(function(){
        if(confirm('Вы точно хотите выйти?')) {
            return true;
        }
        return false;
    });

    //При нажатии на активную ссылку в меню, запрос не отправляется
    $(".menu_link").click(function(){
    	if($(this).hasClass("active")){
    		return false;
    	}
    	else return true;
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