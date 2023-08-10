<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class CreateExpressionsStep1Command extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "create_expressions";

    /**
     * @var string Command Description
     */
    protected $description = "Create New Expressions";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();

        $telegram_id = $update->getMessage()->chat->id;

        $result = user_is_verified($telegram_id);

        if(!$result['status']) {
            return false;
        }

        $lcApi = new \LCAPPAPI();
        $return_data = $lcApi->makeRequest('start-creating-expressions', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        $keyboards = [];
        foreach ($return_data['creative_types'] as $type_id => $creative_type) {
            $keyboards[] = [
                Keyboard::inlineButton([
                    'text' => $creative_type
                ])
            ];
        }

        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  $keyboards,
//            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $options['text'] = __('Please select the type of your creative expression:', $result['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}