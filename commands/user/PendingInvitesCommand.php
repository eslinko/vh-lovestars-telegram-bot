<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class SentInvitesCommand.
 */
class PendingInvitesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "pending_invites";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Pending invites";
	
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

/*        $options = [
            'chat_id' => $telegram_id,
        ];
        $options['text'] = __("E.", $user['user']['language']);
        $this->telegram->sendMessage($options);return;*/


        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('get-user-pending-invites', ['telegram_id' => $telegram_id]);

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
                $users_buttons=[];
                $i=1;
                foreach ($data['connections'] as $item) {
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';
                    $users_buttons[]=
                        [
                            Keyboard::inlineButton([
                                'text' => $i.'. '.sprintf(__('%s sent you a connection request on %s',$user['user']['language']),$user_name_text, date('j/m/y',strtotime($item['updated_at']))),
                                'callback_data' => 'accept_or_decline_pending_connection_by_id__'.$item['connection_id'].'__'.$item['user_id']
                            ])
                        ];
                    $i++;
                }
                $options['reply_markup'] = Keyboard::make([
                    'inline_keyboard' =>  $users_buttons,
                    'resize_keyboard' => true
                ]);
                $options['text'] = __('Hello there! To choose a user and select an option from a pending request, just tap on their name in the list below. Easy peasy!', $user['user']['language']);

            }
        }
		$this->telegram->sendMessage($options);
	}
}
