<?php
require './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

use Telegram\Bot\Api;
$telegram = new Api(getenv('BOT_ID'));

require './inc/LCAPPAPI.php';
require './inc/ReplyOnAction.php';
require './inc/helpers_func.php';
require './commands/commands.php';

$lcApi = new \LCAPPAPI(getenv('API_URL'));

$update = $telegram->getWebhookUpdate();

$last_message = $lcApi->makeRequest('get-user-last-message', ['telegram_id' => $update->getMessage()->chat->id]);

$request = $lcApi->makeRequest('set-user-last-message', ['telegram_id' => $update->getMessage()->chat->id, 'message' => json_encode($update->getMessage())]);

$cur_text = trim($update->getMessage()->text);
if(substr($cur_text, 0, 1) == "/") {
	if(find_count_of_aplha_in_string($cur_text, '/') > 1) {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Warning: This command is not supported', !empty($request['user']['language']) ? $request['user']['language'] : 'en')]);
		$telegram->triggerCommand('help', $update);
		exit;
	}
	
	$telegram->commandsHandler(true);
	exit;
}

if ($update->isType('callback_query')) {
	$callbackName = $update->callbackQuery->data;
	
	if (strpos($callbackName, 'choose_language') !== false) {
		reply_on_action_switcher('choose_language', $update, $telegram, $callbackName);
	} else if (strpos($callbackName, 'remove_interest_from_list_by_number') !== false) {
        reply_on_action_switcher('remove_interest_from_list_by_number', $update, $telegram, $callbackName);
    } else {
		$telegram->triggerCommand($callbackName, $update);
	}
} else {
	if($last_message['status'] !== 'error') {
		$last_message_object = json_decode($last_message['message']['last_message']);

		if(!empty($last_message_object->reply_markup)) {
			$callbackName = $last_message_object->reply_markup->inline_keyboard[0][0]->callback_data;
			reply_on_action_switcher($callbackName, $update, $telegram, $last_message_object);
		} else {
			$telegram->commandsHandler(true);
		}
	} else {
		$telegram->commandsHandler(true);
	}
}