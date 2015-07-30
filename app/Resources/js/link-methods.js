/**
 * Link Methods
 * ============
 *
 * Enable possibility to add to link HTTP methods like POST, PUT, DELETE etc.
 * Just add data attribute 'data-method' with desired HTTP method as value.
 *
 * Created by Adam Uhlíř <adam.uhlir@designeo.cz>
 */

$('a[data-method]').on('click',function(e){

    var $form = $('<form/>').hide();

    //form options
    $form.attr({
        'action' : $(this).attr('href'),
        'method':'post'
    })

    //adding the _method hidden field
    $form.append($('<input/>',{
        type:'hidden',
        name:'_method'
    }).val($(this).data('method')));

    //adding a CSRF if needs
    if ($(this).data('csrf'))
    {
        var csrf = $(this).data('csrf').split(':');
        $form.append($('<input/>',{
            type:'hidden',
            name:csrf[0]
        }).val(csrf[1]));
    }

    //add form to parent node
    $(this).parent().append($form);

    $form.submit();

    e.preventDefault();
    return false;
});