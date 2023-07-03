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
	protected $description = "My invitation codes";
	
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
		} else {
			if(empty($data['codes'])) {
				$options['text'] = __('You don\'t have any codes', $user['user']['language']);
			} else {
				foreach ($data['codes'] as $key => $code) {
					$options['text'] .= ($key + 1) . ".\n";
					$options['text'] .= __('Code', $user['user']['language']) . ': ' . $code['code'] . "\n";
					$options['text'] .= __("Status", $user['user']['language']) . ': ' .  (!empty($code['user']) ? __('Activated by - ', $user['user']['language']) . ' ' . '@' . $code['user']['publicAlias'] : __('Not activated', $user['user']['language'])) . "\n\n";
				}
			}

		}

		$this->telegram->sendMessage($options);
	}
}
