let stepCount = 0;
let questionCount = 0;
let answerCount = 0;

let surveyElementId = {
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

let surveyDataSelector = {
	'stepKey': '*[data-name*=' + surveyElementId.stepKeyName + ']',
	'stepDescription': '*[data-name*=' + surveyElementId.stepDescriptionName + ']',

	'questionType': '*[data-name*=' + surveyElementId.questionTypeName + ']',
	'questionKey': '*[data-name*=' + surveyElementId.questionKeyName + ']',
	'questionDescription': '*[data-name*=' + surveyElementId.questionDescriptionName + ']',
	'questionRequired': '*[data-name*=' + surveyElementId.questionRequiredName + ']',
	'questionTerminate': '*[data-name*=' + surveyElementId.questionTerminateName + ']',

	'answerKey': '*[data-name*=' + surveyElementId.answerKeyName + ']',
	'answerDescription': '*[data-name*=' + surveyElementId.answerDescriptionName + ']',

	'newNumberDescription': '*[data-name*=' + surveyElementId.newNumberDescriptionName + ']',
	'oldNumberDescription': '*[data-name*=' + surveyElementId.oldNumberDescriptionName + ']',
	'packageDescription': '*[data-name*=' + surveyElementId.packageDescriptionName + ']',
	'voucherDescription': '*[data-name*=' + surveyElementId.voucherDescriptionName + ']',
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

let stepTemplate = '<div id="@step-key" class="step-group"><h5>@step-description <button class="btn btn-xs btn-danger btn-delete-element" onclick="removeSurveyElement(\'@step-key\')">Hapus</button></h5><div class="form-group"><label class="col-xs-2 control-label">Kode Bagian</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@step-key-name" placeholder="@step-key-placeholder" value="@step-key-value" required></div></div><div class="form-group"><label class="col-xs-2 control-label">Deskripsi</label><div class="col-xs-10"><input type="text" class="form-control border-round" data-name="@step-description-name" placeholder="@step-description-placeholder" value="@step-description-value" required></div></div><div id="@step-question-id" class="step-questions"></div><div class="form-group"><button type="button" class="btn btn-primary border-round" onclick="addQuestion(@step-index)">Tambah Pertanyaan</button></div></div>';

let questionTemplate = '<div id="@question-key" class="question-group"><h5>@question-description <button class="btn btn-xs btn-danger btn-delete-element" onclick="removeSurveyElement(\'@question-key\')">Hapus</button></h5><div class="form-group"><label class="col-xs-3 control-label">Tipe pertanyaan</label><div class="col-xs-9"><select id="@question-type-id" class="form-control sumo-select border-round right-min-15" data-name="@question-type-name" data-step="@step-index" data-index="@question-index"></select></div></div><div class="non-sales-group"><div class="form-group"><label class="col-xs-3 control-label">Kode pertanyaan</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@question-key-name" data-step="@step-index" data-question="@question-index" placeholder="@question-key-placeholder" value="@question-key-value"></div></div><div class="form-group"><label class="col-xs-3 control-label">Deskripsi</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@question-description-name" data-step="@step-index" placeholder="@question-description-placeholder" value="@question-description-value"></div></div><div class="form-group"><div class="col-xs-9 col-xs-offset-3"><label class="checkbox"><input type="checkbox" data-name="@question-required-name" data-step="@step-index" value="1" @question-required-checked><span class="indicator"><span></span></span> Required</label><label class="checkbox"><input type="checkbox" data-name="@question-terminate-name" data-step="@step-index" value="1" @question-terminate-checked><span class="indicator"><span></span></span> Terminate if empty</label></div></div><div id="@question-answer-id" class="question-answers"></div><div class="form-group add-answer-button"><button type="button" class="btn btn-default border-round" onclick="addAnswer(@question-index)">Tambah Pilihan Jawaban</button></div></div><div class="sales-group"><div class="form-group"><label class="col-xs-3 control-label">Deskripsi Nomor Baru</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@new-number-description-name" data-step="@step-index" placeholder="@new-number-description-placeholder" value="@new-number-description-value"></div></div><div class="form-group"><label class="col-xs-3 control-label">Deskripsi Nomor Lama</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@old-number-description-name" data-step="@step-index" placeholder="@old-number-description-placeholder" value="@old-number-description-value"></div></div><div class="form-group"><label class="col-xs-3 control-label">Deskripsi Paket</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@package-description-name" data-step="@step-index" placeholder="@package-description-placeholder" value="@package-description-value"></div></div><div id="@package-list-id" class="package-lists"></div><div class="form-group"><button type="button" class="btn btn-default border-round" onclick="addPackage(@question-index)">Tambah Paket</button></div><div class="form-group"><label class="col-xs-3 control-label">Deskripsi Voucher</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@voucher-description-name" data-step="@step-index" placeholder="@voucher-description-placeholder" value="@voucher-description-value"></div></div><div id="@voucher-list-id" class="voucher-lists"></div><div class="form-group"><button type="button" class="btn btn-default border-round" onclick="addVoucher(@question-index)">Tambah Voucher</button></div></div></div>';

let answerTemplate = '<div id="@answer-key" class="answer-group"><div class="form-group row"><label class="col-xs-3 control-label">Kode Pertanyaan</label><div class="col-xs-8"><input type="text" class="form-control border-round" data-name="@answer-key-name" data-question="@question-index" placeholder="@answer-key-placeholder" value="@answer-key-value"></div><div class="col-xs-1"><button class="btn btn-xs btn-danger btn-delete-element" onclick="removeSurveyElement(\'@answer-key\')">Hapus</button></div></div><div class="form-group"><label class="col-xs-3 control-label">Deskripsi</label><div class="col-xs-9"><input type="text" class="form-control border-round" data-name="@answer-description-name" data-question="@question-index" placeholder="@answer-description-placeholder" value="@answer-description-value"></div></div></div>';

function removeSurveyElement(selector) {
	$('#' + selector).remove();
	processSurveyChange();
}

function processSurveyCurrentData() {
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

	$(surveyElementId.questionAnswers + questionIndex + ', ' + surveyElementId.packageListId + questionIndex + ', ' + surveyElementId.voucherListId + questionIndex).html('');
	if (questionType == 'number_sales') {
		$(surveyElementId.questionKey + questionIndex + ' .sales-group').show();
		$(surveyElementId.questionKey + questionIndex + ' .non-sales-group').hide();
		addPackage(questionIndex);
		addVoucher(questionIndex);
	} else {
		$(surveyElementId.questionKey + questionIndex + ' .sales-group').hide();
		$(surveyElementId.questionKey + questionIndex + ' .non-sales-group').show();
		if (questionTypeWithAnswers.indexOf(questionType) != -1) {
			$('#question-' + questionIndex + ' .add-answer-button').show();
			addAnswer(questionIndex);
		}
	}
	$(surveyElementId.questionTypeId + questionIndex + '.sumo-select')[0].sumo.reload();
}

function processSurveyChange() {
	// change all " " to "_" in key
	$('*[data-name*="-key"]').each(function() {
		$(this).val($(this).val().replaceAll(' ', '_').toLowerCase());
	});

	let data = [];
	let stepKeys = $(surveyDataSelector.stepKey);
	let stepDescriptions = $(surveyDataSelector.stepDescription);

	for (let i = 0; i < stepKeys.length; i++) {
		let stepKey = stepKeys[i].value;
		let stepDescription = stepDescriptions[i].value;

		let stepIndex = i + 1;
		let stepSelector = '*[data-step=' + stepIndex + ']';
		let questionKeys = $(surveyDataSelector.questionKey + stepSelector);
		let questionDescriptions = $(surveyDataSelector.questionDescription + stepSelector);
		let questionTypes = $(surveyDataSelector.questionType + stepSelector);
		let questionRequires = $(surveyDataSelector.questionRequired + stepSelector);
		let questionTerminates = $(surveyDataSelector.questionTerminate + stepSelector);

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
				let newNumberDescription = $(surveyDataSelector.newNumberDescription + stepSelector)[0].value;
				let oldNumberDescription = $(surveyDataSelector.oldNumberDescription + stepSelector)[0].value;
				let packageDescription = $(surveyDataSelector.packageDescription + stepSelector)[0].value;
				let voucherDescription = $(surveyDataSelector.voucherDescription + stepSelector)[0].value;

				question = {
					'key': 'sales',
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
						'values': getAnswers(questionIndex, surveyElementId.packageListId + questionIndex + ' ')
					},
					'voucher': {
						'key': 'voucher',
						'text': voucherDescription,
						'values': getAnswers(questionIndex, surveyElementId.voucherListId + questionIndex + ' ')
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
	bindSurveyListener();
}

function addQuestion(stepIndex, questionTypeValue = '', questionKeyValue = '', questionDescriptionValue = '', questionRequiredValue = false, questionTerminateValue = false, salesValue = undefined) {
	$(surveyElementId.stepQuestions + stepIndex).append(parseQuestionTemplate(stepIndex, questionKeyValue, questionDescriptionValue, questionRequiredValue, questionTerminateValue, salesValue));
	fillQuestionTypes(questionTypeValue);

	if (questionTypeWithAnswers.indexOf(questionTypeValue) != -1) {
		$('#question-' + questionCount + ' .sales-group').hide();
	} else if (questionTypeValue == 'number_sales') {
		$('#question-' + questionCount + ' .non-sales-group').hide();
	} else {
		$('#question-' + questionCount + ' .add-answer-button, #question-' + questionCount + ' .sales-group').hide();
	}

	bindSurveyListener();
}

function addAnswer(questionIndex, answerKeyValue = '', answerDescriptionValue = '') {
	$(surveyElementId.questionAnswers + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode jawaban (berupa gabungan huruf dan angka)', 'Deskripsi jawaban', answerKeyValue, answerDescriptionValue));
	bindSurveyListener();
}

function addPackage(questionIndex, packageKeyValue = '', packageDescriptionValue = '') {
	$(surveyElementId.packageListId + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode paket (diakhiri harga paket, cth: paket_a_19000)', 'Deskripsi paket', packageKeyValue, packageDescriptionValue));
	bindSurveyListener();
}

function addVoucher(questionIndex, voucherKeyValue = '', voucherDescriptionValue = '') {
	$(surveyElementId.voucherListId + questionIndex).append(parseAnswerTemplate(questionIndex, 'Kode voucher (harga voucher, cth: 10000)', 'Deskripsi voucher', voucherKeyValue, voucherDescriptionValue));
	bindSurveyListener();
}

function getAnswers(questionIndex, prefixSelector = '') {
	let answerKeys = $(prefixSelector + surveyDataSelector.answerKey + '*[data-question=' + questionIndex + ']');
	let answerDescriptions = $(prefixSelector + surveyDataSelector.answerDescription + '*[data-question=' + questionIndex + ']');

	let values = [];
	for (let k = 0; k < answerKeys.length; k++) {
		values.push({
			'key': answerKeys[k].value,
			'text': answerDescriptions[k].value
		});
	}

	return values;
}

function bindSurveyListener() {
	$('*[data-name="question-type"]').unbind('change', processQuestionType).bind('change', processQuestionType);
	$('*[data-name*="step-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);
	$('*[data-name*="question-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);
	$('*[data-name*="answer-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);
	$('*[data-name*="new-number-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);
	$('*[data-name*="old-number-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);
	$('*[data-name*="package-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);
	$('*[data-name*="voucher-"]').unbind('change', processSurveyChange).bind('change', processSurveyChange);

	processSurveyChange();
}

function fillQuestionTypes(questionTypeValue = '') {
	$.each(questionTypes, function(i, item) {
		if (item.key == questionTypeValue) {
			$(surveyElementId.questionTypeId + questionCount).append($('<option>', {
				value: item.key,
				text: item.description,
				selected: true
			}));
		} else {
			$(surveyElementId.questionTypeId + questionCount).append($('<option>', {
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
		.replaceAll('@answer-key-name', surveyElementId.answerKeyName)
		.replaceAll('@answer-description-name', surveyElementId.answerDescriptionName)
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
		.replaceAll('@new-number-description-placeholder', surveyElementId.newNumberPlaceholder)
		.replaceAll('@old-number-description-placeholder', surveyElementId.oldNumberPlaceholder)
		.replaceAll('@package-description-placeholder', surveyElementId.packagePlaceholder)
		.replaceAll('@voucher-description-placeholder', surveyElementId.voucherPlaceholder)
		.replaceAll('@question-required-name', surveyElementId.questionRequiredName)
		.replaceAll('@question-terminate-name', surveyElementId.questionTerminateName)
		.replaceAll('@question-type-name', surveyElementId.questionTypeName)
		.replaceAll('@question-key-name', surveyElementId.questionKeyName)
		.replaceAll('@question-description-name', surveyElementId.questionDescriptionName)
		.replaceAll('@new-number-description-name', surveyElementId.newNumberDescriptionName)
		.replaceAll('@old-number-description-name', surveyElementId.oldNumberDescriptionName)
		.replaceAll('@package-description-name', surveyElementId.packageDescriptionName)
		.replaceAll('@voucher-description-name', surveyElementId.voucherDescriptionName)
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