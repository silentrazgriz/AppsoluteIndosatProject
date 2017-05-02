<hr/>
<h4>KPI</h4>
<div id="kpi-data">
</div>
<div class="form-group">
	<button type="button" class="btn btn-info border-round" onclick="addStep()">Tambah KPI</button>
</div>
<input type="hidden" name="kpi" value="@if(isset($field['value'])){{ json_encode($field['value']) }}@endif">

@section('scripts')
	<script>
		let kpiEdit = {{ isset($edit) ? 'true' : 'false' }};
	</script>
	<script>
		let kpiCount = 0;
		let kpiValueCount = 0;
		let elementId = {
			'kpiKey': '#kpi-',
			'kpiValues': '#kpi-values-',
			'kpiTypeId': '#kpi-type-',
			'kpiFieldId': '#kpi-field-',
			'kpiRequiredId': '#kpi-required-',

			'kpiTextName' : 'kpi-text',
			'kpiShortTextName': 'kpi-short-text',
			'kpiTypeName' : 'kpi-type',
			'kpiGoalName' : 'kpi-goal',
			'kpiReportUnitName' : 'kpi-report-unit',
			'kpiRequiredName' : 'kpi-required',
			'kpiFieldName' : 'kpi-field',

			'kpiValueKey': '#kpi-value-',
			'kpiValueDescriptionName': 'kpi-value',
			
			'kpiTextPlaceholder' : 'Nama KPI',
			'kpiShortTextPlaceholder': 'Kode Singkat KPI',
			'kpiGoalPlaceholder' : 'Target KPI',
			'kpiReportUnitPlaceholder' : 'Unit KPI',
		};

		let kpiTypes = [
			{ 'key': 'require_one_field', 'description': 'Minimal diisi 1' },
			{ 'key': 'require_multiple', 'description': 'Semua harus diisi' },
			{ 'key': 'count', 'description': 'Jumlah data' },
			{ 'key': 'price', 'description': 'Total data' },
		];

		let kpiTemplate = '<div id="@kpi-key" class="kpi-group">' +
			'<h5>@kpi-description <button class="btn btn-xs btn-danger btn-delete-survey" onclick="removeKpiElement(\'@kpi-key\')">Hapus</button></h5>' +
			'<div class="form-group"><select id="@kpi-type-id" class="form-control sumo-select border-round right-min-15" data-name="@kpi-type-name"></select></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@kpi-text-name" placeholder="@kpi-text-placeholder" value="@kpi-text-value" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@kpi-short-text-name" placeholder="@kpi-short-text-placeholder" value="@kpi-short-text-value" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@kpi-goal-name" placeholder="@kpi-goal-placeholder" value="@kpi-goal-value" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@kpi-report-unit-name" placeholder="@kpi-report-unit-placeholder" value="@kpi-report-unit-value" required></div>' +
			'<div class="form-group"><select id="@kpi-field-id" class="form-control sumo-select border-round right-min-15" data-name="@kpi-field-name"></select></div>' +
			'<div class="form-group"><select id="@kpi-required-id" class="form-control sumo-select border-round right-min-15" data-name="@kpi-required-name"></select></div>' +
			'<div id="@kpi-value-id" class="kpi-values"></div>' +
			'<div class="form-group add-value-button"><button type="button" class="btn btn-default border-round" onclick="addKpiValue(@kpi-index)">Tambah Pilihan Jawaban</button></div>' +
			'</div>';

		let kpiValueTemplate = '<div id="@value-key" class="kpi-value-group">' +
			'<div class="form-group row">' +
			'<div class="col-xs-11"><select class="form-control sumo-select border-round right-min-15" data-name="@value-description-name" data-kpi="@kpi-index"></div>' +
			'<div class="col-xs-1"><button class="btn btn-xs btn-danger btn-delete-survey" onclick="removeKpiElement(\'@value-key\')">Hapus</button></div>' +
			'</div>' +
			'</div>';

		$(function () {
			if (edit) {
				processCurrentData();
			} else {
				addStep();
			}
		});

		function addKpi(kpiTypeValue = '', kpiTextValue = '', kpiShortTextValue = '', kpiGoalValue = '', kpiReportUnitValue = 1) {
			$('#kpi-data').append(parseKpiTemplate(kpiTextValue, kpiShortTextValue, kpiGoalValue, kpiReportUnitValue));
			fillKpiTypes(kpiTypeValue);

			bindListener();
		}

		function addKpiValue(kpiIndex) {
			$(elementId.kpiValues + kpiIndex).append(parseKpiValueTemplate(kpiIndex));

			bindListener();
		}

		function fillKpiFields() {
			// append based on survey
		}

		function fillKpiRequires() {
			$(elementId.kpiRequiredId + kpiCount).append($('<option>', {
				value: '',
				text: 'Tidak ada'
			}));
			// append based on survey
		}

		function fillKpiTypes(kpiTypeValue) {
			$.each(kpiTypes, function(i, item) {
				if (item.key == kpiTypeValue) {
					$(elementId.kpiTypeId + kpiCount).append($('<option>', {
						value: item.key,
						text: item.description,
						selected: true
					}));
				} else {
					$(elementId.kpiTypeId + kpiCount).append($('<option>', {
						value: item.key,
						text: item.description
					}));
				}
			});
			$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});
		}

		function processChange() {

		}

		function processKpiType() {

		}

		function bindListener() {
			$('*[data-name="kpi-type"]').unbind('change', processKpiType).bind('change', processKpiType);
			$('*[data-name*="kpi-"]').unbind('change', processChange).bind('change', processChange);

			processChange();
		}

		function parseKpiTemplate(kpiTextValue = '', kpiShortTextValue = '', kpiGoalValue = '', kpiReportUnitValue = 1) {
			kpiCount++;
			return kpiTemplate.replaceAll('@kpi-text-value', kpiTextValue)
				.replaceAll('@kpi-short-text-value', kpiShortTextValue)
				.replaceAll('@kpi-goal-value', kpiGoalValue)
				.replaceAll('@kpi-report-unit-value', kpiReportUnitValue)
				.replaceAll('@kpi-text-placeholder', elementId.kpiTextPlaceholder)
				.replaceAll('@kpi-short-text-placeholder', elementId.kpiShortTextPlaceholder)
				.replaceAll('@kpi-goal-placeholder', elementId.kpiGoalPlaceholder)
				.replaceAll('@kpi-report-unit-placeholder', elementId.kpiReportUnitPlaceholder)
				.replaceAll('@kpi-text-name', elementId.kpiTextName)
				.replaceAll('@kpi-short-text-name', elementId.kpiShortTextName)
				.replaceAll('@kpi-goal-name', elementId.kpiGoalName)
				.replaceAll('@kpi-report-unit-name', elementId.kpiReportUnitName)
				.replaceAll('@kpi-type-name', elementId.kpiTypeName)
				.replaceAll('@kpi-required-name', elementId.kpiRequiredName)
				.replaceAll('@kpi-field-name', elementId.kpiFieldName)
				.replaceAll('@kpi-type-id', 'kpi-type-' + kpiCount)
				.replaceAll('@kpi-required-id', 'kpi-required-' + kpiCount)
				.replaceAll('@kpi-field-id', 'kpi-field-' + kpiCount)
				.replaceAll('@kpi-value-id', 'kpi-values-' + kpiCount)
				.replaceAll('@kpi-description', 'KPI ke-' + kpiCount)
				.replaceAll('@kpi-key', 'kpi-' + kpiCount)
				.replaceAll('@kpi-index', kpiCount);
		}

		function parseKpiValueTemplate(kpiIndex) {
			kpiValueCount++;
			return kpiValueTemplate.replaceAll('@value-description-name', elementId.kpiValueDescriptionName)
				.replaceAll('@kpi-index', kpiIndex)
				.replaceAll('@value-key', 'kpi-value-' + kpiValueCount);
		}

		String.prototype.replaceAll = function (search, replacement) {
			return this.split(search).join(replacement);
		};
	</script>
@append