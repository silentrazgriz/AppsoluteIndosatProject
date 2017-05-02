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
	'questionRequiredName': 'question-required',
	'questionTerminateName': 'question-terminate',

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
	'questionRequired': '*[data-name*=' + elementId.questionRequiredName + ']',
	'questionTerminate': '*[data-name*=' + elementId.questionTerminateName + ']',

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
	'<h5>@step-description <button class="btn btn-xs btn-danger btn-delete-survey" onclick="removeSurveyElement(\'@step-key\')">Hapus</button></h5>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@step-key-name" placeholder="@step-key-placeholder" value="@step-key-value" required></div>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@step-description-name" placeholder="@step-description-placeholder" value="@step-description-value" required></div>' +
	'<div id="@step-question-id" class="step-questions"></div>' +
	'<div class="form-group"><button type="button" class="btn btn-primary border-round" onclick="addQuestion(@step-index)">Tambah Pertanyaan</button></div>' +
	'</div>';

let questionTemplate = '<div id="@question-key" class="question-group">' +
	'<h5>@question-description <button class="btn btn-xs btn-danger btn-delete-survey" onclick="removeSurveyElement(\'@question-key\')">Hapus</button></h5>' +
	'<div class="form-group"><label>Tipe pertanyaan</label><select id="@question-type-id" class="form-control sumo-select border-round right-min-15" data-name="@question-type-name" data-step="@step-index" data-index="@question-index"></select></div>' +
	'<div class="non-sales-group">' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@question-key-name" data-step="@step-index" data-question="@question-index" placeholder="@question-key-placeholder" value="@question-key-value"></div>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@question-description-name" data-step="@step-index" placeholder="@question-description-placeholder" value="@question-description-value"></div>' +
	'<div class="form-group">' +
	'<label class="checkbox"><input type="checkbox" data-name="@question-required-name" data-step="@step-index" value="1" @question-required-checked><span class="indicator"><span></span></span> Required</label>' +
	'<label class="checkbox"><input type="checkbox" data-name="@question-terminate-name" data-step="@step-index" value="1" @question-terminate-checked><span class="indicator"><span></span></span> Terminate if empty</label>' +
	'</div>' +
	'<div id="@question-answer-id" class="question-answers"></div>' +
	'<div class="form-group add-answer-button"><button type="button" class="btn btn-default border-round" onclick="addAnswer(@question-index)">Tambah Pilihan Jawaban</button></div>' +
	'</div>' +
	'<div class="sales-group">' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@new-number-description-name" data-step="@step-index" placeholder="@new-number-description-placeholder" value="@new-number-description-value"></div>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@old-number-description-name" data-step="@step-index" placeholder="@old-number-description-placeholder" value="@old-number-description-value"></div>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@package-description-name" data-step="@step-index" placeholder="@package-description-placeholder" value="@package-description-value"></div>' +
	'<div id="@package-list-id" class="package-lists"></div>' +
	'<div class="form-group"><button type="button" class="btn btn-default border-round" onclick="addPackage(@question-index)">Tambah Paket</button></div>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@voucher-description-name" data-step="@step-index" placeholder="@voucher-description-placeholder" value="@voucher-description-value"></div>' +
	'<div id="@voucher-list-id" class="voucher-lists"></div>' +
	'<div class="form-group"><button type="button" class="btn btn-default border-round" onclick="addVoucher(@question-index)">Tambah Voucher</button></div>' +
	'</div>' +
	'</div>';

let answerTemplate = '<div id="@answer-key" class="answer-group">' +
	'<div class="form-group row">' +
	'<div class="col-xs-11"><input type="text" class="form-control border-round" data-name="@answer-key-name" data-question="@question-index" placeholder="@answer-key-placeholder" value="@answer-key-value"></div>' +
	'<div class="col-xs-1"><button class="btn btn-xs btn-danger btn-delete-survey" onclick="removeSurveyElement(\'@answer-key\')">Hapus</button></div>' +
	'</div>' +
	'<div class="form-group"><input type="text" class="form-control border-round" data-name="@answer-description-name" data-question="@question-index" placeholder="@answer-description-placeholder" value="@answer-description-value"></div>' +
	'</div>';

function removeSurveyElement(selector) {
	$('#' + selector).remove();
	processChange();
}

function processCurrentData() {
	let fieldValue = $('input[name="survey"]').val();
	let surveys = (fieldValue == '') ? [] : JSON.parse(fieldValue);

	$.each(surveys, function(i, step) {
		addStep(step['key'], step['description']);
		$.each(step['questions'], function(j, question) {
			if (question['type'] == 'number_sales') {
				addQuestion(stepCount, question['type'], '', '', false, false, question);
				$.each(question['package']['values'], function(k, value) {
					addPackage(questionCount, value['key'], value['text']);
				});
				$.each(question['voucher']['values'], function(k, value) {
					addVoucher(questionCount, value['key'], value['text']);
				});
			} else {
				let isRequired = (question['class'] != undefined && question['class'].indexOf('required') != -1);
				let isTerminate = (question['class'] != undefined && question['class'].indexOf('terminate-empty') != -1);

				addQuestion(stepCount, question['type'], question['key'], question['text'], isRequired, isTerminate, undefined);
				if (questionTypeWithAnswers.indexOf(question['type']) != -1) {
					$.each(question['values'], function(k, value) {
						addAnswer(questionCount, value['key'], value['text']);
					});
				}
			}
		});
	});
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
		$(this).val($(this).val().replaceAll(' ', '_').toLowerCase());
	});

	let data = [];
	let stepKeys = $(dataSelector.stepKey);
	let stepDescriptions = $(dataSelector.stepDescription);

	for (let i = 0; i < stepKeys.length; i++) {
		let stepKey = stepKeys[i].value;
		let stepDescription = stepDescriptions[i].value;

		let stepIndex = i + 1;
		let stepSelector = '*[data-step=' + stepIndex + ']';
		let questionKeys = $(dataSelector.questionKey + stepSelector);
		let questionDescriptions = $(dataSelector.questionDescription + stepSelector);
		let questionTypes = $(dataSelector.questionType + stepSelector);
		let questionRequires = $(dataSelector.questionRequired + stepSelector);
		let questionTerminates = $(dataSelector.questionTerminate + stepSelector);

		let questions = [];
		for (let j = 0; j < questionKeys.length; j++) {
			let questionKey = questionKeys[j].value;
			let questionDescription = questionDescriptions[j].value;
			let questionType = questionTypes[j].value;
			let questionIndex = questionKeys[j].dataset.question;
			let question = {};

			if (questionTypeWithAnswers.indexOf(questionType) != -1) {
				question = {
					'key': questionKey,
					'text': questionDescription,
					'type': questionType,
					'values': getAnswers(questionIndex)
				};
			} else if (questionType == 'number_sales') {
				let newNumberDescription = $(dataSelector.newNumberDescription + stepSelector)[0].value;
				let oldNumberDescription = $(dataSelector.oldNumberDescription + stepSelector)[0].value;
				let packageDescription = $(dataSelector.packageDescription + stepSelector)[0].value;
				let voucherDescription = $(dataSelector.voucherDescription + stepSelector)[0].value;

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

			let questionRequire = questionRequires[j].checked;
			let questionTerminate = questionTerminates[j].checked;
			let questionClass = 'border-round';

			questionClass += (questionRequire) ? ' required' : '';
			questionClass += (questionTerminate) ? ' terminate-empty' : '';

			question['class'] = questionClass;

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

function addStep(stepKeyValue = '', stepDescriptionValue = '') {
	$('#survey-data').append(parseStepTemplate(stepKeyValue, stepDescriptionValue));
	bindListener();
}

function addQuestion(stepIndex, questionTypeValue = '', questionKeyValue = '', questionDescriptionValue = '', questionRequiredValue = false, questionTerminateValue = false, salesValue = undefined) {
	$(elementId.stepQuestions + stepIndex).append(parseQuestionTemplate(stepIndex, questionKeyValue, questionDescriptionValue, questionRequiredValue, questionTerminateValue, salesValue));
	fillQuestionTypes(questionTypeValue);

	if (questionTypeWithAnswers.indexOf(questionTypeValue) != -1) {
		$('#question-' + questionCount + ' .sales-group').hide();
	} else if (questionTypeValue == 'number_sales') {
		$('#question-' + questionCount + ' .non-sales-group').hide();
	} else {
		$('#question-' + questionCount + ' .add-answer-button, #question-' + questionCount + ' .sales-group').hide();
	}

	bindListener();
}

function addAnswer(questionIndex, answerKeyValue = '', answerDescriptionValue = '') {
	$(elementId.questionAnswers + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode jawaban (berupa gabungan huruf dan angka)', 'Deskripsi jawaban', answerKeyValue, answerDescriptionValue));
	bindListener();
}

function addPackage(questionIndex, packageKeyValue = '', packageDescriptionValue = '') {
	$(elementId.packageListId + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode paket (diakhiri harga paket, contoh: paket_a_19000)', 'Deskripsi paket', packageKeyValue, packageDescriptionValue));
	bindListener();
}

function addVoucher(questionIndex, voucherKeyValue = '', voucherDescriptionValue = '') {
	$(elementId.voucherListId + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode voucher (harga voucher tanpa singkatan, contoh: 10000)', 'Deskripsi voucher', voucherKeyValue, voucherDescriptionValue));
	bindListener();
}

function getAnswers(questionIndex, prefixSelector = '') {
	let answerKeys = $(prefixSelector + dataSelector.answerKey + '*[data-question=' + questionIndex + ']');
	let answerDescriptions = $(prefixSelector + dataSelector.answerDescription + '*[data-question=' + questionIndex + ']');

	let values = [];
	for (let k = 0; k < answerKeys.length; k++) {
		values.push({
			'key': answerKeys[k].value,
			'text': answerDescriptions[k].value
		});
	}

	return values;
}

function bindListener() {
	$('*[data-name="question-type"]').unbind('change', processQuestionType).bind('change', processQuestionType);
	$('*[data-name*="step-"]').unbind('change', processChange).bind('change', processChange);
	$('*[data-name*="question-"]').unbind('change', processChange).bind('change', processChange);
	$('*[data-name*="answer-"]').unbind('change', processChange).bind('change', processChange);
	$('*[data-name*="new-number-"]').unbind('change', processChange).bind('change', processChange);
	$('*[data-name*="old-number-"]').unbind('change', processChange).bind('change', processChange);
	$('*[data-name*="package-"]').unbind('change', processChange).bind('change', processChange);
	$('*[data-name*="voucher-"]').unbind('change', processChange).bind('change', processChange);

	processChange();
}

function fillQuestionTypes(questionTypeValue = '') {
	$.each(questionTypes, function(i, item) {
		if (item.key == questionTypeValue) {
			$(elementId.questionTypeId + questionCount).append($('<option>', {
				value: item.key,
				text: item.description,
				selected: true
			}));
		} else {
			$(elementId.questionTypeId + questionCount).append($('<option>', {
				value: item.key,
				text: item.description
			}));
		}
	});
	$('.sumo-select').SumoSelect({placeholder: 'Pilih disini'});
}

function parseAnswerTemplate(questionIndex, keyPlaceholder = 'Kode jawaban (berupa gabungan huruf dan angka)', descriptionPlaceholder = 'Deskripsi jawaban', answerKeyValue = '', answerDescriptionValue = '') {
	answerCount++;
	return answerTemplate.replaceAll('@answer-key-value', answerKeyValue)
		.replaceAll('@answer-description-value', answerDescriptionValue)
		.replaceAll('@answer-key-placeholder', keyPlaceholder)
		.replaceAll('@answer-description-placeholder', descriptionPlaceholder)
		.replaceAll('@answer-key-name', elementId.answerKeyName)
		.replaceAll('@answer-description-name', elementId.answerDescriptionName)
		.replaceAll('@answer-key', 'answer-' + answerCount)
		.replaceAll('@answer-index', answerCount)
		.replaceAll('@question-index', questionIndex);
}

function parseQuestionTemplate(stepIndex, questionKeyValue = '', questionDescriptionValue = '', questionRequiredValue = false, questionTerminateValue = false, salesValue = undefined) {
	questionCount++;
	return questionTemplate.replaceAll('@question-required-checked', questionRequiredValue ? 'checked' : '')
		.replaceAll('@question-terminate-checked', questionTerminateValue ? 'checked' : '')
		.replaceAll('@question-key-value', questionKeyValue)
		.replaceAll('@question-description-value', questionDescriptionValue)
		.replaceAll('@new-number-description-value', (salesValue != undefined) ? salesValue['number']['new']['text'] : '')
		.replaceAll('@old-number-description-value', (salesValue != undefined) ? salesValue['number']['old']['text'] : '')
		.replaceAll('@package-description-value', (salesValue != undefined) ? salesValue['package']['text'] : '')
		.replaceAll('@voucher-description-value', (salesValue != undefined) ? salesValue['voucher']['text'] : '')
		.replaceAll('@question-key-placeholder', 'Kode pertanyaan (berupa gabungan huruf dan angka)')
		.replaceAll('@question-description-placeholder', 'Deskripsi pertanyaan')
		.replaceAll('@new-number-description-placeholder', elementId.newNumberPlaceholder)
		.replaceAll('@old-number-description-placeholder', elementId.oldNumberPlaceholder)
		.replaceAll('@package-description-placeholder', elementId.packagePlaceholder)
		.replaceAll('@voucher-description-placeholder', elementId.voucherPlaceholder)
		.replaceAll('@question-required-name', elementId.questionRequiredName)
		.replaceAll('@question-terminate-name', elementId.questionTerminateName)
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

function parseStepTemplate(stepKeyValue = '', stepDescriptionValue = '') {
	stepCount++;
	return stepTemplate.replaceAll('@step-key-value', stepKeyValue)
		.replaceAll('@step-description-value', stepDescriptionValue)
		.replaceAll('@step-key-placeholder', 'Kode bagian (berupa gabungan huruf dan angka)')
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
};