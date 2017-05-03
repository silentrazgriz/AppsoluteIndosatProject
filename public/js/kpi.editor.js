let kpiCount = 0;
let kpiValueCount = 0;
let kpiElementId = {
	'kpiKey': '#kpi-',
	'kpiValues': '#kpi-values-',
	'kpiTypeId': '#kpi-type-',
	'kpiFieldId': '#kpi-field-',
	'kpiRequiredId': '#kpi-required-',

	'kpiTextName' : 'kpi-text',
	'kpiShortTextName': 'kpi-short-text',
	'kpiTypeName' : 'kpi-type',
	'kpiGoalName' : 'kpi-goal',
	'kpiUnitName' : 'kpi-unit',
	'kpiReportUnitName' : 'kpi-report-unit',
	'kpiRequiredName' : 'kpi-required',
	'kpiFieldName' : 'kpi-field',

	'kpiValueName': 'kpi-value',

	'kpiTextPlaceholder' : 'Nama KPI',
	'kpiShortTextPlaceholder': 'Kode Singkat KPI',
	'kpiGoalPlaceholder' : 'Target KPI',
	'kpiUnitPlaceholder' : 'Satuan Unit',
	'kpiReportUnitPlaceholder' : 'Unit KPI',
};

let kpiDataSelector = {
	'kpiText': '*[data-name*=' + kpiElementId.kpiTextName + ']',
	'kpiShortText': '*[data-name*=' + kpiElementId.kpiShortTextName + ']',
	'kpiType': '*[data-name*=' + kpiElementId.kpiTypeName + ']',
	'kpiGoal': '*[data-name*=' + kpiElementId.kpiGoalName + ']',
	'kpiUnit' : '*[data-name*=' + kpiElementId.kpiUnitName + ']',
	'kpiReportUnit' : '*[data-name*=' + kpiElementId.kpiReportUnitName + ']',
	'kpiRequired' : '*[data-name*=' + kpiElementId.kpiRequiredName + ']',
	'kpiField' : '*[data-name*=' + kpiElementId.kpiFieldName + ']',
	'kpiValue': '*[data-name*=' + kpiElementId.kpiValueName + ']',
};

let kpiTypes = [
	{ 'key': 'count', 'description': 'Jumlah jawaban (COUNT)' },
	{ 'key': 'sum', 'description': 'Total jawaban (SUM)' },
	{ 'key': 'require_one', 'description': 'Pertanyaan diisi' },
	{ 'key': 'require_multiple', 'description': 'Pertanyaan diisi dengan beberapa jawaban' },
	{ 'key': 'require_one_field', 'description': 'Satu pertanyaan diisi dalam satu bagian' },
	{ 'key': 'require_multiple_field', 'description': 'Beberapa pertanyaan diisi dari satu bagian' },
];

let kpiTypeWithValues = [
	'require_one',
	'require_multiple',
	'require_one_field',
	'require_multiple_field'
];

let kpiTemplate = '<div id="@kpi-key" class="kpi-group"><h5>@kpi-description <button class="btn btn-xs btn-danger btn-delete-element" onclick="removeKpiElement(\'@kpi-key\')">Hapus</button></h5><div class="form-group"><label class="col-xs-2 control-label">Tipe KPI</label><div class="col-xs-10"><select id="@kpi-type-id" class="form-control sumo-select border-round right-min-15" data-name="@kpi-type-name" data-index="@kpi-index"></select></div></div><div class="form-group"><label class="col-xs-2 control-label">Field KPI</label><div class="col-xs-10"><select id="@kpi-field-id" class="form-control sumo-select border-round right-min-15 kpi-field" data-name="@kpi-field-name" data-index="@kpi-index"></select></div></div><div class="form-group"><label class="col-xs-2 control-label">Required Field</label><div class="col-xs-10"><select id="@kpi-required-id" class="form-control sumo-select border-round right-min-15 kpi-required" data-name="@kpi-required-name"></select></div></div><div class="form-group"><label class="col-xs-2 control-label">Nama KPI</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@kpi-text-name" placeholder="@kpi-text-placeholder" value="@kpi-text-value" required></div></div><div class="form-group"><label class="col-xs-2 control-label">Kode KPI</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@kpi-short-text-name" placeholder="@kpi-short-text-placeholder" value="@kpi-short-text-value" required></div></div><div class="form-group"><label class="col-xs-2 control-label">Target KPI</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@kpi-goal-name" placeholder="@kpi-goal-placeholder" value="@kpi-goal-value" required></div></div><div class="form-group"><label class="col-xs-2 control-label">Satuan Unit</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@kpi-unit-name" placeholder="@kpi-unit-placeholder" value="@kpi-unit-value"></div></div><div class="form-group"><label class="col-xs-2 control-label">Unit KPI</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@kpi-report-unit-name" placeholder="@kpi-report-unit-placeholder" value="@kpi-report-unit-value" required></div></div><div class="form-group kpi-values-label"><label>KPI Values</label></div><div id="@kpi-value-id" class="kpi-values"></div></div>';

let kpiValueTemplate = '<label class="checkbox"><input type="checkbox" data-name="@kpi-value-name" data-question="@kpi-index" value="@kpi-value-key" @kpi-value-checked><span class="indicator"><span></span></span>@kpi-value-text</label>';

function addKpi(kpiTypeValue = '', kpiFieldValue = '', kpiRequiredValue = '', kpiTextValue = '', kpiShortTextValue = '', kpiGoalValue = '', kpiUnitValue = '', kpiReportUnitValue = 1) {
	$('#kpi-data').append(parseKpiTemplate(kpiTextValue, kpiShortTextValue, kpiGoalValue, kpiUnitValue, kpiReportUnitValue));
	fillKpiTypes(kpiTypeValue);
	fillKpiField(kpiElementId.kpiKey + kpiCount + ' .kpi-field', kpiCount, true, kpiFieldValue);
	fillKpiField(kpiElementId.kpiKey + kpiCount + ' .kpi-required', kpiCount, true, kpiRequiredValue);

	if (kpiTypeWithValues.indexOf(kpiTypeValue) == -1) {
		$(kpiElementId.kpiKey + kpiCount + ' .kpi-values, ' + kpiElementId.kpiKey + kpiCount + ' .kpi-values-label').hide();
	}

	bindKpiListener();
}

function addKpiValue(kpiIndex, kpiValueKey) {
	let kpiType = $(kpiElementId.kpiTypeId + kpiIndex).val();
	let kpiField = $(kpiElementId.kpiFieldId + kpiIndex).val();

	$(kpiElementId.kpiValues + kpiIndex).html('');
	if (kpiField != '') {
		$.each(getSurveyData(), function(i, step) {
			if (kpiType.indexOf('field') != -1) {
				if (step['key'] == kpiField) {
					$.each(step['questions'], function (j, question) {
						$(kpiElementId.kpiValues + kpiIndex).append(parseKpiValueTemplate(kpiIndex, question['key'], question['text'], (kpiValueKey.indexOf(question['key']) != -1)));
					});
				}
			} else {
				$.each(step['questions'], function (j, question) {
					if (question['key'] == kpiField) {
						$.each(question['values'], function (k, value) {
							$(kpiElementId.kpiValues + kpiIndex).append(parseKpiValueTemplate(kpiIndex, value['key'], value['text'], (kpiValueKey.indexOf(value['key']) != -1)));
						});
					}
				});
			}
		});
	}

	bindKpiListener();
}

function fillKpiField(selector, kpiIndex, needEmpty = false, fieldValue) {
	let kpiType = $(kpiElementId.kpiTypeId + kpiIndex).val();
	let kpiField = $(selector);
	kpiField.find('option').remove();

	if (needEmpty) {
		kpiField.append($('<option>', {
			value: '',
			text: 'Tidak ada'
		}));
	}

	$.each(getSurveyData(), function(i, step) {
		if (kpiType.indexOf('field') != -1) {
			if (step['key'] == fieldValue) {
				kpiField.append($('<option>', {
					value: step['key'],
					text: step['key'],
					selected: true
				}));
			} else {
				kpiField.append($('<option>', {
					value: step['key'],
					text: step['key']
				}));
			}
		} else {
			$.each(step['questions'], function (j, question) {
				let questionKey = question['key'];
				if (questionKey == 'sales') {
					let salesKeys = ['sales.new_number', 'sales.old_number', 'sales.package', 'sales.voucher'];
					$.each(salesKeys, function(k, key) {
						if (key == fieldValue) {
							kpiField.append($('<option>', {
								value: key,
								text: key,
								selected: true
							}));
						} else {
							kpiField.append($('<option>', {
								value: key,
								text: key
							}));
						}
					});
				} else {
					if (questionKey == fieldValue) {
						kpiField.append($('<option>', {
							value: question['key'],
							text: question['key'],
							selected: true
						}));
					} else {
						kpiField.append($('<option>', {
							value: question['key'],
							text: question['key']
						}));
					}
				}
			});
		}
	});

	kpiField.each(function(i, field) {
		field.sumo.reload();
	});
}

function fillKpiTypes(kpiTypeValue) {
	$.each(kpiTypes, function(i, item) {
		if (item.key == kpiTypeValue) {
			$(kpiElementId.kpiTypeId + kpiCount).append($('<option>', {
				value: item.key,
				text: item.description,
				selected: true
			}));
		} else {
			$(kpiElementId.kpiTypeId + kpiCount).append($('<option>', {
				value: item.key,
				text: item.description
			}));
		}
	});
	$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});
}

function processKpiCurrentData() {
	let fieldValue = $('input[name="kpi"]').val();
	let kpis = (fieldValue == '') ? [] : JSON.parse(fieldValue);

	$.each(kpis, function(i, kpi) {
		addKpi(kpi['type'], kpi['field'], kpi['required'], kpi['text'], kpi['short_text'], kpi['goal'], kpi['unit'], kpi['report_unit']);
		if (kpiTypeWithValues.indexOf(kpi['type']) != -1) {
			addKpiValue(kpiCount, kpi['values']);
		}
	});
}

function processKpiChange() {
	let data = [];
	let kpiTypes = $(kpiDataSelector.kpiType);
	let kpiFields = $(kpiDataSelector.kpiField);
	let kpiRequires = $(kpiDataSelector.kpiRequired);
	let kpiTexts = $(kpiDataSelector.kpiText);
	let kpiShortTexts = $(kpiDataSelector.kpiShortText);
	let kpiGoals = $(kpiDataSelector.kpiGoal);
	let kpiUnits = $(kpiDataSelector.kpiUnit);
	let kpiReportUnits = $(kpiDataSelector.kpiReportUnit);

	for (let i = 0; i < kpiTypes.length; i++) {
		let kpi = {
			'text': kpiTexts[i].value,
			'short_text': kpiShortTexts[i].value,
			'field': kpiFields[i].value,
			'type': kpiTypes[i].value,
			'goal': kpiGoals[i].value,
			'unit': kpiUnits[i].value,
			'report_unit': kpiReportUnits[i].value,
			'required': kpiRequires[i].value
		};
		if (kpiTypeWithValues.indexOf(kpiTypes[i].value) != -1) {
			let kpiIndex = i + 1;
			let kpiValues = $(kpiElementId.kpiValues + kpiIndex + ' ' + kpiDataSelector.kpiValue);

			let values = [];
			for (let j = 0; j < kpiValues.length; j++) {
				if (kpiValues[j].checked) {
					values.push(kpiValues[j].value);
				}
			}

			kpi['values'] = values;
		}

		data.push(kpi);
	}

	$('input[name="kpi"]').val(JSON.stringify(data));
}

function processKpiFieldChange() {
	let kpiIndex = $(this).data('index');
	addKpiValue(kpiIndex, []);
}

function processKpiTypeChange() {
	let kpiIndex = $(this).data('index');
	let kpiType = $(this).val();

	$(kpiElementId.kpiValues + kpiIndex).html('');
	if (kpiTypeWithValues.indexOf(kpiType) != -1) {
		fillKpiField(kpiElementId.kpiKey + kpiIndex + ' .kpi-field', kpiIndex, true, '');
		$(kpiElementId.kpiKey + kpiIndex + ' .kpi-values, ' + kpiElementId.kpiKey + kpiIndex + ' .kpi-values-label').show();
	} else {
		$(kpiElementId.kpiKey + kpiIndex + ' .kpi-values, ' + kpiElementId.kpiKey + kpiIndex + ' .kpi-values-label').hide();
	}
	$(kpiElementId.kpiTypeId + kpiIndex + '.sumo-select')[0].sumo.reload();
}

function bindKpiListener() {
	$('*[data-name="kpi-type"]').unbind('change', processKpiTypeChange).bind('change', processKpiTypeChange);
	$('*[data-name="kpi-field"]').unbind('change', processKpiFieldChange).bind('change', processKpiFieldChange);
	$('*[data-name*="kpi-"]').unbind('change', processKpiChange).bind('change', processKpiChange);

	processKpiChange();
}

function parseKpiTemplate(kpiTextValue = '', kpiShortTextValue = '', kpiGoalValue = '', kpiUnitValue = '', kpiReportUnitValue = 1) {
	kpiCount++;
	return kpiTemplate.replaceAll('@kpi-text-value', kpiTextValue)
		.replaceAll('@kpi-short-text-value', kpiShortTextValue)
		.replaceAll('@kpi-goal-value', kpiGoalValue)
		.replaceAll('@kpi-unit-value', kpiUnitValue)
		.replaceAll('@kpi-report-unit-value', kpiReportUnitValue)
		.replaceAll('@kpi-text-placeholder', kpiElementId.kpiTextPlaceholder)
		.replaceAll('@kpi-short-text-placeholder', kpiElementId.kpiShortTextPlaceholder)
		.replaceAll('@kpi-goal-placeholder', kpiElementId.kpiGoalPlaceholder)
		.replaceAll('@kpi-unit-placeholder', kpiElementId.kpiUnitPlaceholder)
		.replaceAll('@kpi-report-unit-placeholder', kpiElementId.kpiReportUnitPlaceholder)
		.replaceAll('@kpi-text-name', kpiElementId.kpiTextName)
		.replaceAll('@kpi-short-text-name', kpiElementId.kpiShortTextName)
		.replaceAll('@kpi-goal-name', kpiElementId.kpiGoalName)
		.replaceAll('@kpi-unit-name', kpiElementId.kpiUnitName)
		.replaceAll('@kpi-report-unit-name', kpiElementId.kpiReportUnitName)
		.replaceAll('@kpi-type-name', kpiElementId.kpiTypeName)
		.replaceAll('@kpi-required-name', kpiElementId.kpiRequiredName)
		.replaceAll('@kpi-field-name', kpiElementId.kpiFieldName)
		.replaceAll('@kpi-type-id', 'kpi-type-' + kpiCount)
		.replaceAll('@kpi-required-id', 'kpi-required-' + kpiCount)
		.replaceAll('@kpi-field-id', 'kpi-field-' + kpiCount)
		.replaceAll('@kpi-value-id', 'kpi-values-' + kpiCount)
		.replaceAll('@kpi-description', 'KPI ke-' + kpiCount)
		.replaceAll('@kpi-key', 'kpi-' + kpiCount)
		.replaceAll('@kpi-index', kpiCount);
}

function parseKpiValueTemplate(kpiIndex, kpiValueKey, kpiValueText, isChecked) {
	kpiValueCount++;
	return kpiValueTemplate.replaceAll('@kpi-value-name', kpiElementId.kpiValueName)
		.replaceAll('@kpi-value-key', kpiValueKey)
		.replaceAll('@kpi-value-text', kpiValueText)
		.replaceAll('@kpi-value-checked', (isChecked ? 'checked' : ''))
		.replaceAll('@kpi-index', kpiIndex);
}

function getSurveyData() {
	let fieldValue = $('input[name="survey"]').val();
	return (fieldValue == '') ? [] : JSON.parse(fieldValue);
}

String.prototype.replaceAll = function (search, replacement) {
	return this.split(search).join(replacement);
};