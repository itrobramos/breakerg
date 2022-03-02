@extends('layouts.business')
@section('content')

    <div class="content-wrapper">
      

        <section class="content">
     
            <img src="images/welcome.png" alt="" class="" style="object-fit: cover;width:100%;height:100%">

        </section>
    </div>


    <script>
        $(document).ready(function() {
            $('#table2').DataTable();
        });
    </script>



@endsection
