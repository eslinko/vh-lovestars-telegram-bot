<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class GetMyInvitationCodesCommand.
 */
class GetMyInvitationCodesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_invitation_codes";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Description_My invitation codes";
	
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
		$data = $lcApi->makeRequest('get-my-invitation-codes', ['telegram_id' => $telegram_id]);

		$options = [
			'chat_id' => $telegram_id,
		];

		if($data['status'] === 'error' || empty($data)) {
			$options['text'] = __("Error! Try again later.", $user['user']['language']);
			$this->telegram->sendMessage($options);
		} else {
			if(empty($data['codes'])) {
				$options['text'] = __('You have no invitation codes available', $user['user']['language']);
				$this->telegram->sendMessage($options);
			} else {
				//foreach ($data['codes'] as $key => $code) {
                for ($i = 0; $i < count($data['codes']); $i++){
                    if($i==count($data['codes']-2)break;
                    $code = $data['codes'][$i];
                    if(empty($code['user'])){
                        $options['text'] = $code['code'];
                        if($user['user']['id']==18) $options['text'] = $i.' '.time().' '.$options['text'];
                    } else {
                        if(empty($code['user']['telegram_alias']))
                            $user_name_text = $code['user']['publicAlias'];
                        else
                            $user_name_text=$code['user']['publicAlias'].' (@'.$code['user']['telegram_alias'].')';
                        $options['text'] = $code['code'] . ', ' . __('Used by', $user['user']['language']) .' '.$user_name_text. ' ' . __('on', $user['user']['language']) . ' ' . date('m/d/Y', $code['signup_date']);
                    }
                    $this->telegram->sendMessage($options);
				}
				$options['text'] = __("You can forward any code which is not used to any of your telegram contacts along with the message below", $user['user']['language']);
				$this->telegram->sendMessage($options);

				$options['text'] = "\xF0\x9F\x94\xA5 " . __("This is an invitation to Zeya", $user['user']['language']);
				$this->telegram->sendMessage($options);
			}

		}
	}
}
