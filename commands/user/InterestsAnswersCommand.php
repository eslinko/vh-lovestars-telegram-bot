<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;

/**
 * Class InterestsAnswersCommand.
 */
class InterestsAnswersCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "interests_answers";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Description_interests_answers";
	
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
        //set message_counter to 0 to show question 0
        $data = $lcApi->makeRequest('set-message-counter', ['telegram_id' => $telegram_id, 'message_counter' => 0]);
        if($data['status'] === 'error') {
            $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
            return false;
        }
        $data = $lcApi->makeRequest('get-interests-answers', ['telegram_id' => $telegram_id]);

        if($data['status'] === 'error') {
            $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
            return false;
        }
        elseif($data['status'] === 'success'){
            $options = [];
            $options['reply_markup'] = Keyboard::make([
                'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => __("Let's do it!", $user['user']['language']),
                            'callback_data' => 'interests_answers_fillup_ignore_input'
                        ]),
/*                        Keyboard::inlineButton([
                            'text' => __('No', $user['user']['language']),
                            'callback_data' => 'help'
                        ])*/
                    ]
                ],
                'resize_keyboard' => true
            ]);
            $options ['chat_id'] = $telegram_id;
            if(count($data['data']) == 0){
                $options ['text'] = __("Hey there! We're super curious about what lights you up in life", $user['user']['language']);
            } elseif(count($data['data']) == 5) {
                $options ['text'] = __("It seems like you've been there, done that with these questions", $user['user']['language']);
            } else {//1,2,3,4
                $options ['text'] = __("Looks like you started the survey", $user['user']['language']);
            }
            $this->telegram->sendMessage($options);
        } else  {
            $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Error! Try again later.", $user['user']['language'])]);
            return false;
        }


	}
}
