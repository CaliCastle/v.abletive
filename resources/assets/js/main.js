$(function () {
    'use strict';

    var search_open = false,
        content = $('#root')[0],
        search_btn = $('a#search-btn')[0],
        search_field = $('#search-box')[0],
        search_overlay = $('.search-overlay')[0],
        search_results = $('.search-results')[0],
        selected_item = null;

    $(search_btn).click(function () {
        toggleSearch();
    });

    $(search_field).bind('input propertychange', function () {
        searchQuery($(this).val().trim());
    });

    function toggleSearch() {
        $('#app-layout').toggleClass('show');
        search_open = !search_open;

        if (search_open) {
            setTimeout(function () {
                search_field.focus();
            }, 800);
        }
    }

    function searchQuery(value) {
        if (value == "" || value.indexOf("'") >= 0) {
            return false;
        }
        $(search_results).html('<h1 class="loading"><i class="fa fa-spin fa-spinner"></i></h1>');
        $.ajax({
            url: searchURL + value,
            data: {_token: $_token},
            dataType: 'json',
            type: "POST",
            success: function (data) {
                $(search_results).html(data.html);
            }
        });
    }

    $(search_overlay).click(function() {
        if( search_open ) {
            toggleSearch();
        }
    } );

    $(document).keydown(function (e) {
        if ((e.ctrlKey || e.metaKey) && e.which == 70) {
            e.preventDefault();
            $(search_btn).trigger('click');
        }

        if (search_open && (e.keyCode == 40 || e.keyCode == 38)) {
            if (selected_item == null) {
                selected_item = $(search_results).find('li.item')[0];
                $(selected_item).addClass('selected');
                return false;
            }
            $(selected_item).removeClass('selected');
            switch (e.keyCode) {
                case 40:
                {
                    // Down arrow
                    var next = $(selected_item).next();
                    if ($(next).hasClass('divider')) {
                        $(next).next().addClass('selected');
                        selected_item = $(next).next();
                    } else if ($(next).length <= 0) {
                        selected_item = $(search_results).find('li.item')[0];
                        $(selected_item).addClass('selected');
                    } else {
                        $(next).addClass('selected');
                        selected_item = next;
                    }
                    break;
                }
                case 38:
                {
                    // Up arrow
                    var prev = $(selected_item).prev();
                    if ($(prev).hasClass('divider')) {
                        $(prev).prev().addClass('selected');
                        selected_item = $(prev).prev();
                    } else if ($(prev).length <= 0) {
                        selected_item = $(search_results).find('li.item')[$(search_results).find('li.item').length-1];
                        $(selected_item).addClass('selected');
                    } else {
                        $(prev).addClass('selected');
                        selected_item = prev;
                    }
                    break;
                }
            }
        }

        if (search_open && e.keyCode == 13) {
            // Enter key
            window.location.href = $($(selected_item).find('a')[0]).attr('href');
        }
    });
});