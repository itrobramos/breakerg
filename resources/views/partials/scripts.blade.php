
<script src="{{env('DEPLOY_URL')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


<script>
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('form').append('{{csrf_field()}}');

    $(document).on('click','.button-destroy',function(e){   
        e.stopPropagation();
        e.preventDefault();
        var a = $(this);
        var _token = $('meta[name="csrf-token"]').attr('content');
        swal.fire({   
            title: a.data('trans-title'),
            text: a.data('trans-subtitle'),
            icon: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",
            confirmButtonText: a.data('trans-button-confirm'), 
            cancelButtonText: a.data('trans-button-cancel'), 
        }).then( (result) =>{
            if (result.value) {
                var form = 
                $('<form>', {
                    'method': 'POST',
                    'action': a.attr('href')
                });

                var token = 
                $('<input>', {
                    'name': '_token',
                    'type': 'hidden',
                    'value': _token
                });
                

                var hiddenInput =
                $('<input>', {
                    'name': '_method',
                    'type': 'hidden',
                    'value': a.data('method')
                });

                form.append(token, hiddenInput).appendTo('body').submit();
            }
        })
    })

 
})



</script>