@extends('app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('plugins/adminlte/css/AdminLTE.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('plugins/adminlte/css/skins/skin-yellow.css') }}"/>
	<link rel="stylesheet" href="{{ asset('plugins/datatables.net/css/jquery.dataTables.css') }}"/>
	<link rel="stylesheet" href="{{ asset('plugins/datatables.net/css/responsive.dataTables.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
@append

@section('scripts')
	<script type="text/javascript" src="{{ asset('plugins/adminlte/js/app.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/chartjs/chart.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/datatables.net/js/dataTables.bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/datatables.net/js/dataTables.responsive.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/datatables.net/js/dataTables.scroller.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
@append

@section('body')
	<div class="skin-yellow">
		<div class="wrapper">
			<header class="main-header">
				<a href="#" class="logo">Indosat</a>
				<nav class="navbar navbar-static-top" role="navigation">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><span class="sr-only">Toggle navigation</span></a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li><a href="{{ route('logout') }}">Keluar</a></li>
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar">
				<div class="sidebar">
					<ul class="sidebar-menu">
						<li class="header">CMS</li>
						<li @if($page == 'dashboard') class="active" @endif>
							<a href="{{ route('dashboard') }}"><span><i class="fa fa-dashboard"></i> Dashboard</span></a>
						</li>
						<li class="header">SALES</li>
						<li @if($page == 'create-sales') class="active" @endif>
							<a href="{{ route('create-sales') }}"><span><i class="fa fa-user-plus"></i> Buat Buddies Baru</span></a>
						</li>
						<li @if($page == 'sales-balance') class="active" @endif>
							<a href="{{ route('sales-balance') }}"><span><i class="fa fa-credit-card"></i> Tambah Saldo Buddies</span></a>
						</li>
						<li @if($page == 'sales') class="active" @endif>
							<a href="{{ route('sales') }}"><span><i class="fa fa-users"></i> Lihat Daftar Buddies</span></a>
						</li>
						<li class="header">NOMOR HP</li>
						<li @if($page == 'create-number') class="active" @endif>
							<a href="{{ route('create-number') }}"><span><i class="fa fa-address-card-o"></i> Tambah Nomor HP</span></a>
						</li>
						<li @if($page == 'number') class="active" @endif>
							<a href="{{ route('number') }}"><span><i class="fa fa-address-book-o"></i> Lihat Daftar Nomor HP</span></a>
						</li>
						<li class="header">EVENT</li>
						<li @if($page == 'create-event') class="active" @endif>
							<a href="{{ route('create-event') }}"><span><i class="fa fa-calendar-plus-o"></i> Buat Event</span></a>
						</li>
						<li @if($page == 'event') class="active" @endif>
							<a href="{{ route('event') }}"><span><i class="fa fa-calendar"></i> Lihat Daftar Event</span></a>
						</li>
					</ul>
				</div>
			</aside>
			<div class="content-wrapper">
				<div class="content">
					@yield('content')
				</div>
			</div>
		</div>
	</div>
@endsection