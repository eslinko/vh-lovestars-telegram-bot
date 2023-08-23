<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyConnectionsCommand.
 */
class MyConnectionsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_connections";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "My connections";
	
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


        //show commands
        $options['text'] = '/sent_invites - '.__("Your sent invites.", $user['user']['language'])."\n";
        $options['text'] .= '/rejected_invites - '.__("Rejected invites.", $user['user']['language']);
        $this->telegram->sendMessage($options);
        $options['text'] = '';



        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('get-user-connections', ['telegram_id' => $telegram_id]);



        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['connections'])) {
                $options['text'] = __('You do not have connections', $user['user']['language']);
            } else {
                $i=1;
                foreach ($data['connections'] as $item) {
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';
                    $options['text'].=$i.'. '.$user_name_text.' created on '.date('j/m/y',strtotime($item['created_on']))."\n";
                    $i++;
                }
            }

        }


		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Add new', $user['user']['language']),
						'callback_data' => 'add_new_connection'
					]),
                    Keyboard::inlineButton([
                        'text' => __('Delete', $user['user']['language']),
                        'callback_data' => 'delete_connections'
                    ])
				]


			],
			'resize_keyboard' => true
		]);
		
		$this->telegram->sendMessage($options);
	}
}
