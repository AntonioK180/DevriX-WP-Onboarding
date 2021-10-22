var filters_enabled = document.getElementById('filters-enabled');

if (filters_enabled != null) {
    filters_enabled.addEventListener("change", function($) {
        update_box();
    })
}

function update_box() {
    var data = {
        action: 'add_filters_action',
        'filters_enabled': filters_enabled.checked
    };

    jQuery.post(ajaxurl, data, function(response) {});
}
