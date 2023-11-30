<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;

/**
 * Class GetMyDataCommand.
 */
class GetMyDataCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_data";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "My data";
	
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		$update = $this->getUpdate();
		
		$telegram_id = $update->getMessage()->chat->id;
		
		$user = user_is_verified($telegram_id);
		
		if(!$user['status']) {
			return false;
		}
		
		$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = __("My public alias:", $user['user']['language']) . " " . $user['user']['publicAlias'] . "\n";
//		$options['text'] .= __("My e-mail:", $user['user']['language']) . " " . $user['user']['email'];
		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
//				[
//					Keyboard::inlineButton([
//						'text' => __('Update Email', $user['user']['language']),
//						'callback_data' => 'update_my_email'
//					])
//				],
				[
					Keyboard::inlineButton([
						'text' => __('Update Public Alias', $user['user']['language']),
						'callback_data' => 'update_my_public_alias'
					])
				],
/*				[
					Keyboard::inlineButton([
						'text' => __('Update Password', $user['user']['language']),
						'callback_data' => 'update_my_password'
					])
				],*/
/*                [
                    Keyboard::inlineButton([
                        'text' => __('Change the language', $user['user']['language']),
                        'callback_data' => 'change_language'
                    ])
                ]*/
			],
			'resize_keyboard' => true,
		]);
		
		$this->telegram->sendMessage($options);

        //new profile data
        $profile_data = json_decode($user['user']['profile_data'] ?? '{}', true);

        if(isset($profile_data['avatar'])){
            $photo =  InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/backend/web/uploads/avatars/'.$profile_data['avatar'],$profile_data['avatar']);

        } else {
            $photo =  InputFile::create(parse_url(getenv('API_URL'))['scheme'].'://'.parse_url(getenv('API_URL'))['host'].'/backend/web/assets/images/avatar-default.png','avatar-default.png');
        }
        $photo_options = [
                'chat_id' => $telegram_id,
            ];
        $photo_options['photo'] = $photo;
        $photo_options['caption'] = __("Your avatar", $user['user']['language']);
        $photo_options['reply_markup'] = Keyboard::make([
        'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => __('Update avatar', $user['user']['language']),
                            'callback_data' => 'upload_avatar'
                        ])
                    ],
                ],
                'resize_keyboard' => true,
            ]);
        $this->telegram->sendPhoto($photo_options);

        //button list
        $reply_markup = Keyboard::make([
            'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Your creative name:', $user['user']['language']).($profile_data['creative_name'] ?? __('NOT SET', $user['user']['language'])) ,
						'callback_data' => 'update_json_profile__creative_name'
					])
				],
                [
                    Keyboard::inlineButton([
                        'text' => __('City where you live:', $user['user']['language']).($profile_data['city_village'] ?? __('NOT SET', $user['user']['language'])) ,
                        'callback_data' => 'update_json_profile__city_village'
                    ])
                ],
                [
                    Keyboard::inlineButton([
                        'text' => __('Your creative job:', $user['user']['language']).($profile_data['creative_job'] ?? __('NOT SET', $user['user']['language'])) ,
                        'callback_data' => 'update_json_profile__creative_job'
                    ])
                ],
                [
                    Keyboard::inlineButton([
                        'text' => __('About you:', $user['user']['language']).($profile_data['about_you'] ?? __('NOT SET', $user['user']['language'])) ,
                        'callback_data' => 'update_json_profile__about_you'
                    ])
                ],
            ],
            'resize_keyboard' => true,
        ]);
        $options['reply_markup'] = $reply_markup;
        $options['text'] = __('Public profile:', $user['user']['language']);
        $this->telegram->sendMessage($options);

        $reply_markup = Keyboard::make([
            'inline_keyboard' =>[
                [
                Keyboard::inlineButton([
                    'text' => __('Facebook:', $user['user']['language']).($profile_data['facebook'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__facebook'
                ])
                ],
            [
                Keyboard::inlineButton([
                    'text' => __('Youtube:', $user['user']['language']).($profile_data['youtube'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__youtube'
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => __('Tik tok:', $user['user']['language']).($profile_data['tik_tok'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__tik_tok'
                ])
            ],
                [
                    Keyboard::inlineButton([
                        'text' => __('Discord:', $user['user']['language']).($profile_data['discord'] ?? __('NOT SET', $user['user']['language'])) ,
                        'callback_data' => 'update_json_profile__discord'
                    ])
                ],
            [
                Keyboard::inlineButton([
                    'text' => __('Linkedin:', $user['user']['language']).($profile_data['linkedin'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__linkedin'
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => __('Instagram:', $user['user']['language']).($profile_data['instagram'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__instagram'
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => __('Twitter:', $user['user']['language']).($profile_data['twitter'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__twitter'
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => __('Pinterest:', $user['user']['language']).($profile_data['pinterest'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__pinterest'
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => __('Twitch:', $user['user']['language']).($profile_data['twitch'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__twitch'
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => __('Snapchat:', $user['user']['language']).($profile_data['snapchat'] ?? __('NOT SET', $user['user']['language'])) ,
                    'callback_data' => 'update_json_profile__snapchat'
                ])
            ],
        ],
            'resize_keyboard' => true,
        ]);
        $options['reply_markup'] = $reply_markup;
        $options['text'] = __('Social media platforms:', $user['user']['language']);
        $this->telegram->sendMessage($options);
	}
}
