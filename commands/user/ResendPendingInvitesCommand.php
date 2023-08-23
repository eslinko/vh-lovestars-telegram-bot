<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class SentInvitesCommand.
 */
class ResendPendingInvitesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "resend_pending_invites";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Resend pending invites";
	
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
        $data = $lcApi->makeRequest('get-user-sent-pending-invites', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['connections'])) {
                $options['text'] = __('You do not have pending invites', $user['user']['language']);
            } else {
                $options['text'] = __("You can only use the 'Re-send' action once, please select the person you want to re-send the connection:", $user['user']['language']);
                $i=1;
                $users_buttons=[];
                foreach ($data['connections'] as $item) {
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';
                    $users_buttons[]=
                        [
                            Keyboard::inlineButton([
                                'text' => $i.'. '.$user_name_text.' '.__('updated at', $user['user']['language']).' '.date('j/m/y',strtotime($item['updated_at'])).' - '.__('pending', $user['user']['language']),
                                'callback_data' => 'resend_invitation__'.$item['user_id']
                            ])
                        ];
                    $i++;
                }
                $options['reply_markup'] = Keyboard::make([
                    'inline_keyboard' =>  $users_buttons,
                    'resize_keyboard' => true
                ]);

            }

        }

		$this->telegram->sendMessage($options);
	}
}
