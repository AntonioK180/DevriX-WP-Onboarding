function setOutputField(value) {
    document.getElementById('output').innerHTML = value;
}

function setOutputIframe(value) {
    document.getElementById('output-frame').srcdoc = value;
}

function showInput() {
    var data = {
        'action': 'display_input_link',
        'dataType': 'JSON',
        'link_value': document.getElementById('link').value,
        'cache_duration': document.getElementById('cache_duration').value
    };

    jQuery.post(ajaxurl, data, function(response) {
        setOutputIframe(response);
    });
}

function loadCachedHTMLBody() {
    var data = {
        'action': 'display_cached_html',
        'dataType': 'JSON'
    };

    jQuery.post(ajaxurl, data, function(response) {
        setOutputIframe(response);
        console.log(window.location.href);
    });
}


if (!window.location.href.localeCompare('http://localhost/wordpress/wp-admin/admin.php?page=myplugin%2Fsanitized-links-admin.php')) {
    loadCachedHTMLBody();
}