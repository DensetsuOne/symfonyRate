$(document).ready(() => {
    $('#conversion-form').submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: '/',
            data: $(this).serialize(),
            method: 'POST',
            dataType: 'json',
            success: (function (response) {
                $('#sum').val(response['sum'])
            }),
        })
    })
})