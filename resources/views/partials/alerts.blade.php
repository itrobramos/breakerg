@if($errors->any())
    @foreach($errors->all() as $error)
        <script type="text/javascript">
           $.toast({

                heading: 'Errores detectado',
                text: '<strong>{{ $error }}</strong>',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 9500
            
            });
        </script>  
    @endforeach
@elseif(Session::has('success'))
    dd(1);
    <script type="text/javascript">
       $.toast({
            heading: 'Procesado',
            text: '{{Session::get("success")}}',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 9500
        
        });
    </script>
@elseif(Session::has('info'))
    
    <script type="text/javascript">
       $.toast({
            heading: 'Información',
            text: '{{Session::get("info")}}',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'info',
            hideAfter: 9500
        
        });
    </script>
@elseif(Session::has('warning'))
    dd(1);
    <script type="text/javascript">
       $.toast({
            heading: 'Importante',
            text: '{{Session::get("warning")}}',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'warning',
            hideAfter: 9500
        
        });
    </script>
@elseif(Session::has('danger'))
    dd(1);
    <script type="text/javascript">
       $.toast({
            heading: 'Error',
            text: '{{Session::get("danger")}}',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'error',
            hideAfter: 9500
        
        });
    </script>
@endif
