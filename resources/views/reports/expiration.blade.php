@extends('layouts.app')
@section('title', 'Proximos a caducar')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reports.expiration') }}">Proximos a caducar</a></li>
    </ol>
@endsection

@section('content')
    <div class="card">

        <div class="card-body">
            <table class="table table-striped table-bordered table-responsive-sm">
                <thead>
                    <tr>
                        <th style="width:40px;">Logo</th>
                        <th>Nombre</th>
                        <th>Restan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expirationData as $object)

                    @php
                        $ticketTime = strtotime($object->date);
                        $days = $ticketTime - time();
                    @endphp

                    @if (round($days / 86400) < 60)
                        <tr class="bg-danger">
                    @elseif(round($days/ 86400) < 0) 
                        <tr class="bg-warning">
                    @else
                        <tr class="bg-default">
                    @endif
                            <td class="text-center">
                                @if (File::exists($object->product->imageUrl))
                                    <img src="{{ asset($object->product->imageUrl) }}" style="width: 90px">
                                @else
                                    <img src="{{ asset('images/not-found.png') }}" style="width: 90px">
                                @endif
                            </td>

                            <td>{{ $object->product->name }}</td>
                            <td>
                                {{ round($days / 86400) }} días
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-12">
                    {{ $expirationData->links() }}
                </div>
            </div>


        </div>


        <div class="card-footer">

        </div>
    </div>
@endsection
