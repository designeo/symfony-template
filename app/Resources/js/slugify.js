/**
 * Slugify
 * =======
 * Created by Ondrej Musil
 * Extended by Adam Uhlir
 */

/**
 * Return passed string normalized for URL address
 *
 * @param str string
 * @returns string
 */
var slugify = function (str) {
    var charMap = {
        'á': 'a',
        'ě': 'e',
        'é': 'e',
        'š': 's',
        'č': 'c',
        'ř': 'r',
        'ž': 'z',
        'ý': 'y',
        'ú': 'u',
        'ů': 'u',
        'ď': 'd',
        'ť': 't',
        'ň': 'n',
        'í': 'i'
    };

    str = str.toString().toLowerCase();

    var to;
    for (var from in charMap) {
        if (charMap.hasOwnProperty(from)) {
            to = charMap[from];
            str = str.replace(new RegExp(from, 'g'), to);
        }
    }

    str = str.replace(/\s+/g, '-')    // Replace spaces with -
        .replace(/[^\w\-]+/g, '')     // Remove all non-word chars
        .replace(/\-\-+/g, '-')       // Replace multiple - with single -
        .replace(/^-+/, '')           // Trim - from start of text
        .replace(/-+$/, '');          // Trim - from end of text

    return str;
};

/**
 *
 * @param from selector
 * @param to selector
 */
var slugifyListener = function (from, to) {
    var $to = $(to);

    $(from).on('change', function () {
        $to.val(slugify($(this).val()));
    });
};