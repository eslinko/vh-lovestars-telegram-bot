<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class NotificationSettingsCommand.
 */
class NotificationSettingsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "notification_settings";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Notification settings";
	
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

        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('get-user-notification-settings', ['telegram_id' => $telegram_id]);

        if($data['status'] === 'error')
            $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Sorry, there was an error, please contact the administrator.", $user['user']['language'])]);
        if($data['status'] === 'success') {
            $button_command = [];
            if($data['notify_connections'] == 1) {
                $button_command['notify_connections'] = 'notification_settings__connections__0';
                $button_command['notify_connections_title'] = __('Btn_notify_connections_is_on', $user['user']['language']);
            }
            else {
                $button_command['notify_connections'] = 'notification_settings__connections__1';
                $button_command['notify_connections_title'] = __('Btn_notify_connections_is_off', $user['user']['language']);
            }
            if($data['notify_matches'] == 1) {
                $button_command['notify_matches'] = 'notification_settings__matches__0';
                $button_command['notify_matches_title'] = __('Btn_notify_matches_is_on', $user['user']['language']);
            }
            else {
                $button_command['notify_matches'] = 'notification_settings__matches__1';
                $button_command['notify_matches_title'] = __('Btn_notify_matches_is_off', $user['user']['language']);
            }
            if($data['notify_invite_codes'] == 1) {
                $button_command['notify_invite_codes'] = 'notification_settings__invite_codes__0';
                $button_command['notify_invite_codes_title'] = __('Btn_notify_invite_codes_is_on', $user['user']['language']);
            }
            else {
                $button_command['notify_invite_codes'] = 'notification_settings__invite_codes__1';
                $button_command['notify_invite_codes_title'] = __('Btn_notify_invite_codes_is_off', $user['user']['language']);
            }
            if($data['notify_ce_activity'] == 1) {
                $button_command['notify_ce_activity'] = 'notification_settings__ce_activity__0';
                $button_command['notify_ce_activity_title'] = __('Btn_notify_ce_activity_is_on', $user['user']['language']);
            }
            else {
                $button_command['notify_ce_activity'] = 'notification_settings__ce_activity__1';
                $button_command['notify_ce_activity_title'] = __('Btn_notify_ce_activity_is_off', $user['user']['language']);
            }

            $options = [
                'chat_id' => $telegram_id,
            ];
            $options['text'] = __('Press button to turn off or turn on notification', $user['user']['language']);
            $options['reply_markup'] = Keyboard::make([
                'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => $button_command['notify_connections_title'],
                            'callback_data' => $button_command['notify_connections']
                        ])
                    ],[
                        Keyboard::inlineButton([
                            'text' => $button_command['notify_matches_title'],
                            'callback_data' => $button_command['notify_matches']
                        ])
                    ],[
                        Keyboard::inlineButton([
                            'text' => $button_command['notify_invite_codes_title'],
                            'callback_data' => $button_command['notify_invite_codes']
                        ])
                    ],[
                        Keyboard::inlineButton([
                            'text' => $button_command['notify_ce_activity_title'],
                            'callback_data' => $button_command['notify_ce_activity']
                        ])
                    ]
                ],
                'resize_keyboard' => true
            ]);
        }
        $this->telegram->sendMessage($options);
	}
}
