$(function(){

    var $courses = $('.js-courses .collapsible-header');

    $courses.on('click', function(e){
        var $this = $(this),
            $wrap = $this.closest('.events-item');

        if(!$('li', $wrap).hasClass('active')){
            BX.ajax.runComponentAction('sopdu:courses.detail',
                'showFile', { // Вызывается без постфикса Action
                    mode: 'class',
                    data: {
                        iFileID: $wrap.data('id'),
                        iElementID: $wrap.data('element')
                    }, // ключи объекта data соответствуют параметрам метода
                })
                .then(function(response) {
                    if (response.status === 'success') {
                        $('.collapsible-body', $wrap).html(response.data.TEMPLATE);
                    }
                });
        }
    });

});