"use strict";
window.addEventListener('load', function() {
	AOS.init({
		easing: 'ease-in-out-sine'
	});
});

document.addEventListener('DOMContentLoaded', function() {    
//Маски для полей ввода    
$('#phone').mask('+(375) 99 999-99-99');

//------------------------------------------------------------------------------------
//						CONTACT FORM VALIDATION'S SETTINGS
//------------------------------------------------------------------------------------
$('#call_request').validate({
    onfocusout: false,
    onkeyup: false,
    rules: {
        phone:{
            required: true       
        },
    },
    errorPlacement: function (error, element) {},
    highlight: function (element) {
                  $(element)
                      .closest('.form-group')
                      .removeClass('has-success')
                      .addClass('has-error');
              },            
    unhighlight: function (element) {
                  $(element)
                      .closest('.form-group')
                      .removeClass('has-error')
                      .addClass('has-success');
              }
});

$('#form_message, #contact-form').validate({
    onfocusout: false,
    onkeyup: false,
    rules: {
        NAME:{
          required:true,  
        },
        EMAIL:{
          required:true,    
        },
    },
    errorPlacement: function (error, element) {},
    highlight: function (element) {
                  $(element)
                      .closest('.form-group')
                      .removeClass('has-success')
                      .addClass('has-error');
              },
              
    unhighlight: function (element) {
                  $(element)
                      .closest('.form-group')
                      .removeClass('has-error')
                      .addClass('has-success');
              }
});

$('#index-form').validate({
    onfocusout: false,
    onkeyup: false,
    rules: {
        NAME:{
          required:true,  
        },
        EMAIL:{
          required:true,    
        },
    },
    errorPlacement: function (error, element) {},
    highlight: function (element) {
                  $(element)
                      .closest('.form-group')
                      .removeClass('has-success')
                      .addClass('has-error');
              },
              
    unhighlight: function (element) {
                  $(element)
                      .closest('.form-group')
                      .removeClass('has-error')
                      .addClass('has-success');
              }
});


//------------------------------------------------------------------------------------
//								CONTACT FORM SCRIPT
//------------------------------------------------------------------------------------

$('#call_request, #index-form, #form_message, #contact-form').submit(function () {
    // submit the form
    var form =  $(this);   
    if ($(this).valid()) {
        form.find("p.msg").addClass('none');
        //формируем объект данных
        var formData = new FormData();
            form.find('input, textarea').each(function () {
                      formData.append($(this).attr('name'), $(this).val());
            });
        formData.append('id', this.id);
        var action = $(this).attr('action');               
        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 4000,
            //Указывая тип json использовать функцию JSON.parse не надо будет ошибка
            dataType: "json",
            beforeSend: function (data) {
            //Блокируем кнопку и элементы формы
                form.find('button,input').attr('disabled', 'disabled');
            },
            success: function (data) {
                if (data.status == true) {                   
                    //Очистка формы
                    form[0].reset();
                    //Включение кнопки и элементов формы
                    form.find('button,input').removeAttr('disabled');
                    form.find('.form-group').removeClass('has-success');
                    form.find("p.msg").html(data.message);                   
                    form.find("p.msg").css("color", "green").fadeIn("slow");
                    setTimeout(function () {
                        $('.modal.in').modal( 'hide' );
                        $('p.msg').fadeOut("slow");
                    }, 2000); 
                }else {                    
                    //Включение кнопки и элементов формы
                    form.find('button,input').removeAttr('disabled');
                    form.find('.form-group').removeClass('has-success');
                    form.find("p.msg").html(data.message);                   
                    form.find("p.msg").css("color", "red").fadeIn("slow");
                    setTimeout(function () {                       
                        $('p.msg').fadeOut("slow");
                    }, 2000); 
                }
            },
            error: function(x, t, e){
                if( t === 'timeout') {
                    // Произошел тайм-аут
                    //Очистка формы
                    form[0].reset();
                    //Включение кнопки и элементов формы
                    form.find('button,input').removeAttr('disabled');
                    form.find("p.msg").html('Превышено время ожидания');
                    form.find("p.msg").css("color", "#000").fadeIn("slow");
                    setTimeout(function() { $('p.msg').fadeOut("slow"); }, 3000);      
                }       
            }
        });
        } else {           
            //if data was invalidated
        }
    return false;
});
//------------------------------------------------------------------------------------



   

});
