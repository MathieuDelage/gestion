$('#add_article_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/add",
        {
            type : 'post',
            data : {
                'reference' : $('#add_article_reference').val(),
                'name' : $('#add_article_name').val(),
                'price' : $('#add_article_price').val()
            },
            success : function(data) {
                $('#add_article_result').empty().append(data['message']);
            },
            error : function (e){
                $('#add_article_result').append("e.code + ' ' + e.message");
            }
        }
    )
});

$('#get_article_by_ref_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/get_article_ref/",
        {
            type : 'get',
            data : {
                'reference' : $('#get_article_by_ref_reference').val()
            },
            dataType: 'json',
            success : function(data) {
                $('#get_article_by_ref_result').empty();
                $('#edit').hide();
                $('#stock').hide();
                    if(data['message']){
                        $('#get_article_by_ref_result').append(data['message']);
                    } else {
                        $('#get_article_by_ref_submit').html("Nouvelle recherche");
                        $('#edit').show();
                        $('#edit_article_reference').val(data['reference']).attr("disabled", "disabled");
                        $('#edit_article_name').val(data['name']);
                        $('#edit_article_price').val(data['price']);
                        $('#edit_article_result').empty();
                    }
                },
            error : function (data){
                $('#get_article_by_ref_result').empty().append("e.code + ' ' + e.message");
            }
        }
    )
});

$('#edit_article_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/edit",
        {
            type : 'put',
            data : {
                'reference' : $('#edit_article_reference').val(),
                'name' : $('#edit_article_name').val(),
                'price' : $('#edit_article_price').val()
            },
            success : function(data) {
                $('#edit_article_result').empty().append(data['message']);
            },
            error : function (){
                $('#edit_article_result').append("Erreur dans l'édition de l'article");
            }
        }
    )
});

$('#get_article_by_name_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/get_article_name",
        {
            type : 'get',
            data : {
                'name' : $('#get_article_by_name_name').val()
            },
            dataType: 'json',
            success : function(data) {
                $('#get_article_by_name_result').empty();
                $('#edit').hide();
                if(data['message']){
                    $('#get_article_by_name_result').append(data['message']);
                } else {
                    $('#get_article_by_name_submit').html("Nouvelle recherche");
                    $('#edit').show();
                    $('#edit_article_reference').val(data['reference']).attr("disabled", "disabled");
                    $('#edit_article_name').val(data['name']);
                    $('#edit_article_price').val(data['price']);
                    $('#edit_article_result').empty();
                }
            }
        }
    )
});

$('#get_article_price_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/get_article_interval",
        {
            type : 'get',
            data : {
                'min' : $('#get_article_price_min').val(),
                'max' : $('#get_article_price_max').val(),
            },
            dataType: 'json',
            success : function(data) {
                $('#get_article_price_result').empty();
                $('#interval_result').hide();
                if(data['message']){
                    $('#get_article_price_result').append(data['message']);
                } else {
                    $('#interval_result').show();
                    $('#sortBy').show();
                    for (let i = 0; i < data.length; i++){
                        $('#interval_tbody').append(
                            "<tr>" +
                            "<th scope='col'>" + data[i]['reference'] +
                            "<th scope='col'>" + data[i]['name'] +
                            "<th scope='col'>" + data[i]['price'] +
                            "</tr>"
                        );
                    }
                }
            },
            error : function (data){
                $('#get_article_by_ref_result').empty().append("e.code + ' ' + e.message");
            }
        }
    )
});

$('#add_stock_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/get_warehouse",
        {
            type : 'get',
            dataType: 'json',
            success : function(data) {
                $('#stock').show();
                $('#warehouse_list').show();
                for (let i = 0; i < data.length; i++){
                    $('#warehouse_tbody').append(
                        "<tr>" +
                        "<th scope='col'>" + data[i]['name'] + "</th>" +
                        "</tr>"
                    );
                }
            }
        }
    )
});

$('#add_article_stock_submit').click(function(e){
    e.preventDefault();
    $.ajax(
        "/article/add_article_stock",
        {
            type : 'post',
            data : {
                'reference' : $('#edit_article_reference').val(),
                'warehouse' : $('#add_article_stock_warehouse').val(),
                'amount' : $('#add_article_stock_amount').val()
            },
            success : function(data) {
                $('#add_article_stock_result').empty().append(data['message']);
            },
            error : function (e){
                $('#add_article_stock_result').append("e.code + ' ' + e.message")
            }
        }
    )
});