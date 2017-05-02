let elementId = {
	'newNumber': '#new-number-',
	'newNumberList': '#new-number-list-',
	'oldNumber': '#old-number-',
	'package': '#package-',
	'voucher': '#voucher-'
};
let dataCount = 0;
let template = '<div class="form-group"><label for="@new-number-key">@new-number-text</label><input id="@new-number-key" list="@new-number-list-key" type="number" class="form-control border-round" value="@new-number-value" onchange="processData(@index)"><datalist id="@new-number-list-key"></datalist></div>' +
	'<div class="form-group"><label for="@old-number-key">@old-number-text</label><input id="@old-number-key" type="number" class="form-control border-round" value="@old-number-value" onchange="processData(@index)"></div>' +
	'<div class="form-group"><label for="@voucher-key">@voucher-text</label><span class="select"><select id="@voucher-key" class="form-control sumo-select border-round" onchange="processData(@index)" multiple></select></span></div>' +
	'<div class="form-group"><label for="@package-key">@package-text</label><span class="select"><select id="@package-key" class="form-control sumo-select border-round" onchange="processData(@index)"></select></span></div>' +
	'<hr/>';

function processCurrentData() {
	let fieldValue = $(fieldKey).val();
	let values = (fieldValue == '') ? [] : JSON.parse(fieldValue);

	$.each(values, function(i, item) {
		addForm(item.new_number, item.old_number, item.package, item.voucher);
	});
}

function setInputValue(index, data) {
	let fieldValue = $(fieldKey).val();
	let values = (fieldValue == '') ? [] : JSON.parse(fieldValue);
	let errorMessage = '';

	if (index <= values.length) {
		values[index - 1] = data;
	} else {
		values.push(data);
	}

	if (!salesEdit) {
		if (data['new_number'] != '' && !isNumberExists(data['new_number'])) {
			errorMessage = 'Nomor SP tidak terdaftar';
		} else if (getAllVoucherValue(values) > balance) {
			errorMessage = 'Saldo anda tidak cukup';
		}
		if (errorMessage != '') {
			alert(errorMessage);
			values.splice(index - 1, 1);
			resetField(index);
		}

		flagTakenNumber(values);
	}
	setBalance(values);
	$(fieldKey).val(JSON.stringify(values));
}

function addForm(newNumber = '', oldNumber = '', packageIndex = 0, voucherValues = []) {
	$('#number-sales').append(parseTemplate(newNumber, oldNumber));
	if (!salesEdit) {
		fillNumberData(dataCount);
	}
	fillPackageData();
	fillVoucherData();

	$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});

	$(elementId.package + dataCount + '.sumo-select')[0].sumo.selectItem(packageIndex);
	$.each(voucherValues, function (i, item) {
		$(elementId.voucher + dataCount + '.sumo-select')[0].sumo.selectItem(item);
	});
}

function processData(index) {
	let newNumberData = $(elementId.newNumber + index).val();
	let oldNumberData = $(elementId.oldNumber + index).val();
	let packageData = $(elementId.package + index).val();
	let voucherData = $(elementId.voucher + index).val();

	setInputValue(index, {
		'new_number': newNumberData,
		'old_number': oldNumberData,
		'package': packageData,
		'voucher': voucherData
	});
}

function setBalance(values) {
	$('#user-balance').html(
		'Rp. ' + (balance - getAllVoucherValue(values)).toLocaleString()
	);
}

function isNumberExists(number) {
	result = false;

	$.each(numberList, function (key, data) {
		if (data['number'] == number) {
			result = true;
			return false;
		}
	});
	return result;
}

function getAllVoucherValue(values) {
	let total = 0;
	$.each(values, function (key, value) {
		$.each(value['voucher'], function (i, voucher) {
			total += parseInt(voucher);
		});
	});
	return total;
}

function flagTakenNumber(values) {
	$.each(numberList, function (key, data) {
		$.each(values, function (i, value) {
			data['is_taken'] = (value['new_number'] == data['number']) ? 1 : 0;
		});
	});
}

function fillNumberData(index) {
	$(elementId.newNumberList + index).empty();
	$.each(numberList, function (i, item) {
		if (item['is_taken'] == 0) {
			$(elementId.newNumberList + index).append($('<option>', {
				value: item['number']
			}));
		}
	});
}

function fillPackageData() {
	$.each(fieldData['package']['values'], function (i, item) {
		$(elementId.package + dataCount).append($('<option>', {
			value: item['key'],
			text: item['text']
		}));
	});
}

function fillVoucherData() {
	$.each(fieldData['voucher']['values'], function (i, item) {
		$(elementId.voucher + dataCount).append($('<option>', {
			value: item['key'],
			text: item['text']
		}));
	});
}

function resetField(index) {
	$(elementId.newNumber + index).val('');
	$(elementId.oldNumber + index).val('');
	resetSumoSelect(elementId.package + index + '.sumo-select', 0);
	resetSumoSelect(elementId.voucher + index + '.sumo-select', -1);
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

function parseTemplate(newNumber = '', oldNumber = '') {
	dataCount++;
	return template.replaceAll('@new-number-key', 'new-number-' + dataCount)
		.replaceAll('@new-number-list-key', 'new-number-list-' + dataCount)
		.replaceAll('@new-number-text', fieldData['number']['new']['text'])
		.replaceAll('@new-number-value', newNumber)
		.replaceAll('@old-number-key', 'old-number-' + dataCount)
		.replaceAll('@old-number-text', fieldData['number']['old']['text'])
		.replaceAll('@old-number-value', oldNumber)
		.replaceAll('@package-key', 'package-' + dataCount)
		.replaceAll('@package-text', fieldData['package']['text'])
		.replaceAll('@voucher-key', 'voucher-' + dataCount)
		.replaceAll('@voucher-text', fieldData['voucher']['text'])
		.replaceAll('@index', dataCount);
}

String.prototype.replaceAll = function (search, replacement) {
	return this.split(search).join(replacement);
};