@extends('layouts.login')

@section('content')
<div class="container-fuild">
  
    <div class="container-fluid mt-3">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
    </div>

   
</div>
@endsection
