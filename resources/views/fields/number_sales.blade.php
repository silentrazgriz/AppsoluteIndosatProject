<div id="number-sales">
	<input type="hidden" id="{{ $field['key'] }}-input" name="{{ $field['key'] }}" value="">
</div>
<div class="form-group text-center">
	<button type="button" class="btn btn-primary border-round" onclick="addForm()">Tambah Pembelian</button>
</div>
<div class="form-group">
	<label>Saldo kamu</label>
	<div>
		<span class="icon-red"><i class="fa fa-dollar"></i></span>
		<span id="user-balance" class="user-balance">Rp. {{ number_format($user['balance']) }}</span>
	</div>
</div>

@section('scripts')
	<script>
		let numberList = {!! json_encode($numbers) !!};
		let data = {!! json_encode($field) !!};
		let dataCount = 0;
		let balance = {{ $user['balance'] }};
		let template = '<div class="form-group"><label for="@number-key">@number-text</label><input id="@number-key" list="number-list" type="number" class="form-control border-round" onchange="processData(@index)"><datalist id="number-list"></datalist></div>' +
			'<div class="form-group"><label for="@voucher-key">@voucher-text</label><span class="select"><select id="@voucher-key" class="form-control sumo-select border-round" onchange="processData(@index)" multiple></select></span></div>' +
			'<div class="form-group"><label for="@package-key">@package-text</label><span class="select"><select id="@package-key" class="form-control sumo-select border-round" onchange="processData(@index)"></select></span></div>' +
			'<hr/>';

		$(function() {
			addForm();
		});

		function processData(index) {
			let numberData = $('#number-' + index).val();
			let packageData = $('#package-' + index).val();
			let voucherData = $('#voucher-' + index).val();

			setInputValue(index, {
				"number": numberData,
				"package": packageData,
				"voucher": voucherData
			});
		}

		function setInputValue(index, data) {
			let fieldValue = $('#{{ $field['key'] }}-input').val();
			let values = (fieldValue == '') ? [] : JSON.parse(fieldValue);

			if (index <= values.length) {
				values[index - 1] = data;
			} else {
				values.push(data);
			}

			setBalance(values);
			flagTakenNumber(values);
			$('#{{ $field['key'] }}-input').val(JSON.stringify(values));
		}

		function addForm() {
			$('#number-sales').append(parseTemplate());
			fillNumberData();
			fillPackageData();
			fillVoucherData();

			$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});
		}

		function setBalance(values) {
			$('#user-balance').html(
				'Rp. ' + numberFormat(balance - getAllVoucherValue(values))
			);
		}

		function getAllVoucherValue(values) {
			let total = 0;
			$.each(values, function(key, value) {
				$.each(value['voucher'], function(i, voucher) {
					total += parseInt(voucher);
				});
			});
			return total;
		}

		function flagTakenNumber(values) {
			$.each(numberList, function(key, data) {
				$.each(values, function(i, value) {
					data['is_taken'] = (value['number'] == data['number']) ? 1 : 0;
				});
			});
		}

		function fillNumberData() {
			$('#number-list').empty();
			$.each(numberList, function (i, item) {
				if (item['is_taken'] == 0) {
					$('#number-list').append($('<option>', {
						value: item['number']
					}));
				}
			});
		}

		function fillPackageData() {
			$.each(data['package']['values'], function (i, item) {
				$('#package-' + dataCount).append($('<option>', {
					value: item['key'],
					text: item['text']
				}));
			});
		}

		function fillVoucherData() {
			$.each(data['voucher']['values'], function (i, item) {
				$('#voucher-' + dataCount).append($('<option>', {
					value: item['key'],
					text: item['text']
				}));
			});
		}

		function numberFormat(number) {
			return number.toString().replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
		}

		function parseTemplate() {
			dataCount++;
			return template.replaceAll('@number-key', 'number-' + dataCount)
				.replaceAll('@number-text', data['number']['text'])
				.replaceAll('@number-placeholder', data['number']['placeholder'])
				.replaceAll('@package-key', 'package-' + dataCount)
				.replaceAll('@package-text', data['package']['text'])
				.replaceAll('@voucher-key', 'voucher-' + dataCount)
				.replaceAll('@voucher-text', data['voucher']['text'])
				.replaceAll('@index', dataCount);
		}

		String.prototype.replaceAll = function(search, replacement) {
			return this.split(search).join(replacement);
		}
	</script>
@append