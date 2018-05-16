(function ($) {
    var data = JSON.parse($('#data').text()),
        calendar = $('#calendar').html('');
    calendar.fullCalendar({
        events: data,
        eventRender: function (eventItem, element) {
            var actionEl = $('<div class="actions"/>'),
                deleteUrl = calendar.data('delete').replace(/__id__/gi, eventItem.id),
                deleteEl = $('<a class="delete" href="' + deleteUrl + '">Delete</a>');
            actionEl.append('<a href="' + calendar.data('edit').replace(/__id__/gi, eventItem.id) + '">Edit</a>');
            actionEl.append(deleteEl);
            // bind el
            deleteEl.on('click', function () {
                if (!confirm('Are your sure?')) {
                    return false;
                }
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                    },
                    success: function (response) {
                        // delete event
                        calendar.fullCalendar('removeEvents', eventItem.id);
                    }
                })
                return false;
            });
            element.addClass('status-' + eventItem.status).prop('title', 'Status: ' + eventItem.status);
            element.append(actionEl);
        },
        eventClick: function(eventItem, jsEvent, view) {
        }
    });
})(jQuery);
