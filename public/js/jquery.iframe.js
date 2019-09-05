$(document).on('click', 'a.link', function(e) {
    $.modal('<iframe src="' + $(this).attr('href') + '" scrolling="no" height="570" width="1024">', {
        closeHTML: "<a href='#' style='position:absolute; right:10px; top:10px;'><img src='images/cerrar.png'></a>",
        overlayClose: true
    });
    return false;
});