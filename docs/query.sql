UPDATE `event_answers` SET `answer` = REPLACE(answer, '"provider":"3"', '"provider":"tri"');
UPDATE `event_answers` SET `answer` = REPLACE(answer, '"number"', '"new_number"') WHERE `answer` not like '%new_number%';
UPDATE `event_answers` SET `answer` = REPLACE(answer, '"package"', '"old_number":"","package"') WHERE `answer` not like '%old_number%';

UPDATE `event_answers` SET `answer` = REPLACE(REPLACE(answer, '"moment":{"twitter"', '"twitter"'), '},"hadiah_voucher"', ',"hadiah_voucher"');

UPDATE `events` SET `kpi` = REPLACE(kpi, 'reportUnit', 'report_unit');