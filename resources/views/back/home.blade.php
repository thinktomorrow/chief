@extends('back._layouts.master')

@section('page-title')
Dashboard
@stop

@section('topbar-right')

@stop

@section('content')
<div class="row">
  <div class="col-md-4">
  <div class="panel panel-tile text-center br-a br-grey">
    <div class="panel-body">
      @role('Admin')
        <img src="{{ asset('assets/back/img/dashboard.png')}}" alt="Dashboard">
        <h1 class="fs30 mt5 mbn">Ahéhee {{ Auth::user()->firstname }}</h1>
        <h6 class="text-system">Your new name will be "{{ Auth::user()->getAlterEgo() }}"</h6>
      @endrole
      @role('User')
        <img src="{{ asset('assets/back/img/dashboard-user.png')}}" alt="Dashboard">
        <h1 class="fs30 mt5 mbn">Ahéhee {{ Auth::user()->firstname }}</h1>
        <h6 class="text-system">Your new name will be "{{ Auth::user()->getAlterEgo() }}"</h6>
      @endrole
    </div>
    <div class="panel-footer br-t p12">
      <span class="fs11">
        Ga naar je <b>account</b>
        <i class="fa fa-arrow-right pr5 text-default"></i>
      </span>
    </div>
  </div>
</div>

            <!-- // ORDER COUNTER -->
            <div class="col-md-4">
              <div class="panel panel-tile text-center br-a br-grey">
                <div class="panel-body">
                  <h1 class="fs30 mt5 mbn">16</h1>
                  <h6 class="text-success">BESTELLINGEN</h6>
                </div>
                <div class="panel-footer br-t p12">
                  <span class="fs11">
                    Ga naar je <b>bestellingen</b>
                    <i class="fa fa-arrow-right pr5 text-success"></i>
                  </span>
                </div>
              </div>
            </div>
            <!-- // PRODUCT COUNTER -->
            <div class="col-md-4">
              <div class="panel panel-tile text-center br-a br-grey">
                <div class="panel-body">
                  <h1 class="fs30 mt5 mbn">248</h1>
                  <h6 class="text-warning">PRODUCTEN</h6>
                </div>
                <div class="panel-footer br-t p12">
                  <span class="fs11">
                    Ga naar je <b>catalogus</b>
                    <i class="fa fa-arrow-right pr5 text-warning"></i>
                  </span>
                </div>
              </div>
            </div>
            <!-- // USER COUNTER -->
            <div class="col-md-4">
              <div class="panel panel-tile text-center br-a br-grey">
                <div class="panel-body">
                  <h1 class="fs30 mt5 mbn">74</h1>
                  <h6 class="text-danger">KLANTEN</h6>
                </div>
                <div class="panel-footer br-t p12">
                  <span class="fs11">
                    Ga naar de <b>gebruikers</b>
                    <i class="fa fa-arrow-right pr5 text-danger"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
@stop