jQuery(document).ready(function ($) {

    function catapi_show_error(){
        $('#container').html(catapi.error_msg);
        $('#container').fadeIn();
        return;
    }

    $('#breeds').change(function () {
        $('#container').fadeOut();
        $('.loading-top').fadeIn();
        $.ajax({
            url: catapi.ajax_url,
            data: { breed: $("#breeds").val(), action: 'catapi_get_cats_wrapper' },
            dataType: "json",
            type: "get",
            success: function (data) {
                var pagination = data[0];
                var cats = data[1];
                $('#cats').html("");
                if (cats) {
                    jQuery.each(cats, function (index, cat) {
                        $('#cats').append('<div class="cat"><div class="cat-image"><a href="'+catapi.cat_url+'?id='+cat.id+'" style="background-image: url(' + cat.url + ');"></a></div><a class="button view-details" href="'+catapi.cat_url+'?id='+cat.id+'">View Details</a></div>');
                    });
                    $('#load-more').data('page', 0);
                    $('#load-more').removeClass('hide-me');
                    if (pagination.current >= pagination.total) {
                        $('#load-more').addClass('hide-me');
                    }

                    const url = new URL(window.location);
                    url.searchParams.set('breed', $("#breeds").val());
                    const state = { 'breed': $("#breeds").val() }
                    history.pushState(state, '', url)
                }
                else {
                    catapi_show_error();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                catapi_show_error();
            }
        }).done(function () {
            $('.loading-top').hide();
            $('#container').fadeIn(3000);
        });

    });

    $(document).on("click", '#load-more', function (event) {
        var page = parseInt($(this).data('page'));
        $('.loading-bottom').fadeIn();
        $.ajax({
            url: catapi.ajax_url,
            data: { breed: $("#breeds").val(), action: 'catapi_get_cats_wrapper', page: 1 + page },
            dataType: "json",
            type: "get",
            success: function (data) {
                if (data) {
                    var pagination = data[0];
                    var cats = data[1];
                    jQuery.each(cats, function (index, cat) {
                        $('#cats').append('<div class="cat" style="display:none"><div class="cat-image"><a href="'+catapi.cat_url+'?id='+cat.id+'" style="background-image: url(' + cat.url + ');"></a></div><a class="button view-details" href="'+catapi.cat_url+'?id='+cat.id+'">View Details</a></div>');
                        $('.cat').fadeIn(3000);
                    });

                    $('#load-more').data('page', 1 + page);
                    $('#load-more').removeClass('hide-me');
                    if (pagination.current >= pagination.total) {
                        $('#load-more').addClass('hide-me');
                    }
                }
                else {
                    $('#load-more').addClass('hide-me');
                    catapi_show_error();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                $('#load-more').addClass('hide-me');
                catapi_show_error();
            }
        }).done(function () {
            $('.loading-bottom').hide();
        });
    })
});