<hr/>
<h4>Survey</h4>
<div id="survey-data">
	<input type="hidden" name="survey" value="">
</div>
<div class="form-group">
	<button type="button" class="btn btn-info border-round" onclick="addStep()">Tambah Langkah</button>
</div>
<input type="hidden" name="survey" value=""/>

@section('scripts')
	<script>
		let stepCount = 0;
		let questionCount = 0;
		let answerCount = 0;

		let elementId = {
			'stepKey': '#step-',
			'stepQuestions': '#step-questions-',
			'stepKeyName' : 'step-key',
			'stepDescriptionName': 'step-description',

			'questionKey': '#question-',
			'questionTypeId': '#question-type-',
			'questionAnswers': '#question-answers-',
			'questionTypeName': 'question-type',
			'questionKeyName': 'question-key',
			'questionDescriptionName': 'question-description',

			'newNumberDescriptionName': 'new-number-description',
			'oldNumberDescriptionName': 'old-number-description',
			'packageDescriptionName': 'package-description',
			'voucherDescriptionName': 'voucher-description',
			
			'newNumberPlaceholder': 'Deskripsi beli nomor baru',
			'oldNumberPlaceholder': 'Deskripsi nomor lama',
			'packagePlaceholder': 'Deskripsi beli paket',
			'voucherPlaceholder': 'Deskripsi beli voucher',

			'packageListId': '#package-list-',
			'voucherListId': '#voucher-list-',

			'answerKey': '#answer-',
			'answerKeyName': 'answer-key',
			'answerDescriptionName': 'answer-description',
		};
		let dataSelector = {
			'stepKey': '*[data-name*=' + elementId.stepKeyName + ']',
			'stepDescription': '*[data-name*=' + elementId.stepDescriptionName + ']',

			'questionType': '*[data-name*=' + elementId.questionTypeName + ']',
			'questionKey': '*[data-name*=' + elementId.questionKeyName + ']',
			'questionDescription': '*[data-name*=' + elementId.questionDescriptionName + ']',

			'answerKey': '*[data-name*=' + elementId.answerKeyName + ']',
			'answerDescription': '*[data-name*=' + elementId.answerDescriptionName + ']',

			'newNumberDescription': '*[data-name*=' + elementId.newNumberDescriptionName + ']',
			'oldNumberDescription': '*[data-name*=' + elementId.oldNumberDescriptionName + ']',
			'packageDescription': '*[data-name*=' + elementId.packageDescriptionName + ']',
			'voucherDescription': '*[data-name*=' + elementId.voucherDescriptionName + ']',
		};
		let questionTypes = [
			{ 'key': 'text', 'description': 'Text' },
			{ 'key': 'email', 'description': 'Email' },
			{ 'key': 'phone', 'description': 'Phone Number' },
			{ 'key': 'date', 'description': 'Date' },
			{ 'key': 'dropdown', 'description': 'Dropdown List' },
			{ 'key': 'checkbox', 'description': 'Checkbox' },
			{ 'key': 'checkboxes', 'description': 'Multiple Checkbox' },
			{ 'key': 'radio', 'description': 'Option' },
			{ 'key': 'image', 'description': 'Image Upload' },
			{ 'key': 'number_sales', 'description': 'Sales' },
		];
		let questionTypeWithAnswers = [
			'dropdown',
			'checkboxes',
			'radio'
		];

		let stepTemplate = '<div id="@step-key" class="step-group">' +
			'<h5>@step-description</h5>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@step-key-name" placeholder="@step-key-placeholder" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@step-description-name" placeholder="@step-description-placeholder" required></div>' +
			'<div id="@step-question-id" class="step-questions"></div>' +
			'<div class="form-group"><button type="button" class="btn btn-primary border-round" onclick="addQuestion(@step-index)">Tambah Pertanyaan</button></div>' +
			'</div>';

		let questionTemplate = '<div id="@question-key" class="question-group">' +
			'<h5>@question-description</h5>' +
			'<div class="form-group"><label>Tipe pertanyaan</label><select id="@question-type-id" class="form-control sumo-select border-round" data-name="@question-type-name" data-step="@step-index" data-index="@question-index"></select></div>' +
			'<div class="non-sales-group">' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@question-key-name" data-step="@step-index" placeholder="@question-key-placeholder" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@question-description-name" data-step="@step-index" placeholder="@question-description-placeholder" required></div>' +
			'<div id="@question-answer-id" class="question-answers"></div>' +
			'<div class="form-group add-answer-button"><button type="button" class="btn btn-default border-round" onclick="addAnswer(@question-index)">Tambah Pilihan Jawaban</button></div>' +
			'</div>' +
			'<div class="sales-group">' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@new-number-description-name" data-step="@step-index" placeholder="@new-number-description-placeholder" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@old-number-description-name" data-step="@step-index" placeholder="@old-number-description-placeholder" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@package-description-name" data-step="@step-index" placeholder="@package-description-placeholder" required></div>' +
			'<div id="@package-list-id" class="package-lists"></div>' +
			'<div class="form-group"><button type="button" class="btn btn-default border-round" onclick="addPackage(@question-index)">Tambah Paket</button></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@voucher-description-name" data-step="@step-index" placeholder="@voucher-description-placeholder" required></div>' +
			'<div id="@voucher-list-id" class="voucher-lists"></div>' +
			'<div class="form-group"><button type="button" class="btn btn-default border-round" onclick="addVoucher(@question-index)">Tambah Voucher</button></div>' +
			'</div>' +
			'</div>';

		let answerTemplate = '<div id="@answer-key" class="answer-group">' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@answer-key-name" data-question="@question-index" placeholder="@answer-key-placeholder" required></div>' +
			'<div class="form-group"><input type="text" class="form-control border-round" data-name="@answer-description-name" data-question="@question-index" placeholder="@answer-description-placeholder" required></div>' +
			'</div>';

		$(function () {
			addStep();
		});

		function bindListener() {
			$('*[data-name*="question-type"]').unbind('change', processQuestionType).bind('change', processQuestionType);
			$('*[data-name*="step-"]').unbind('change', processChange).bind('change', processChange);
			$('*[data-name*="question-"]').unbind('change', processChange).bind('change', processChange);
			$('*[data-name*="answer-"]').unbind('change', processChange).bind('change', processChange);
			$('*[data-name*="new-number-"]').unbind('change', processChange).bind('change', processChange);
			$('*[data-name*="old-number-"]').unbind('change', processChange).bind('change', processChange);
			$('*[data-name*="package-"]').unbind('change', processChange).bind('change', processChange);
			$('*[data-name*="voucher-"]').unbind('change', processChange).bind('change', processChange);
		}

		function processQuestionType() {
			let questionIndex = $(this).data('index');
			let questionType = $(this).val();

			$(elementId.questionAnswers + questionIndex + ', ' + elementId.packageListId + questionIndex + ', ' + elementId.voucherListId + questionIndex).html('');
			if (questionType == 'number_sales') {
				$(elementId.questionKey + questionIndex + ' .sales-group').show();
				$(elementId.questionKey + questionIndex + ' .non-sales-group').hide();
				addPackage(questionIndex);
				addVoucher(questionIndex);
			} else {
				$(elementId.questionKey + questionIndex + ' .sales-group').hide();
				$(elementId.questionKey + questionIndex + ' .non-sales-group').show();
				if (questionTypeWithAnswers.indexOf(questionType) != -1) {
					$('#question-' + questionIndex + ' .add-answer-button').show();
					addAnswer(questionIndex);
				}
			}
			$(elementId.questionTypeId + questionIndex + '.sumo-select')[0].sumo.reload();
		}

		function processChange() {
			// change all " " to "_" in key
			$('*[data-name*="-key"]').each(function() {
				$(this).val($(this).val().replaceAll(' ', '_'));
			});

			let data = [];
			let stepKeys = $(dataSelector.stepKey);
			let stepDescriptions = $(dataSelector.stepDescription);

			for (let i = 0; i < stepKeys.length; i++) {
				let stepKey = stepKeys[i].value;
				let stepDescription = stepDescriptions[i].value;

				let stepIndex = i + 1;
				let questionKeys = $(dataSelector.questionKey + '*[data-step=' + stepIndex + ']');
				let questionDescriptions = $(dataSelector.questionDescription + '*[data-step=' + stepIndex + ']');
				let questionTypes = $(dataSelector.questionType + '*[data-step=' + stepIndex + ']');

				let questions = [];
				for (let j = 0; j < questionKeys.length; j++) {
					let questionKey = questionKeys[j].value;
					let questionDescription = questionDescriptions[j].value;
					let questionType = questionTypes[j].value;
					let questionIndex = j + 1;
					let question = {};

					if (questionTypeWithAnswers.indexOf(questionType) != -1) {
						question = {
							'key': questionKey,
							'text': questionDescription,
							'type': questionType,
							'values': getAnswers(questionIndex)
						};
					} else if (questionType == 'number_sales') {
						let newNumberDescription = $(dataSelector.newNumberDescription + '*[data-step=' + stepIndex + ']')[0].value;
						let oldNumberDescription = $(dataSelector.oldNumberDescription + '*[data-step=' + stepIndex + ']')[0].value;
						let packageDescription = $(dataSelector.packageDescription + '*[data-step=' + stepIndex + ']')[0].value;
						let voucherDescription = $(dataSelector.voucherDescription + '*[data-step=' + stepIndex + ']')[0].value;

						question = {
							'key': questionKey,
							'type': questionType,
							'number': {
								'new': {
									'key': 'new_number',
									'text': newNumberDescription
								},
								'old': {
									'key': 'old_number',
									'text': oldNumberDescription
								}
							},
							'package': {
								'key': 'package',
								'text': packageDescription,
								'values': getAnswers(questionIndex, elementId.packageListId + questionIndex + ' ')
							},
							'voucher': {
								'key': 'voucher',
								'text': voucherDescription,
								'values': getAnswers(questionIndex, elementId.voucherListId + questionIndex + ' ')
							}
						};
					} else {
						question = {
							'key': questionKey,
							'text': questionDescription,
							'type': questionType
						};
					}

					questions.push(question);
				}

				data.push({
					key: stepKey,
					description: stepDescription,
					questions: questions
				});
			}

			$('input[name="survey"]').val(JSON.stringify(data));
		}

		function addStep() {
			$('#survey-data').append(parseStepTemplate());
			bindListener();
		}

		function addQuestion(stepIndex) {
			$(elementId.stepQuestions + stepIndex).append(parseQuestionTemplate(stepIndex));
			fillQuestionTypes();
			$('#question-' + questionCount + ' .add-answer-button, #question-' + questionCount + ' .sales-group').hide();
			bindListener();
		}

		function addAnswer(questionIndex) {
			$(elementId.questionAnswers + questionIndex).append(parseAnswerTemplate(questionIndex));
			bindListener();
		}

		function addPackage(questionIndex) {
			$(elementId.packageListId + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode paket', 'Deskripsi paket'));
			bindListener();
		}

		function addVoucher(questionIndex) {
			$(elementId.voucherListId + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode voucher', 'Deskripsi voucher'));
			bindListener();
		}

		function getAnswers(questionIndex, prefixSelector = '') {
			console.log(prefixSelector + dataSelector.answerKey + '*[data-question=' + questionIndex + ']');
			let answerKeys = $(prefixSelector + dataSelector.answerKey + '*[data-question=' + questionIndex + ']');
			let answerDescriptions = $(prefixSelector + dataSelector.answerDescription + '*[data-question=' + questionIndex + ']');

			let values = [];
			for (let k = 0; k < answerKeys.length; k++) {
				values.push({
					'key': answerKeys[k].value,
					'text': answerDescriptions[k].value
				});
				console.log(values);
			}

			return values;
		}

		function fillQuestionTypes() {
			$.each(questionTypes, function(i, item) {
				$(elementId.questionTypeId + questionCount).append($('<option>', {
					value: item.key,
					text: item.description
				}));
			});
			$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});
		}

		function parseAnswerTemplate(questionIndex, keyPlaceholder = 'Kode jawaban', descriptionPlaceholder = 'Deskripsi jawaban') {
			answerCount++;
			return answerTemplate.replaceAll('@answer-key-placeholder', keyPlaceholder)
				.replaceAll('@answer-description-placeholder', descriptionPlaceholder)
				.replaceAll('@answer-key-name', elementId.answerKeyName)
				.replaceAll('@answer-description-name', elementId.answerDescriptionName)
				.replaceAll('@answer-key', 'answer-' + answerCount)
				.replaceAll('@answer-index', answerCount)
				.replaceAll('@question-index', questionIndex);
		}

		function parseQuestionTemplate(stepIndex) {
			questionCount++;
			return questionTemplate.replaceAll('@question-key-placeholder', 'Kode pertanyaan')
				.replaceAll('@question-description-placeholder', 'Deskripsi pertanyaan')
				.replaceAll('@new-number-description-placeholder', elementId.newNumberPlaceholder)
				.replaceAll('@old-number-description-placeholder', elementId.oldNumberPlaceholder)
				.replaceAll('@package-description-placeholder', elementId.packagePlaceholder)
				.replaceAll('@voucher-description-placeholder', elementId.voucherPlaceholder)
				.replaceAll('@question-type-name', elementId.questionTypeName)
				.replaceAll('@question-key-name', elementId.questionKeyName)
				.replaceAll('@question-description-name', elementId.questionDescriptionName)
				.replaceAll('@new-number-description-name', elementId.newNumberDescriptionName)
				.replaceAll('@old-number-description-name', elementId.oldNumberDescriptionName)
				.replaceAll('@package-description-name', elementId.packageDescriptionName)
				.replaceAll('@voucher-description-name', elementId.voucherDescriptionName)
				.replaceAll('@question-type-id', 'question-type-' + questionCount)
				.replaceAll('@question-answer-id', 'question-answers-' + questionCount)
				.replaceAll('@package-list-id', 'package-list-' + questionCount)
				.replaceAll('@voucher-list-id', 'voucher-list-' + questionCount)
				.replaceAll('@question-description', 'Pertanyaan ke-' + questionCount)
				.replaceAll('@question-key', 'question-' + questionCount)
				.replaceAll('@question-index', questionCount)
				.replaceAll('@step-index', stepIndex);
		}

		function parseStepTemplate() {
			stepCount++;
			return stepTemplate.replaceAll('@step-key-placeholder', 'Kode bagian')
				.replaceAll('@step-description-placeholder', 'Deskripsi bagian')
				.replaceAll('@step-key-name', 'step-key')
				.replaceAll('@step-description-name', 'step-description')
				.replaceAll('@step-question-id', 'step-questions-' + stepCount)
				.replaceAll('@step-description', 'Bagian ke-' + stepCount)
				.replaceAll('@step-key', 'step-' + stepCount)
				.replaceAll('@step-index', stepCount);
		}

		String.prototype.replaceAll = function (search, replacement) {
			return this.split(search).join(replacement);
		}
	</script>
@append