/**
 * Created by maverick10 on 24.9.15.
 */

$('a[data-confirm], button[data-confirm], input[data-confirm]').on('click', function (e) {
    if (!confirm($(this).attr('data-confirm'))) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    }
});
