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