(function($){

function supports_html5_storage() {
  try {
    return 'localStorage' in window && window['localStorage'] !== null;
  } catch (e) {
    return false;
  }
}

var hasStorage = supports_html5_storage();

$(document).on('change', '[data-showsettings]', function() {
    var input = $(this),
        section = input.closest('.section'),
        checked = input.is(':checked');

    if(checked) {
        section.addClass('fieldguide-show-settings');
    }
    else {
        section.removeClass('fieldguide-show-settings');
    }

    if(hasStorage) {
        localStorage.setItem('fieldguide-show-settings-' + input.attr('data-showsettings'), checked ? '1' : '');
    }
});


if(hasStorage) {
    $(function() {
        $('[data-showsettings]').each(function() {
            var input = $(this),
                checked = localStorage.getItem('fieldguide-show-settings-' + input.attr('data-showsettings'));

            if(checked) {
                input.attr('checked', 'checked').trigger('change');
            }
        });
    });
}

})(jQuery);