UPDATE `event_answer` SET `answer` = REPLACE(answer, '"provider":"3"', '"provider":"tri"');
UPDATE `event_answer` SET `answer` = REPLACE(answer, '"number"', '"new_number"') WHERE `answer` not like '%new_number%';
UPDATE `event_answer` SET `answer` = REPLACE(answer, '"package"', '"old_number":"","package"') WHERE `answer` not like '%old_number%';