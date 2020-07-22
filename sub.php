<?php

require_once('bot.php');

schedule();

$db = new MysqliDb('us-cdbr-east-02.cleardb.com', 'b869ac278f05ad', '1dfb91f0', 'heroku_f954956b083bef4');

$chat_ids = $db->getValue("subscriptions", "chat_id", null);


?>