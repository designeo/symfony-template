$(document).ready(function(){
    $('input.send-form-on-enter').keyup(function(e){
        if (e.keyCode == 13){
            $(this).parents('form').submit();
        }
    });

    $('.grid form.grid-filter').submit(function(){
        // remove empty inputs from query string
        $(this).find('input').each(function(){
            if ($(this).val().length === 0) {
                $(this).attr("disabled", "disabled");
            }
        });
        // remove empty select boxes from query string
        $(this).find('select').each(function(){
            var selectedOption = $(this).find('option:selected');
            if (! selectedOption.length || ! selectedOption.val()) {
                $(this).attr("disabled", "disabled");
            }
        });

        setTimeout(function () {
            $("input[disabled], select[disabled]").removeAttr('disabled');
        }, 10);

        return true;
    });

    $('select.send-form-on-change').change(function(e){
        $(this).parents('form').submit();
    });

    $('a.remove').click(function(e){
        return confirm('Opravdu chcete smazat tuto položku?');
    });
});