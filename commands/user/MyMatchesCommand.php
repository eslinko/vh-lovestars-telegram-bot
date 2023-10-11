<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyMatchesCommand.
 */
class MyMatchesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_matches";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "My matches";
	
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

        $options['text'] = '';



        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('get-user-matches', ['telegram_id' => $telegram_id]);



        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['matches'])) {
                $options['text'] = __('You do not have matches', $user['user']['language']);
            } else {
                $i=1;
                foreach ($data['matches'] as $item) {
                    $user_name_text = $item['user']['public_alias'];
                    if(!empty($item['user']['telegram_alias']))$user_name_text = '(@'.$item['user']['telegram_alias'].') '.$user_name_text;
                    $options['text'].=$i.'. '.$user_name_text.' '.__('created on', $user['user']['language']).' '.date('j/m/y',strtotime($item['timestamp']))."\n";
                    $i++;
                }
            }

        }


		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Explore CE (tinder)', $user['user']['language']),
						'web_app' => ['url' => 'https://staging-server.zeya888.com/frontend/web/swipe/swipe.htm']//'https://api.siberianlegend.ru/swipe/swipe.htm']
					])
				]


			],
			'resize_keyboard' => true
		]);
		
		$this->telegram->sendMessage($options);
	}
}
