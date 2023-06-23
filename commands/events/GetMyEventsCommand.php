<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class GetMyEventsCommand.
 */
class GetMyEventsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "get_my_events";

    /**
     * @var string Command Description
     */
    protected $description = "Get list of my events.";

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
        $return_data = $lcApi->makeRequest('get-my-events', ['telegram_id' => $telegram_id]);

        if(!empty($return_data['events'])) {
            $text = __("Your events:", $user['user']['language']) . "\n";
            foreach ($return_data['events'] as $key => $event) {
                $text .= ($key + 1) . ")\n";
                $text .= __("Url:", $user['user']['language']) . " " . $event['facebook_url'] . "\n";
                $text .= __("Status:", $user['user']['language']) . " " . ucfirst($event['status']) . "\n\n";
            }
        } else {
            $text = __("You have no events of your own...", $user['user']['language']);
        }

        $this->telegram->sendMessage([
            'chat_id' => $telegram_id,
            'text' => $text,
            'disable_web_page_preview' => true
        ]);
    }
}
