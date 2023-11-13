<?php
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;
function reply_on_action_switcher($callback_data, $update, $telegram, $last_message_object) {
	switch ($callback_data) {
		case 'registration_step_1':
			after_registration_step_1($update, $telegram);
			break;
		case 'registration_step_2':
			after_registration_step_2($update, $telegram);
			break;
		case 'registration_step_invitation_code':
			registration_step_invitation_code($update, $telegram);
			break;
		case 'registration_step_3':
			after_registration_step_3($update, $telegram);
			break;
		case 'registration_step_4':
			after_registration_step_4($update, $telegram);
			break;
		case 'teacher_create':
			teacher_create($update, $telegram);
			break;
        case 'teacher_create_step_2':
            teacher_create_step_2($update, $telegram);
            break;
        case 'teacher_create_step_3':
            teacher_create_step_3($update, $telegram);
            break;
        case 'teacher_create_step_4':
            teacher_create_step_4($update, $telegram);
            break;
		case 'teacher_update_title':
			teacher_update_title($update, $telegram);
			break;
		case 'teacher_update_public_alias':
			teacher_update_public_alias($update, $telegram);
			break;
		case 'teacher_update_description':
			teacher_update_description($update, $telegram);
			break;
		case 'teacher_update_hashtags':
			teacher_update_hashtags($update, $telegram);
			break;
		case 'set_active_teacher':
			teacher_set_active($update, $telegram);
			break;
		case 'assign_user_to_active_teacher':
			assign_user_to_active_teacher($update, $telegram);
			break;
		case 'update_my_email':
			update_my_email($update, $telegram);
			break;
		case 'update_my_email_confirm_code':
			update_my_email_confirm_code($update, $telegram);
			break;
		case 'update_my_public_alias':
			update_my_public_alias($update, $telegram);
			break;
		case 'update_my_password':
			update_my_password($update, $telegram);
			break;
        case 'events_create':
            events_create($update, $telegram);
            break;
		case 'suggest_new_language':
			suggest_new_language($update, $telegram);
            break;
		case 'choose_language':
			choose_language($update, $telegram, $last_message_object);
            break;
        case 'remove_interest_from_list_by_number':
            remove_interest_from_list_by_number($update, $telegram, $last_message_object);
            break;
		case 'my_interests_and_values':
			my_interests_and_values($update, $telegram, $last_message_object);
            break;
        case 'set_user_interests':
            set_user_interests($update, $telegram, $last_message_object);
            break;
        case 'add_new_connection':
            add_new_connection($update, $telegram);
            break;
        case 'delete_connection_by_id':
            delete_connection_by_id($update, $telegram, $last_message_object);
            break;
        case 'accept_connection':
            accept_connection($update, $telegram, $last_message_object);
            break;
        case 'decline_connection':
            decline_connection($update, $telegram, $last_message_object);
            break;
        case 'check_connection':
            check_connection($update, $telegram, $last_message_object);
            break;
        case 'ask_to_revert_connection':
            ask_to_revert_connection($update, $telegram, $last_message_object);
            break;
        case 'expression_choose_type':
            expression_choose_type($update, $telegram, $last_message_object);
            break;
        case 'expression_choose_description':
            expression_choose_description($update, $telegram, $last_message_object);
            break;
        case 'expression_choose_tags':
            expression_choose_tags($update, $telegram, $last_message_object);
            break;
        case 'claim_my_lovestars':
            claim_my_lovestars($update, $telegram, $last_message_object);
            break;
        case 'expression_choose_file':
            expression_choose_file($update, $telegram, $last_message_object);
            break;
        case 'confirm_remove_connection_by_id':
            confirm_remove_connection_by_id($update, $telegram, $last_message_object);
            break;
        case 'accept_or_decline_pending_connection_by_id':
            accept_or_decline_pending_connection_by_id($update, $telegram, $last_message_object);
            break;
        case 'resend_invitation':
            resend_invitation($update, $telegram, $last_message_object);
            break;
        case 'generate_codes':
            generate_invitation_codes($update, $telegram, $last_message_object);
            break;
        case 'generate_codes_step_enter_alias':
            generate_codes_step_enter_alias($update, $telegram, $last_message_object);
            break;
        case 'report_an_issue':
            report_an_issue($update, $telegram, $last_message_object);
            break;
        case 'interests_answers_fillup':
            interests_answers_fillup($update, $telegram, false);
            break;
        case 'interests_answers_fillup_ignore_input':
            interests_answers_fillup($update, $telegram, true);
            break;
        case 'expression_choose_expiration':
            expression_choose_expiration($update, $telegram, $last_message_object);
            break;
        case 'update_json_profile':
            update_json_profile($update, $telegram, $last_message_object);
            break;
        case 'write_json_profile':
            write_json_profile($update, $telegram, $last_message_object);
            break;
        case 'upload_avatar':
            upload_avatar($update, $telegram, $last_message_object);
            break;
		default:
			$telegram->commandsHandler(true);
			break;
	}
}

function assign_user_to_active_teacher($update, $telegram) {
	$userPublicAlias = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('assign-teacher-to-user', ['telegram_id' => $update->getMessage()->chat->id, 'user_public_alias' => $userPublicAlias]);
	
	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $result['text'], 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'assign_user_to_active_teacher'
					]),
					Keyboard::inlineButton([
						'text' => 'Show my teachers',
						'callback_data' => 'list_my_teachers'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'The assignment was successful.',
		'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Show commands',
						'callback_data' => 'help'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
}

function teacher_set_active($update, $telegram) {
	$publicAlias = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-active-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_public_alias' => $publicAlias]);
	
	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $result['text'], 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'set_active_teacher'
					]),
					Keyboard::inlineButton([
						'text' => 'Show my teachers',
						'callback_data' => 'list_my_teachers'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher '.$result['teacher']['title'].'(@' . $result['teacher']['publicAlias'] . ') set as an active.',
		'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Show commands',
						'callback_data' => 'help'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
}

function teacher_update_title($update, $telegram) {
	$teacherName = trim($update->getMessage()->text);

	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_title' => $teacherName]);

	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $result['text'], 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'teacher_update_title'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	} else {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher successfully updated.', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Show my teachers',
						'callback_data' => 'list_my_teachers'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
}

function teacher_update_public_alias($update, $telegram) {
	$publicAlias = trim($update->getMessage()->text);
	
	if (strpos($publicAlias, ' ') !== false) {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher public alias cannot contain spaces (should follow typical username style)!', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'teacher_update_public_alias'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_public_alias' => $publicAlias]);

	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $result['text'], 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'teacher_update_public_alias'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	} else {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher successfully updated.', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Show my teachers',
						'callback_data' => 'list_my_teachers'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
}

function teacher_update_description($update, $telegram) {
	$description = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_description' => $description]);
	
	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $result['text'], 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'teacher_update_description'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	} else {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher successfully updated.', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Show my teachers',
						'callback_data' => 'list_my_teachers'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
}

function teacher_update_hashtags($update, $telegram) {
	$hashtags = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_hashtags' => $hashtags]);
	
	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $result['text'], 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'teacher_update_hashtags'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	} else {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher successfully updated.', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Show my teachers',
						'callback_data' => 'list_my_teachers'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
}

function teacher_create($update, $telegram) {
	$teacherName = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('become-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_title' => $teacherName]);
	
	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'An error has occurred.', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'teacher_create'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}

    $telegram->triggerCommand('teacher_create_step_2', $update);
    set_command_to_last_message('teacher_create_step_2', $update->getMessage()->chat->id);
}

function teacher_create_step_2($update, $telegram) {
    $publicAlias = trim($update->getMessage()->text);

    if (strpos($publicAlias, ' ') !== false) {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher public alias cannot contain spaces (should follow typical username style)!', 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => 'Try again',
                        'callback_data' => 'teacher_create_step_2'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        return false;
    }

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_public_alias' => $publicAlias]);

    $telegram->triggerCommand('teacher_create_step_3', $update);
    set_command_to_last_message('teacher_create_step_3', $update->getMessage()->chat->id);
}

function teacher_create_step_3($update, $telegram) {
    $description = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_description' => $description]);

    $telegram->triggerCommand('teacher_create_step_4', $update);
    set_command_to_last_message('teacher_create_step_4', $update->getMessage()->chat->id);
}

function teacher_create_step_4($update, $telegram) {
    $hashtags = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('update-teacher', ['telegram_id' => $update->getMessage()->chat->id, 'teacher_hashtags' => $hashtags]);

    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Teacher "' . $result['teacher']['title'] . '" has been successfully created.',
        'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => 'Show my teachers',
                        'callback_data' => 'list_my_teachers'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
}

function after_registration_step_1($update, $telegram) {
	$email = trim($update->getMessage()->text);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'Wrong e-mail format!', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'registration_step_1'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-user-email', ['telegram_id' => $update->getMessage()->chat->id, 'email' => $email]);
	
	if($result['status'] === 'user_with_email_exist') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => 'A user with such a e-mail already exists!', 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => 'Try again',
						'callback_data' => 'registration_step_1'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->triggerCommand('registration_step_2', $update);
	set_command_to_last_message('registration_step_2', $update->getMessage()->chat->id);
}

function after_registration_step_2($update, $telegram) {
	$publicAlias = trim($update->getMessage()->text);
	$user = user_is_verified($update->getMessage()->chat->id)['user'];
	
	if (strpos($publicAlias, ' ') !== false) {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('User public alias cannot contain spaces (should follow typical username style)!', $user['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $user['language']),
						'callback_data' => 'registration_step_2'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-public-alias', ['telegram_id' => $update->getMessage()->chat->id, 'publicAlias' => $publicAlias]);
	
	if ($result['status'] === 'error' && !empty($result['type']) && $result['type'] === 'user_with_publicalias_exist') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Such public alias already exists.', $result['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $result['user']['language']),
						'callback_data' => 'registration_step_2'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->triggerCommand('registration_step_invitation_code', $update);
	
	set_command_to_last_message('registration_step_invitation_code', $update->getMessage()->chat->id);
}

function registration_step_invitation_code($update, $telegram) {
	$code = trim($update->getMessage()->text);
	$user = user_is_verified($update->getMessage()->chat->id)['user'];

    //if($result['status']) return false;

	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-invitation-code', ['telegram_id' => $update->getMessage()->chat->id, 'code' => $code]);

	if(isset($result['status'])==false OR $result['status'] !== 'success') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($result['text'], $user['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $user['language']),
						'callback_data' => 'registration_step_invitation_code'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}

//TO ASK FOR PASSWORD UNCOMMENT BELOW and in function choose_step
/*	if(empty($user['password_hash'])) {
		$telegram->triggerCommand('registration_step_3', $update);
		set_command_to_last_message('registration_step_3', $update->getMessage()->chat->id);
	} else {*/
    TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram,$user,"\xF0\x9F\x91\x8B");
    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id,
			'text' => __('Congratulations, you have successfully registered!', $user['language']),
			'reply_markup' => Keyboard::make([
				'inline_keyboard' =>  [
					[
						Keyboard::inlineButton([
							'text' => __('View a list of commands.', $user['language']),
							'callback_data' => 'help'
						])
					]
				],
				'resize_keyboard' => true,
			])]);
        //here we come once, after registration only
        $resp = $lcApi->makeRequest('set-user-registration-lovecoins', ['telegram_id' => $update->getMessage()->chat->id]);
        if($resp['status'] === false OR $resp['status'] === 'error'){
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($resp['message'], $user['language'])]);
        } else {
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Congratulations! You have received your first Lovestar!', $user['language'])]);

            if(!empty($result['register_with_lovestars']) && $result['register_with_lovestars'] === true) {
                $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => sprintf(__("Bonus Lovestars have been added to your account courtesy of a special invitation code. Your total Lovestars tally is now %s. You can always check your Lovestars balance in the \"My Lovestars\" section. \nStay tuned for updates on all the cool things you can do with those Lovestars as our platform expands its functionality.", $user['language']), $result['current_lovestars'])]);
            }
        }
//	}

/*    //send message to go code owner
    if($result['status'] === 'success' AND isset($result['owner_user'])){
        if (empty($user['telegram_alias']))
            $telegram_alias = '';
        else
            $telegram_alias = ' (@'.$user['telegram_alias'].')';
        $telegram->sendMessage(['chat_id' => $result['owner_user']['telegram'], 'text' => sprintf(__("Congratulations! You have received one Lovestar because %s%s registered on Zeya888 with your invitation code (%s). You now have %s Lovestars.", $result['owner_user']['language']), $user['publicAlias'], $telegram_alias, $code, $result['owner_user']['currentLovestarsCounter'])]);
    }
    //send message to code owner connections
    if(isset($result['code_owner_connections']) AND !isset($result['code_owner_connections']['status'])){
        foreach ($result['code_owner_connections'] as $conn_user){
            if (empty($user['telegram_alias']))
                $telegram_alias = '';
            else
                $telegram_alias = ' (@'.$user['telegram_alias'].')';
            if (empty($result['owner_user']['telegram_alias']))
                $telegram_alias2 = '';
            else
                $telegram_alias2 = ' (@'.$result['owner_user']['telegram_alias'].')';
            $telegram->sendMessage(['chat_id' => $conn_user['telegram'], 'text' => sprintf(__("Congratulations! You have received one Lovestar because %s%s registered on Zeya888 via the invitation of your connection %s%s. You now have %s Lovestars.", $conn_user['language']), $user['publicAlias'], $telegram_alias, $result['owner_user']['publicAlias'], $telegram_alias2, $conn_user['currentLovestarsCounter'])]);
        }
    }*/
}

function after_registration_step_3($update, $telegram) {
	$password = urlencode(base64_encode(trim($update->getMessage()->text)));

	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-user-password', ['telegram_id' => $update->getMessage()->chat->id, 'password' => $password]);

    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id,
		'text' => __('Congratulations, you have successfully registered!', $result['user']['language']),
		'reply_markup' => Keyboard::make([
        'inline_keyboard' =>  [
            [
                Keyboard::inlineButton([
                    'text' => __('View a list of commands.', $result['user']['language']),
                    'callback_data' => 'help'
                ])
            ]
        ],
        'resize_keyboard' => true,
    ])]);
    //here we come once, after registration only
    TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram,$result['user'],"\xF0\x9F\x91\x8B");
    $resp = $lcApi->makeRequest('set-user-registration-lovecoins', ['telegram_id' => $update->getMessage()->chat->id]);
    if($resp['status'] === false OR $resp['status'] === 'error'){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($resp['message'], $result['user']['language'])]);
    } else {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Congratulations! You have received your first Lovestar!', $result['user']['language'])]);
    }

//	$telegram->triggerCommand('registration_step_4', $update);
	
//	set_command_to_last_message('registration_step_4', $update->getMessage()->chat->id);
}

function after_registration_step_4($update, $telegram) {
	$code = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-user-verified', ['telegram_id' => $update->getMessage()->chat->id, 'code' => $code]);
	
	if($result['status'] === 'wrong_code') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Incorrect code!', $result['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $result['user']['language']),
						'callback_data' => 'registration_step_4'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id,
		'text' => __('Congratulations, you have successfully registered!', $result['user']['language']),
		'reply_markup' => Keyboard::make([
		'inline_keyboard' =>  [
			[
				Keyboard::inlineButton([
					'text' => __('View a list of commands.', $result['user']['language']),
					'callback_data' => 'help'
				])
			]
		],
		'resize_keyboard' => true,
	])]);
}

function update_my_email($update, $telegram) {
	$email = trim($update->getMessage()->text);
	$user = user_is_verified($update->getMessage()->chat->id);
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Wrong e-mail format!', $user['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $user['user']['language']),
						'callback_data' => 'update_my_email'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('get-code-for-new-email', ['telegram_id' => $update->getMessage()->chat->id, 'email' => $email]);
	
	if($result['status'] === 'user_with_email_exist') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('A user with such a e-mail already exists!', $user['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $user['user']['language']),
						'callback_data' => 'update_my_email'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->triggerCommand('update_my_email_confirm_code', $update);
	set_command_to_last_message('update_my_email_confirm_code', $update->getMessage()->chat->id);
}

function update_my_email_confirm_code($update, $telegram) {
	$code = trim($update->getMessage()->text);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('update-user-email', ['telegram_id' => $update->getMessage()->chat->id, 'code' => $code]);
	
	if($result['status'] === 'wrong_code') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Incorrect code!', $result['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $result['user']['language']),
						'callback_data' => 'update_my_email_confirm_code'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('You have successfully changed e-mail.', $result['user']['language']), 'reply_markup' => Keyboard::make([
		'inline_keyboard' =>  [
			[
				Keyboard::inlineButton([
					'text' => __('Show my data', $result['user']['language']),
					'callback_data' => 'my_data'
				])
			]
		],
		'resize_keyboard' => true,
	])]);
}

function update_my_public_alias($update, $telegram) {
	$publicAlias = trim($update->getMessage()->text);
	$user = user_is_verified($update->getMessage()->chat->id)['user'];
	
	if (strpos($publicAlias, ' ') !== false) {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('User public alias cannot contain spaces (should follow typical username style)!', $user['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $user['language']),
						'callback_data' => 'update_my_public_alias'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-public-alias', ['telegram_id' => $update->getMessage()->chat->id, 'publicAlias' => $publicAlias]);
	
	if($result['status'] === 'error') {
		$text = __('Error, try again', $user['language']);
		if(!empty($result['type']) && $result['type'] === 'user_with_publicalias_exist') {
			$text = __('Such public alias already exists.', $user['language']);
		}
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $text, 'reply_markup' => Keyboard::make([
			'inline_keyboard' => [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $user['language']),
						'callback_data' => 'update_my_public_alias'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('You have successfully changed your public alias.', $user['language']), 'reply_markup' => Keyboard::make([
		'inline_keyboard' =>  [
			[
				Keyboard::inlineButton([
					'text' => __('Show my data', $user['language']),
					'callback_data' => 'my_data'
				])
			]
		],
		'resize_keyboard' => true,
	])]);
    //TGKeyboard::showMyDataKeyboard($update->getMessage()->chat->id,$telegram, $user, 'OK');

   return false;
}

function update_my_password($update, $telegram) {
	$password = urlencode(base64_encode(trim($update->getMessage()->text)));
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-user-password', ['telegram_id' => $update->getMessage()->chat->id, 'password' => $password]);
	
	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error, try again', $result['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $result['user']['language']),
						'callback_data' => 'update_my_password'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
		return false;
	}
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('You have successfully changed your password.', $result['user']['language']), 'reply_markup' => Keyboard::make([
		'inline_keyboard' =>  [
			[
				Keyboard::inlineButton([
					'text' => __('Show my data', $result['user']['language']),
					'callback_data' => 'my_data'
				])
			]
		],
		'resize_keyboard' => true,
	])]);
    //TGKeyboard::showMyDataKeyboard($update->getMessage()->chat->id,$telegram, $result['user'], 'OK');
    return false;
}

function events_create($update, $telegram) {
    $eventsUrl = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('events-url-add', ['telegram_id' => $update->getMessage()->chat->id, 'events_url' => $eventsUrl]);

    if($result['status'] === 'error') {
        $telegram->sendMessage([
			'chat_id' => $update->getMessage()->chat->id,
			'text' => __('An error has occurred.', $result['user']['language']),
			'disable_web_page_preview' => true,
			'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $result['user']['language']),
                        'callback_data' => 'events_create'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $result['user'], 'OK');

        return false;
    }

    $text = '';
    foreach ($result['events_result'] as $key => $events) {
        switch ($key) {
            case 'success':
                $text .= "\n\n" . __("Events successfully created:", $result['user']['language']);
                break;
            case 'url_already_exist':
                $text .= "\n\n" . __("Events already exist:", $result['user']['language']);
                break;
            case 'not_correct_url':
                $text .= "\n\n" . __("The links to events are wrong:", $result['user']['language']);
                break;
        }

        if(empty($events)) $text .= '0';
        else {
            foreach ($events as $event_url) {
                $text .= "\n" . $event_url;
            }
        }
    }

    $telegram->sendMessage([
		'chat_id' => $update->getMessage()->chat->id,
		'text' => $text,
		'disable_web_page_preview' => true,
		'reply_markup' => Keyboard::make([
        'inline_keyboard' =>  [
            [
                Keyboard::inlineButton([
                    'text' => __('Show my events', $result['user']['language']),
                    'callback_data' => 'get_my_events'
                ])
            ]
        ],
        'resize_keyboard' => true,
    ])]);
    //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $result['user'], 'OK');
    return false;
}

function choose_language($update, $telegram, $last_message_object) {
	$lang_to_set = explode('__', $last_message_object);
	$lang_to_set = $lang_to_set[1];
	
	$lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('set-user-language', ['telegram_id' => $update->getMessage()->chat->id, 'language' => $lang_to_set]);
	
	if(empty($result['user']['publicAlias']) || empty($result['user']['username'])) {
		$telegram->triggerCommand('registration_step_2', $update);
		set_command_to_last_message('registration_step_2', $update->getMessage()->chat->id);
	} else {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('You have successfully installed the language', $lang_to_set)]);
        $telegram->triggerCommand('help', $update);
        TGKeyboard::showMainKeyboard($update->getMessage()->chat->id, $telegram, $result['user'], 'Home');
	}
}

function suggest_new_language($update, $telegram) {
	$language_to_suggest = trim($update->getMessage()->text);
	
	$is_verified = user_is_verified($update->getMessage()->chat->id);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('send-notification-to-admin', ['telegram_id' => $update->getMessage()->chat->id, 'message' =>  __("Alarm! A user {userPublicAlias} suggested adding a new language:", $is_verified['user']['language']) . ' ' . $language_to_suggest]);
	
	//$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Thank you! Our administrators will consider your application :)', $result['user']['language'])]);
    //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], __('Thank you! Our administrators will consider your application :)', $is_verified['user']['language']));
}

function report_an_issue($update, $telegram, $last_message_object){
    $message = trim($update->getMessage()->text);

    $is_verified = user_is_verified($update->getMessage()->chat->id);

    $lcApi = new \LCAPPAPI();
    $lcApi->makeRequest('send-notification-to-admin', ['telegram_id' => $update->getMessage()->chat->id, 'message' =>  __("Alarm! A user {userPublicAlias} reported an issue:", $is_verified['user']['language']) . ' ' . $message]);
    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Thank you! Our administrators received your report', $is_verified['user']['language'])]);

}

function set_user_interests($update, $telegram) {
    $is_verified = user_is_verified($update->getMessage()->chat->id);
	$interests = trim($update->getMessage()->text);

    if(strlen($interests) >= 2000) {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error! The maximum allowed number of characters is 2000', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'set_user_interests'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
    }

    if(strlen($interests) < 2) {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error! The new element must be text and be more than 2 characters long.', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'set_user_interests'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
    }

    $interests = json_encode($interests);
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('set-user-interests', ['telegram_id' => $update->getMessage()->chat->id, 'entered_text' => $interests, 'user_lang' => $is_verified['user']['language']]);

	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error, try again', $result['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $result['user']['language']),
						'callback_data' => 'set_user_interests'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
	}
    //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
    $telegram->triggerCommand('my_interests_and_values', $update);
}

function my_interests_and_values($update, $telegram) {
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    $message = trim($update->getMessage()->text);
//    $message = removeEmoji(trim($update->getMessage()->text));

    $lcApi = new \LCAPPAPI();

    if(is_numeric($message)){
        $result = $lcApi->makeRequest('get-calculated-interest-by-list-number', ['telegram_id' => $update->getMessage()->chat->id, 'entered_text' => $message, 'user_lang' => $is_verified['user']['language']]);

        if(empty($result['choosed_interests'])) {
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('There is no such item', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
                'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => __('Try again', $is_verified['user']['language']),
                            'callback_data' => 'my_interests_and_values'
                        ])
                    ]
                ],
                'resize_keyboard' => true,
            ])]);
        } else {
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Do you really want to delete an item', $is_verified['user']['language']) . ' ' . '“'.$message.'. ' . trim($result['choosed_interests']) . '”?', 'reply_markup' => Keyboard::make([
                'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => __('Yes', $is_verified['user']['language']),
                            'callback_data' => 'remove_interest_from_list_by_number__' . $message
                        ]),
                        Keyboard::inlineButton([
                            'text' => __('No', $is_verified['user']['language']),
                            'callback_data' => 'my_interests_and_values'
                        ])
                    ]
                ],
                'resize_keyboard' => true,
            ])]);
        }
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
    }

    if(strlen($message) >= 50) {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error! The maximum allowed number of characters is 50', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'my_interests_and_values'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
    }

    if(strlen($message) < 2) {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error! The new element must be text and be more than 2 characters long.', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'my_interests_and_values'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
    }

	$result = $lcApi->makeRequest('add-interest-to-user-list', ['telegram_id' => $update->getMessage()->chat->id, 'entered_text' => $message, 'user_lang' => $is_verified['user']['language']]);

	if($result['status'] === 'error') {
		$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Error, try again', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Try again', $is_verified['user']['language']),
						'callback_data' => 'my_interests_and_values'
					])
				]
			],
			'resize_keyboard' => true,
		])]);
        //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
        return false;
	}
    //TGKeyboard::showMainKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');
    $telegram->triggerCommand('my_interests_and_values', $update);
	return false;
}

function remove_interest_from_list_by_number($update, $telegram, $last_message_object) {
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    $number_of_item = explode('__', $last_message_object);
    $number_of_item = $number_of_item[1];

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('remove-interest-from-user-list', ['telegram_id' => $update->getMessage()->chat->id, 'user_lang' => $is_verified['user']['language'], 'number_to_remove' => $number_of_item]);

    $telegram->triggerCommand('my_interests_and_values', $update);;
}

function add_new_connection($update, $telegram)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!isset($update->getMessage()['text'])){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Please enter text only. Try again.', $is_verified['user']['language'])]);
        set_command_to_last_message("add_new_connection", $update->getMessage()->chat->id);
        return;
    }
    $look_for = trim($update->getMessage()->text);
    if (str_contains($look_for, '@')) $look_for = mb_substr($look_for, 1);

    if(strlen($look_for)==0){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Mmmm...enter user alias. Try again.', $is_verified['user']['language'])]);
        set_command_to_last_message("add_new_connection", $update->getMessage()->chat->id);
        return;
    }

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('get-users-by-any-alias', ['alias' => $look_for,'telegram_id'=>$update->getMessage()->chat->id]);//'get-users-by-any-alias'
    if(isset($return_data['status']) AND $return_data['status']==='error'){
        //$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Sorry, there was an error, please contact the administrator.', $is_verified['user']['language'])]);
        //TGKeyboard::showConnectionsKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], __('Sorry, there was an error, please contact the administrator.', $is_verified['user']['language']));
        return;
    }
    //TGKeyboard::showConnectionsKeyboard($update->getMessage()->chat->id,$telegram, $is_verified['user'], 'OK');

    if(count($return_data)>1){
        $users_buttons=[];
        foreach ($return_data as $user){
            $button_text='';
            if(mb_strtolower($user['publicAlias'])===mb_strtolower($look_for))
                $button_text.=__('User alias', $is_verified['user']['language']).': '.$user['publicAlias'];
            if(mb_strtolower($user['telegram_alias'])===mb_strtolower($look_for))
                $button_text.=__('Telegram alias', $is_verified['user']['language']).': '.$user['telegram_alias'];

            $users_buttons[]=
               [
                   Keyboard::inlineButton([
                       'text' => $button_text,
                       'callback_data' => 'check_connection__'.$is_verified['user']['id'].'__'.$user['id']//'callback_data' => 'create_new_connection__'.$user['id']
                   ])
               ];
        }
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  $users_buttons,
            'resize_keyboard' => true
        ]);
        $options['chat_id'] = $update->getMessage()->chat->id;

        $options['text'] = __('Search results:', $is_verified['user']['language']);
        $telegram->sendMessage($options);
    }elseif(count($return_data)==1){
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Yes', $is_verified['user']['language']),
                        'callback_data' => 'check_connection__'.$is_verified['user']['id'].'__'.$return_data[0]['id']//'callback_data' => 'create_new_connection__'.$return_data[0]['id']
                    ]),
                    Keyboard::inlineButton([
                        'text' => __('No', $is_verified['user']['language']),
                        'callback_data' => 'help'
                    ])
                ]


            ],
            'resize_keyboard' => true
        ]);
        $options['chat_id'] = $update->getMessage()->chat->id;

        $options['text'] = __('Would you like to send request to this user to be part of your connections?', $is_verified['user']['language']);
        $telegram->sendMessage($options);

    }elseif(count($return_data)==0){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __("This person is not registered on Zeya.", $is_verified['user']['language'])]);
        $lcApi = new \LCAPPAPI();
        $return_data = $lcApi->makeRequest('get-my-not-used-invitation-codes', ['telegram_id'=>$update->getMessage()->chat->id]);//'get-users-by-any-alias'
        if(isset($return_data['status']) AND $return_data['status']==='error'){
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Sorry, there was an error, please contact the administrator.', $is_verified['user']['language'])]);
            return;
        }
        if(count($return_data['codes'])>9) {
            $text = '';
            foreach ($return_data['codes'] as $code){
                $text .= $code['code']."\n";
            }
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id,'text' => $text]);

        } else {
            foreach ($return_data['codes'] as $code){
                $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id,'text' => $code['code']]);
            }
        }

        if(empty($return_data['codes'])){
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id,'text' => __('You have no invitation codes available', $is_verified['user']['language'])]);
        }
        else{
            $options = [
                'chat_id' => $update->getMessage()->chat->id,
            ];
            $options['text'] = __("You can forward any code which is not used to any of your telegram contacts along with the message below", $is_verified['user']['language']);
            $telegram->sendMessage($options);

            $options['text'] = "\xF0\x9F\x94\xA5 " . __("This is an invitation to Zeya", $is_verified['user']['language']);
            $telegram->sendMessage($options);
        }
    }
}
function create_new_connection($update, $telegram, $user_id_2)//,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;

    //$user_id_2=intval(substr($callbackName,strpos($callbackName,'__')+2));
    $telegram_id = $update->getMessage()->chat->id;

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('set-user-connection', ['telegram_id' => $telegram_id,'user_id_2' => $user_id_2]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    $telegram->sendMessage(['chat_id' => $telegram_id,'text'=>__('Request has been sent.', $is_verified['user']['language'])]);

    //notify potential friend
    //we use notification system built-in set-user-connection
/*    $return_data = $lcApi->makeRequest('get-user-by-user-id', ['user_id' => $user_id_2]);
    $user_id_1=$is_verified['user']['id'];
    if($return_data['status'] === 'success') {
        $user_text='';
        if(!empty($is_verified['user']['telegram_alias'])) $user_text=' (@'.$is_verified['user']['telegram_alias'].')';
        $options['chat_id'] = $return_data['user']['telegram'];
        $options['text'] = $is_verified['user']['publicAlias'].$user_text.' '.__("sent you a connection request.", $return_data['user']['language']);
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Accept', $return_data['user']['language']),
                        'callback_data' => 'accept_connection__'.$user_id_1.'__'.$user_id_2
                    ]),
                    Keyboard::inlineButton([
                        'text' => __('Decline', $return_data['user']['language']),
                        'callback_data' => 'decline_connection__'.$user_id_1.'__'.$user_id_2
                    ])
                ]


            ],
            'resize_keyboard' => true
        ]);
        $telegram->sendMessage($options);
    }*/
    return true;
}
function resend_invitation($update, $telegram, $callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $ids = explode('__',$callbackName);
    $user_id_2 = $ids[1];
    //$user_id_2=intval(substr($callbackName,strpos($callbackName,'__')+2));
    $telegram_id = $update->getMessage()->chat->id;

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('get-user-sent-pending-invitation', ['telegram_id' => $telegram_id, 'user_id_2' => $user_id_2]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }

    if(intval($return_data['connection']['attempts']) > 1){//cannot send again
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("This invitation was already sent by you and still pending.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('increment-user-sent-pending-invitation', ['telegram_id' => $telegram_id,'user_id_2' => $user_id_2]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    $telegram->sendMessage(['chat_id' => $telegram_id,'text'=>__('This invitation was re-sent by you.', $is_verified['user']['language'])]);

    //notify potential friend
    $return_data = $lcApi->makeRequest('get-user-by-user-id', ['user_id' => $user_id_2]);
    $user_id_1=$is_verified['user']['id'];
    if($return_data['status'] === 'success') {
        $user_text='';
        if(!empty($is_verified['user']['telegram_alias'])) $user_text=' (@'.$is_verified['user']['telegram_alias'].')';
        $options['chat_id'] = $return_data['user']['telegram'];
        $options['text'] = $is_verified['user']['publicAlias'].$user_text.' '.__("sent you a connection request.", $return_data['user']['language']);
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Accept', $return_data['user']['language']),
                        'callback_data' => 'accept_connection__'.$user_id_1.'__'.$user_id_2
                    ]),
                    Keyboard::inlineButton([
                        'text' => __('Decline', $return_data['user']['language']),
                        'callback_data' => 'decline_connection__'.$user_id_1.'__'.$user_id_2
                    ])
                ]


            ],
            'resize_keyboard' => true
        ]);
        $telegram->sendMessage($options);
    }
    return true;
}
function confirm_remove_connection_by_id($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $ids = explode('__',$callbackName);
    $connection_id=$ids[1];
    $foe_id = $ids[2];
    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('get-user-by-user-id', ['telegram_id' => $telegram_id,'user_id' => $foe_id]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }

    if(!empty($return_data['user']['telegram_alias']))
        $usertext = $return_data['user']['publicAlias'].' (@'.$return_data['user']['telegram_alias'].')';
    else
        $usertext = $return_data['user']['publicAlias'];

    $options = ['chat_id' => $telegram_id];
    $options['text'] = __("Do you confirm that you want to remove from your connections user", $is_verified['user']['language']).' '.$usertext.'?';
    $options['reply_markup'] = Keyboard::make([
        'inline_keyboard' =>  [
            [
            Keyboard::inlineButton([
                'text' => __('Yes', $is_verified['user']['language']),
                'callback_data' => 'delete_connection_by_id__'.$connection_id
            ]),
            Keyboard::inlineButton([
                'text' => __('No', $is_verified['user']['language']),
                'callback_data' => 'help'
            ])
            ]
        ],
        'resize_keyboard' => true
    ]);
    $telegram->sendMessage($options);
    return true;
}
function delete_connection_by_id($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $connection_id=intval(substr($callbackName,strpos($callbackName,'__')+2));
    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('delete-user-connection', ['telegram_id' => $telegram_id,'connection_id' => $connection_id]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    $telegram->sendMessage(['chat_id' => $telegram_id,'text'=>__('Connection has been deleted.', $is_verified['user']['language'])]);
    return true;
}
function accept_or_decline_pending_connection_by_id($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $ids = explode('__',$callbackName);
    //$connection_id=$ids[1];
    $person_id = $ids[2];
    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('get-user-by-user-id', ['telegram_id' => $telegram_id,'user_id' => $person_id]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }

    if(!empty($return_data['user']['telegram_alias']))
        $usertext = $return_data['user']['publicAlias'].' (@'.$return_data['user']['telegram_alias'].')';
    else
        $usertext = $return_data['user']['publicAlias'];

    $options = ['chat_id' => $telegram_id];
    $options['text'] = sprintf(__('Would you like to confirm a request from %s to be part of your connections?', $is_verified['user']['language']),$usertext);
    $options['reply_markup'] = Keyboard::make([
        'inline_keyboard' =>  [
            [
                Keyboard::inlineButton([
                    'text' => __('Accept', $is_verified['user']['language']),
                    'callback_data' => 'accept_connection__'.$person_id.'__'.$is_verified['user']['id']
                ]),
                Keyboard::inlineButton([
                    'text' => __('Decline', $is_verified['user']['language']),
                    'callback_data' => 'decline_connection__'.$person_id.'__'.$is_verified['user']['id']
                ])
            ]
        ],
        'resize_keyboard' => true
    ]);
    $telegram->sendMessage($options);
    return true;
}
function accept_connection($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $ids=explode('__',$callbackName);
    if(!isset($ids[3])) $ids[3] = NULL;
    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('accept-user-connection-request', ['telegram_id' => $telegram_id,'user_id_1' => intval($ids[1]),'user_id_2' => intval($ids[2]),'notification_id' => $ids[3]]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    $telegram->sendMessage(['chat_id' => $telegram_id,'text'=>__('Request has been accepted.', $is_verified['user']['language'])]);
    return true;
}
function decline_connection($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $ids=explode('__',$callbackName);
    if(!isset($ids[3])) $ids[3] = NULL;
    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('decline-user-connection-request', ['telegram_id' => $telegram_id,'user_id_1' => intval($ids[1]),'user_id_2' => intval($ids[2]),'notification_id' => $ids[3]]);

    if($return_data['status'] === 'error' || empty($return_data)) {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    $telegram->sendMessage(['chat_id' => $telegram_id,'text'=>__('Request has been declined.', $is_verified['user']['language'])]);
    return true;
}

function check_connection($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $ids=explode('__',$callbackName);
    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('check-user-connection', ['telegram_id' => $telegram_id,'user_id_1' => intval($ids[1]),'user_id_2' => intval($ids[2])]);

    if($return_data['status'] === 'error') {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    if($return_data['connection']===NULL){
        create_new_connection($update, $telegram,$ids[2]);
    }elseif($return_data['connection']['status']==='pending'){
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("This invitation was already sent by you and still pending.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
    }elseif($return_data['connection']['status']==='accepted'){
        $options['chat_id'] = $telegram_id;
        if($return_data['connection']['user_id_1']==$ids[1])
            $options['text'] = __("This invitation was already sent by you and accepted.", $is_verified['user']['language']);
        else
            $options['text'] = __("This invitation was already sent to you and accepted.", $is_verified['user']['language']);

        $telegram->sendMessage($options);
    }elseif($return_data['connection']['status']==='declined'){
        if($return_data['connection']['user_id_1']==$ids[1]) {//sent by us, friend rejected
            $options['chat_id'] = $telegram_id;
            $options['text'] = __("This invitation was already sent by you and rejected.", $is_verified['user']['language']);
            $telegram->sendMessage($options);
        }
        if($return_data['connection']['user_id_2']==$ids[1]) {//sent to us, we rejected
            create_new_connection($update, $telegram,$ids[2]);
        }
    }

    return true;
}

function ask_to_revert_connection($update, $telegram,$callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    $telegram_id = $update->getMessage()->chat->id;
    $ids=explode('__',$callbackName);

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('get-user-by-user-id', ['telegram_id' => $telegram_id,'user_id' => intval($ids[2])]);

    if($return_data['status'] === 'error') {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }

    $username = $return_data['user']['publicAlias'];
    if(empty($username)){
        $username=$return_data['user']['full_name'];
        if(empty($username)){
            $username=$return_data['user']['username'];
        }
    }

    $options['chat_id'] = $telegram_id;
    $options['text'] = __("Are you sure that you want to revert reject and accept this invite from", $is_verified['user']['language']).' '.$username.'?';
    $options['reply_markup'] = Keyboard::make([
        'inline_keyboard' =>  [
            [
                Keyboard::inlineButton([
                    'text' => __('Yes', $is_verified['user']['language']),
                    'callback_data' => 'accept_connection__'.$ids[2].'__'.$ids[1]
                ])
            ]
        ],
        'resize_keyboard' => true,
    ]);

    $telegram->sendMessage($options);
}

function generate_invitation_codes($update, $telegram, $callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;
    if(!isset($update->getMessage()['text'])){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Please enter number only. Try again.', $is_verified['user']['language'])]);
        set_command_to_last_message("generate_codes", $update->getMessage()->chat->id);
        return;
    }
    $amount = trim($update->getMessage()->text);
    if (is_numeric($amount) == false) {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Please enter number only. Try again.', $is_verified['user']['language'])]);
        set_command_to_last_message("generate_codes", $update->getMessage()->chat->id);
        return;
    }

    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Type the platform alias of the user who will receive those invitation codes starting with @', $is_verified['user']['language'])]);
    set_command_to_last_message("generate_codes_step_enter_alias", $update->getMessage()->chat->id,['amount' => $amount]);

}

function generate_codes_step_enter_alias($update, $telegram, $last_message_object)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!isset($update->getMessage()['text'])){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Please enter text only. Try again.', $is_verified['user']['language'])]);
        set_command_to_last_message("generate_codes_step_enter_alias", $update->getMessage()->chat->id,['amount' => $last_message_object->amount]);
        return;
    }
    $look_for = trim($update->getMessage()->text);
    if (str_contains($look_for, '@')) $look_for = mb_substr($look_for, 1);

    if(strlen($look_for)==0){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Mmmm...enter user alias. Try again.', $is_verified['user']['language'])]);
        set_command_to_last_message("generate_codes_step_enter_alias", $update->getMessage()->chat->id,['amount' => $last_message_object->amount]);
        return;
    }

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('generate-codes', ['amount' => $last_message_object->amount, 'telegram_id'=>$update->getMessage()->chat->id, 'alias' => $look_for]);
    if(isset($return_data['status']) AND $return_data['status']==='error'){
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($return_data['text'], $is_verified['user']['language'])]);
        set_command_to_last_message("generate_codes_step_enter_alias", $update->getMessage()->chat->id,['amount' => $last_message_object->amount]);
        return;
    }
    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => sprintf(__('%d invite codes have been generated and a notification has been sent to %s', $is_verified['user']['language']),$last_message_object->amount,$look_for)]);

    //send message to codes owner
    $telegram->sendMessage(['chat_id' => $return_data['owner_user']['telegram'], 'text' => sprintf(__('You were granted %d invitation codes. Use /my_invitation_codes command to access them.', $return_data['owner_user']['language']),$last_message_object->amount)]);

}
function expression_choose_type($update, $telegram, $callbackName)
{//DEPRECATED
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;

    $telegram_id = $update->getMessage()->chat->id;
    $description = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('set-creative-type-to-expression', ['telegram_id' => $telegram_id, 'type_title' => $description]);

    if($result['status'] === 'error') {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($result['text'], $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'expression_choose_type'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        return false;
    }

    //TGKeyboard::showMainKeyboard($telegram_id, $telegram, $is_verified['user'], __('Thank you', $is_verified['user']['language']));

    $telegram->triggerCommand('expression_choose_description', $update);
    set_command_to_last_message('expression_choose_description', $update->getMessage()->chat->id);
}

function expression_choose_description($update, $telegram, $callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;

    $telegram_id = $update->getMessage()->chat->id;
    $description = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('set-description-to-expression', ['telegram_id' => $telegram_id, 'desc' => $description]);

    if($result['status'] === 'error') {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($result['text'], $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'expression_choose_description'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        return false;
    }

    $telegram->triggerCommand('expression_choose_expiration', $update);
    //set_command_to_last_message('expression_choose_tags', $update->getMessage()->chat->id);
}

function expression_choose_tags($update, $telegram, $callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;

    $telegram_id = $update->getMessage()->chat->id;
    $tags = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('set-tags-to-expression', ['telegram_id' => $telegram_id, 'tags' => $tags]);

    if($result['status'] === 'error') {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($result['text'], $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'expression_choose_tags'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        return false;
    }

    $telegram->triggerCommand('expression_choose_file', $update);

    set_command_to_last_message('expression_choose_file', $update->getMessage()->chat->id);
    }

function expression_choose_file($update, $telegram, $callbackName)
{
    $message = $update->getMessage();
    $telegram_id = $message->chat->id;

    $lcApi = new \LCAPPAPI();
    $is_verified = user_is_verified($telegram_id);
    if(!$is_verified['status']) return false;

    $supported_formats = ["jpg", "jpeg", "gif", "png","mp4", "mov", "webm", "3gp", "ogg",'mp3','aac','wav','wma','flac'];
    $message_text = trim($message->text);

    // if its url to content
    if(filter_var($message_text, FILTER_VALIDATE_URL)){
        $arr = explode('.', $message_text);
        $ext = strtolower($arr[count($arr)-1]);
        if(in_array($ext,$supported_formats)){
                $result = $lcApi->makeRequest('set-url-content-to-expression', ['telegram_id' => $telegram_id, 'url' => $message_text]);

                $telegram->triggerCommand('expression_confirm_creation', $update);
                set_command_to_last_message('expression_confirm_creation', $telegram_id);

                return false;
        } else {
            $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('unsupported format, choose different file', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
                'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => __('Try again', $is_verified['user']['language']),
                            'callback_data' => 'expression_choose_file'
                        ])
                    ]
                ],
                'resize_keyboard' => true,
            ])]);
            return false;
        }

    }


    if(!empty($message['document']) || !empty($message['photo']) || !empty($message['video']) || !empty($message['voice']) || !empty($message['audio'])) {

        if(!empty($message['document'])) {
            $file_id = $message['document']['file_id'];
        } else if (!empty($message['photo'])) {
            $file_id = end($message['photo'])['file_id'];
        } else if(!empty($message['video'])) {
            $file_id = $message['video']['file_id'];
        } else if(!empty($message['voice'])) {
            $file_id = $message['voice']['file_id'];
        } elseif(!empty($message['audio'])){
            $file_id = $message['audio']['file_id'];
        }

        if(!empty($file_id)) {
            $result = $lcApi->makeRequest('set-file-content-to-expression', ['telegram_id' => $telegram_id, 'file_id' => $file_id, 'supported_formats' => $supported_formats]);
            if($result['status'] === 'success') {
                $telegram->triggerCommand('expression_confirm_creation', $update);
                set_command_to_last_message('expression_confirm_creation', $update->getMessage()->chat->id);

                return false;
            }
            if($result['status'] === 'error' AND $result['text'] === 'unsupported_format') {
                $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('unsupported format, choose different file', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
                    'inline_keyboard' =>  [
                        [
                            Keyboard::inlineButton([
                                'text' => __('Try again', $is_verified['user']['language']),
                                'callback_data' => 'expression_choose_file'
                            ])
                        ]
                    ],
                    'resize_keyboard' => true,
                ])]);
                return false;
            }

        }
    }

    if(!empty($message_text)){//TEXT
        mb_substitute_character(mb_ord('_', 'UTF-8'));//character to replace bad json symbols in server response
        $message_text=mb_convert_encoding($message_text,'UTF-8','UTF-8');
        $result = $lcApi->makeRequest('set-text-content-to-expression', ['telegram_id' => $telegram_id, 'text' => $message_text], 'array', 'POST');

        if($result['status'] === 'success')
        {//if our format was TEXT we set text, otherwise error
            $telegram->triggerCommand('expression_confirm_creation', $update);
            set_command_to_last_message('expression_confirm_creation', $telegram_id);
            return false;
        }
    }

    $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Please attach file of CE', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
        'inline_keyboard' =>  [
            [
                Keyboard::inlineButton([
                    'text' => __('Try again', $is_verified['user']['language']),
                    'callback_data' => 'expression_choose_file'
                ])
            ]
        ],
        'resize_keyboard' => true,
    ])]);
}

function claim_my_lovestars($update, $telegram, $callbackName)
{
    $is_verified = user_is_verified($update->getMessage()->chat->id);
    if(!$is_verified['status']) return false;

    $telegram_id = $update->getMessage()->chat->id;
    $code = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('claim-my-lovestars', ['telegram_id' => $telegram_id, 'code' => $code]);

    if($result['status'] === 'error') {
        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __($result['text'], $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Try again', $is_verified['user']['language']),
                        'callback_data' => 'claim_my_lovestars'
                    ])
                ]
            ],
            'resize_keyboard' => true,
        ])]);
        return false;
    }

    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => sprintf(__('Congrats! You received %d Lovestars 💜', $is_verified['user']['language']), (int) $result['emitted_lovestars'])]);
}

function interests_answers_fillup($update, $telegram, $ignore_input) {
    $answer_number_array =
        ['TIME_TRAVEL',
        'UNLIMITED_ISLAND',
        'MAGIC_WISH',
        'INTEREST_FESTIVAL',
        'LIFE_BOOK'];

    $telegram_id = $update->getMessage()->chat->id;
    $user = user_is_verified($telegram_id);
    if(!$user['status']) {
        return false;
    }
    $lcApi = new \LCAPPAPI();
    $interests_resp = $lcApi->makeRequest('get-interests-answers', ['telegram_id' => $telegram_id]);

    if($interests_resp['status'] === 'error') {
        $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
        return false;
    }
    $interests_answers = [];
    foreach ($interests_resp['data'] as $answ){
        $interests_answers[$answ['question_type']] = $answ['response'];
    }
    //$telegram->sendMessage(['chat_id' => $telegram_id, 'text' => json_encode($interests_answers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]);

    $message_counter = $user['user']['message_counter'];
    if($interests_resp['status'] === 'success'){
        if($ignore_input == false){//process previous answer
            $answer = trim($update->getMessage()->text);
            $data = $lcApi->makeRequest('set-interests-answers', ['telegram_id' => $telegram_id, 'question_type' => $answer_number_array[$message_counter-1], 'answer' => $answer]);
            if($data['status'] === 'error') {
                $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
                return false;
            }
        }
        //set counter to next question for the next run
        $data = $lcApi->makeRequest('set-message-counter', ['telegram_id' => $telegram_id, 'message_counter' => $message_counter+1]);
        if($data['status'] === 'error') {
            $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
            return false;
        }

        $options = [];
        $options ['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __("Skip it!", $user['user']['language']),
                        'callback_data' => 'interests_answers_fillup_ignore_input'
                    ]),
                    //Keyboard::inlineButton([
                       // 'text' => __('No', $user['user']['language']),
                       // 'callback_data' => 'help'
                    //])
                ]
            ],
            'resize_keyboard' => true
        ]);
        switch ($message_counter){
            case 0:
                $options ['photo'] = InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/frontend/web/bot_images/time_travel.png','image.png');
                $options ['caption'] = __("Imagine you have a time machine", $user['user']['language']);

                $telegram->sendPhoto($options);

                if(isset($interests_answers[$answer_number_array[$message_counter]]))
                    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Your saved answer:", $user['user']['language'])."\n".$interests_answers[$answer_number_array[$message_counter]]]);

                set_command_to_last_message('interests_answers_fillup', $telegram_id);
                break;
            case 1:
                $options ['photo'] = InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/frontend/web/bot_images/borderless_island.png','image.png');

                $options ['caption'] = __("You are stranded on a desert island", $user['user']['language']);
                $telegram->sendPhoto($options);

                if(isset($interests_answers[$answer_number_array[$message_counter]]))
                    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Your saved answer:", $user['user']['language'])."\n".$interests_answers[$answer_number_array[$message_counter]]]);

                set_command_to_last_message('interests_answers_fillup', $telegram_id);
                break;
            case 2:
                $options ['photo'] = InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/frontend/web/bot_images/magical_wish.png','image.png');
                $options ['caption'] = __("Suddenly you have the opportunity to fulfill", $user['user']['language']);
                $telegram->sendPhoto($options);

                if(isset($interests_answers[$answer_number_array[$message_counter]]))
                    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Your saved answer:", $user['user']['language'])."\n".$interests_answers[$answer_number_array[$message_counter]]]);

                set_command_to_last_message('interests_answers_fillup', $telegram_id);
                break;
            case 3:
                $options ['photo'] = InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/frontend/web/bot_images/festival_of_interests.png','image.png');
                $options ['caption'] = __("Imagine you are organizing a festival", $user['user']['language']);
                $telegram->sendPhoto($options);

                if(isset($interests_answers[$answer_number_array[$message_counter]]))
                    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Your saved answer:", $user['user']['language'])."\n".$interests_answers[$answer_number_array[$message_counter]]]);

                set_command_to_last_message('interests_answers_fillup', $telegram_id);
                break;
            case 4:
                $options ['photo'] = InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/frontend/web/bot_images/a_book_of_life.png','image.png');
                $options ['caption'] = __("If you were writing a book about your life", $user['user']['language']);
                $telegram->sendPhoto($options);

                if(isset($interests_answers[$answer_number_array[$message_counter]]))
                    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Your saved answer:", $user['user']['language'])."\n".$interests_answers[$answer_number_array[$message_counter]]]);

                set_command_to_last_message('interests_answers_fillup', $telegram_id);
                break;
            case 5://end interests survey
                $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("We're all set, thanks!", $user['user']['language'])]);
                $data = $lcApi->makeRequest('set-user-interests-answers', ['telegram_id' => $update->getMessage()->chat->id, 'user_lang' => $user['user']['language']]);
                if($data['status'] === 'error') {
                    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
                    return false;
                }
                //if($data['status'] === 'success')
                    //$telegram->sendMessage(['chat_id' => $telegram_id, 'text' => json_encode($data['list_of_interests'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]);


                break;
            default:
                $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
                return false;
        }
    }
}

function expression_choose_expiration($update, $telegram, $callbackName)
{
    $telegram_id = $update->getMessage()->chat->id;
    $is_verified = user_is_verified($telegram_id );
    if(!$is_verified['status']) return false;

    $ids=explode('__',$callbackName);

    $lcApi = new \LCAPPAPI();
    $return_data = $lcApi->makeRequest('set-expiration-to-expression', ['telegram_id' => $telegram_id,'expiration' => intval($ids[1])]);

    if($return_data['status'] === 'error') {
        $options['chat_id'] = $telegram_id;
        $options['text'] = __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language']);
        $telegram->sendMessage($options);
        return false;
    }
    $telegram->triggerCommand('expression_choose_tags', $update);
    set_command_to_last_message('expression_choose_tags', $update->getMessage()->chat->id);

}

function update_json_profile($update, $telegram, $callbackName)
{
    $telegram_id = $update->getMessage()->chat->id;
    $is_verified = user_is_verified($telegram_id );
    if(!$is_verified['status']) return false;


    $comm=explode('__',$callbackName);
    switch ($comm[1])
    {
        case 'creative_name':
            $text = __("Enter your creative name", $is_verified['user']['language']);
            break;
        case 'city_village':
            $text = __("Enter city where you live", $is_verified['user']['language']);
            break;
        case 'creative_job':
            $text = __("Enter your creative job", $is_verified['user']['language']);
            break;
        case 'about_you':
            $text = __("Enter about you info", $is_verified['user']['language']);
            break;
        case 'facebook':
            $text = __("Enter your facebook", $is_verified['user']['language']);
            break;
        case 'youtube':
            $text = __("Enter your youtube", $is_verified['user']['language']);
            break;
        case 'tik_tok':
            $text = __("Enter your tiktok", $is_verified['user']['language']);
            break;
        case 'linkedin':
            $text = __("Enter your linkedin", $is_verified['user']['language']);
            break;
        case 'instagram':
            $text = __("Enter your instagram", $is_verified['user']['language']);
            break;
        case 'twitter':
            $text = __("Enter your twitter", $is_verified['user']['language']);
            break;
        case 'pinterest':
            $text = __("Enter your pinterest", $is_verified['user']['language']);
            break;
        case 'twitch':
            $text = __("Enter your twitch", $is_verified['user']['language']);
            break;
        case 'snapchat':
            $text = __("Enter your snapchat", $is_verified['user']['language']);
            break;
        default:
            return;


    }
    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => $text]);
    set_command_to_last_message('write_json_profile__'.$comm[1], $update->getMessage()->chat->id);
}
function write_json_profile($update, $telegram, $callbackName)
{
    $telegram_id = $update->getMessage()->chat->id;
    $is_verified = user_is_verified($telegram_id );
    if(!$is_verified['status']) return false;

    $comm=explode('__',$callbackName);

    $new_data = trim($update->getMessage()->text);

    $lcApi = new \LCAPPAPI();
    $result = $lcApi->makeRequest('set-json-profile-data', ['telegram_id' => $telegram_id, 'field' => $comm[1], 'data' => $new_data]);
    if($result['status'] === 'error') {
        $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Sorry, there was an error, please contact the administrator.", $is_verified['user']['language'])]);
        return false;
    }
    $telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Data is written successfully", $is_verified['user']['language'])]);
    $telegram->triggerCommand('my_data', $update);
}

function upload_avatar($update, $telegram, $callbackName)
{
    $telegram_id = $update->getMessage()->chat->id;
    $lcApi = new \LCAPPAPI();
    $is_verified = user_is_verified($telegram_id);
    if(!$is_verified['status']) return false;
    $message = $update->getMessage();
    if(!empty($message['document']) || !empty($message['photo'])) {

        if(!empty($message['document'])) {
            $file_id = $message['document']['file_id'];
        } else if (!empty($message['photo'])) {
            $file_id = end($message['photo'])['file_id'];
        }

        if(!empty($file_id)) {

            $result = $lcApi->makeRequest('upload-avatar', ['telegram_id' => $telegram_id, 'file_id' => $file_id]);

            if($result['status'] === 'success') {
                $telegram->triggerCommand('my_data', $update);
                return false;
            }
            //$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => json_encode($result)]);
            if($result['status'] === 'error' AND $result['text'] === 'unsupported_format') {
                $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('unsupported format, choose different file', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
                    'inline_keyboard' =>  [
                        [
                            Keyboard::inlineButton([
                                'text' => __('Try again', $is_verified['user']['language']),
                                'callback_data' => 'upload_avatar'
                            ])
                        ]
                    ],
                    'resize_keyboard' => true,
                ])]);
                return false;
            }

        }
    }
    //$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => json_encode($message)]);

        $telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Please attach cool avatar', $is_verified['user']['language']), 'reply_markup' => Keyboard::make([
        'inline_keyboard' =>  [
            [
                Keyboard::inlineButton([
                    'text' => __('Try again', $is_verified['user']['language']),
                    'callback_data' => 'upload_avatar'
                ])
            ]
        ],
        'resize_keyboard' => true,
    ])]);
}