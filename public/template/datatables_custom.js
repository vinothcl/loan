"use strict";

$(document).ready(function() {     
    $('button.toggle-vis').on('click', function(e) {
        e.preventDefault();
        // Get the column API object
        var column = table.column($(this).attr('data-column'));
        // Toggle the visibility
        column.visible(!column.visible());
    });

});
