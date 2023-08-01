@extends('layouts.app')

@section('content')
    <index-component csrf_token="{{@csrf_token()}}"></index-component>
@endsection
