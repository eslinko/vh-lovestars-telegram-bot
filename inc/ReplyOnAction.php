<?php
use Telegram\Bot\Keyboard\Keyboard;

function reply_on_action_switcher($callback_data, $update, $telegram, $last_message_object) {
	switch ($callback_data) {
		case 'registration_step_1':
			after_registration_step_1($update, $telegram);
			break;
		case 'registration_step_2':
			after_registration_step_2($update, $telegram);
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
	
	$telegram->triggerCommand('registration_step_3', $update);
	
	set_command_to_last_message('registration_step_3', $update->getMessage()->chat->id);
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
	}
}

function suggest_new_language($update, $telegram) {
	$language_to_suggest = trim($update->getMessage()->text);
	
	$is_verified = user_is_verified($update->getMessage()->chat->id);
	
	$lcApi = new \LCAPPAPI();
	$result = $lcApi->makeRequest('send-notification-to-admin', ['telegram_id' => $update->getMessage()->chat->id, 'message' =>  __("Alarm! A user {userPublicAlias} suggested adding a new language:", $is_verified['user']['language']) . ' ' . $language_to_suggest]);
	
	$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Thank you! Our administrators will consider your application :)', $result['user']['language'])]);
}