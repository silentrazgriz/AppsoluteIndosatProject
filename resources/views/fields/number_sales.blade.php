<div id="number-sales">
	<input type="hidden" id="{{ $field['key'] }}-input" name="{{ $field['key'] }}" value="">
</div>
<div class="form-group text-center">
	<button type="button" class="btn btn-primary border-round" onclick="addForm()">TAMBAH PEMBELIAN</button>
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
		let template = '<div class="form-group"><label for="@number-key">@number-text</label><input id="@number-key" list="@number-list-key" type="number" class="form-control border-round" onchange="processData(@index)"><datalist id="@number-list-key"></datalist></div>' +
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
				'number': numberData,
				'package': packageData,
				'voucher': voucherData
			});
		}

		function setInputValue(index, data) {
			let fieldValue = $('#{{ $field['key'] }}-input').val();
			let values = (fieldValue == '') ? [] : JSON.parse(fieldValue);
			let errorMessage = '';

			if (index <= values.length) {
				values[index - 1] = data;
			} else {
				values.push(data);
			}

			if (data['number'] != '' && !isNumberExists(data['number'])) {
				errorMessage = 'Nomor SP tidak terdaftar';
			} else if (getAllVoucherValue(values) > balance) {
				errorMessage = 'Saldo anda tidak cukup';
			}
			if (errorMessage != '') {
				alert(errorMessage);
				values.splice(index - 1, 1);
				resetField(index);
			}

			setBalance(values);
			flagTakenNumber(values);
			$('#{{ $field['key'] }}-input').val(JSON.stringify(values));
		}

		function addForm() {
			$('#number-sales').append(parseTemplate());
			fillNumberData(dataCount);
			fillPackageData();
			fillVoucherData();

			$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});
		}

		function setBalance(values) {
			$('#user-balance').html(
				'Rp. ' + (balance - getAllVoucherValue(values)).toLocaleString()
			);
		}

		function isNumberExists(number) {
			result = false;

			$.each(numberList, function(key, data) {
				if (data['number'] == number) {
					result = true;
					return false;
				}
			});
			return result;
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

		function fillNumberData(index) {
			$('#number-list-' + index).empty();
			$.each(numberList, function (i, item) {
				if (item['is_taken'] == 0) {
					$('#number-list-' + index).append($('<option>', {
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

		function resetField(index) {
			$('#number-' + index).val('');
			resetSumoSelect('#voucher-' + index + '.sumo-select', -1);
			resetSumoSelect('#package-' + index + '.sumo-select', 0);
		}

		function resetSumoSelect(selector, value) {
			let sumoSelect = $(selector)[0].sumo;
			if (value == -1) {
				sumoSelect.unSelectAll();
			} else {
				sumoSelect.selectItem(value);
			}
			sumoSelect.reload();
		}

		function parseTemplate() {
			dataCount++;
			return template.replaceAll('@number-key', 'number-' + dataCount)
				.replaceAll('@number-list-key', 'number-list-' + dataCount)
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