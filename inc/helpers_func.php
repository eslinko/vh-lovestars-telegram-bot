<?php

use Telegram\Bot\Keyboard\Keyboard;

function set_command_to_last_message($command_name, $telegram_id, $additional_data = []) {
	$lcApi = new \LCAPPAPI();
	$lcApi->makeRequest('set-user-last-message', ['telegram_id' => $telegram_id, 'message' => json_encode(array_merge(['reply_markup' => ['inline_keyboard' => [[['callback_data' => $command_name]]]]], $additional_data))]);
}

function user_is_verified($telegram_id) {
	$lcApi = new \LCAPPAPI();
	$return_data = $lcApi->makeRequest('get-user-by-telegram-id', ['telegram_id' => $telegram_id]);
	$return = [];
	$return['reply_markup'] = '';
	switch ($return_data['status']){
		case 'error':
			$return['status'] = false;
			$return['message'] = 'Sorry, there was an error, please contact the administrator.';
			break;
//		case 'user_not_found':
//			$return['message'] = 'Hello! To interact with the bot you must first complete a simple registration!';
//			$return['status'] = false;
//			$return['reply_markup'] = Keyboard::make([
//				'inline_keyboard' =>  [
//					[
//						Keyboard::inlineButton([
//							'text' => __('Start registration', !empty($return_data['user']['language']) ? $return_data['user']['language'] : 'en'),
//							'callback_data' => 'registration_step_2'
//						])
//					]
//				],
//				'resize_keyboard' => true,
//			]);
//			break;
		default:
			$return = choose_step($return_data['user'], $telegram_id);
			break;
	}

    $return['user'] = $return_data['user'];
    $return['expressions_in_proccess'] = $return_data['expressions_in_proccess'];

    if(!empty($return_data['expressions_in_proccess'])) {
        $return['expressions_create_command'] = get_command_for_expressions_create($return_data['expressions_in_proccess']);
    }

	return $return;
}

function get_command_for_expressions_create($expression)
{
    if(empty($expression['type'])) {
        return 'expression_choose_type';
    } else if(empty($expression['description'])) {
        return 'expression_choose_description';
    } else if(empty($expression['tags'])) {
        return 'expression_choose_tags';
    } else if (empty($expression['content'])) {
        return 'expression_choose_file';
    } else {
        return 'expression_confirm_creation';
    }
}

function choose_step($user, $telegram_id = '') {
	$res = [];
	
	if(empty($user['language'])) {
		$res['text'] = __('Hello. Select a communication language:', 'en');
		
		$languages = get_active_languages($telegram_id);
		
		$res['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  $languages['languages'],
			'resize_keyboard' => true,
		]);
        //TO ASK FOR PASSWORD UNCOMMENT "empty($user['password_hash'])" and BELOW and in function registration_step_invitation_code
	} else if(empty($user['full_name']) || empty($user['publicAlias']) /*|| empty($user['password_hash'])*/ || empty($user['invitation_code_id'])) {
		
		if(empty($user['full_name']) || empty($user['publicAlias'])) {
			$res['message'] = __('Hello! To interact with the bot you must first complete a simple registration!', $user['language']);
            $res['text'] = __('Hello! To interact with the bot you must first complete a simple registration!', $user['language']);
            $step = 'registration_step_2';
			$res['status'] = false;
		} else if (empty($user['invitation_code_id'])) {
			$res['text'] = __('Hello. You need to finish registering with the bot.', $user['language']);
			$step = 'registration_step_invitation_code';
			$res['status'] = false;
            //TO ASK FOR PASSWORD UNCOMMENT BELOW and in function registration_step_invitation_code
		}/* else if (empty($user['password_hash'])) {
			$res['message'] = __('Hello. You need to finish registering with the bot.', $user['language']);
			$step = 'registration_step_3';
			$res['status'] = false;
		}*/
		
		$res['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Continue registration', $user['language']),
						'callback_data' => $step
					])
				]
			],
			'resize_keyboard' => true,
		]);
	} else {
		$res['status'] = true;
	}
	$res['user'] = $user;
	return $res;
}

function __($text, $lang = 'en') {
//    require realpath($_SERVER['DOCUMENT_ROOT']) . '/languages/translates.php';
    if(!is_string($text)) return '';
	require dirname(__DIR__) . '/languages/translates.php';
	return !empty($translates[$text]) && !empty($translates[$text][$lang]) ? $translates[$text][$lang] : $text;
}

function get_active_languages($telegram_id) {
	$lcApi = new \LCAPPAPI();
	$return_data = $lcApi->makeRequest('get-active-languages', ['telegram_id' => $telegram_id]);
	
	$keyboards = [];
	if($return_data['status'] === 'success') {
		foreach ($return_data['languages'] as $language) {
			$keyboards[] = [
				Keyboard::inlineButton([
					'text' => $language['title'],
					'callback_data' => 'choose_language__' . $language['code']
				])
			];
		}
	}
	
	return $return_data['status'] === 'error' ? false : ['languages' => $return_data['languages'], 'keyboards' => $keyboards];
}

function find_count_of_aplha_in_string($string, $search){
	$pos = -1;
	$result = [];
	
	while(($pos = strpos($string, $search, $pos+1))!==false) {
		$result[] = $pos;
	}
	
	return count($result);
}

//function expressions_step($cur_command, $telegram, $update)
//{
//    $telegram_id = $update->getMessage()->chat->id;
//
//    $result = user_is_verified($telegram_id);
//
//    if(!$result['status']) {
//        return false;
//    }
//}