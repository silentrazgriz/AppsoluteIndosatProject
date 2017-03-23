<div class="row">
	<div class="col-xs-12 text-center">
		{{ $date }}
	</div>
</div>
<div class="sales-home">
	<div class="row">
		<div class="col-xs-2">
			<span class="icon-red"><i class="fa fa-calendar" aria-hidden="true"></i></span>
		</div>
		<div class="col-xs-4">
			Event A
		</div>
		<div class="col-xs-2">
			<span class="icon-red"><i class="fa fa-dollar" aria-hidden="true"></i></span>
		</div>
		<div class="col-xs-4">
			Rp. 500.000
		</div>
	</div>
	<div class="row">
		<div class="col-xs-2">
			<span class="icon-red"><i class="fa fa-file-text" aria-hidden="true"></i></span>
		</div>
		<div class="col-xs-4">
			100 Success report
		</div>
		<div class="col-xs-2">
			<span class="icon-red"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
		</div>
		<div class="col-xs-4">
			5 Failed report
		</div>
	</div>
	<div class="row">
		<div class="col-xs-2">
			<span class="icon-red"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
		</div>
		<div class="col-xs-10">
			Pesanggrahan, Kembangan, Meruya Utara, DKI Jakarta 11620
		</div>
	</div>
</div>
<div class="row text-center">
	<a href="#" class="btn btn-primary border-round"><i class="fa fa-plus-circle" aria-hidden="true"></i> REPORT</a>
</div>
@include('fields.geomap')
<div class="row text-center">
	<a href="{{ route('logout') }}" class="btn btn-primary border-round">LOGOUT</a>
</div>