<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class ExploreCeCommand.
 */
class ExploreCeCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "explore_ce";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Explore CE (tinder)";
	
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

        $options['text'] = __('Press button to explore Creative Expressions', $user['language']);
        $url = parse_url(getenv('API_URL'));
		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __("\xF0\x9F\x91\x80".'Explore CE (tinder)', $user['language']),
						'web_app' => ['url' => $url['scheme']."://".$url['host'].'/frontend/web/swipe/swipe.htm']
					])
				]
			],
			'resize_keyboard' => true
		]);
		$this->telegram->sendMessage($options);
	}
}
