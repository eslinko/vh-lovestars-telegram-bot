<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ExpressionCancelCreationCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_cancel_creation";

    /**
     * @var string Command Description
     */
    protected $description = "Cancel creation expression";

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
        $data = $lcApi->makeRequest('cancel-expression-creation', ['telegram_id' => $telegram_id]);


        $options = [
            'chat_id' => $telegram_id,
        ];

        if($data['status'] === 'error') {
            $options['text'] = __('An error has occurred.', $result['user']['language']);
        } else {
            $options['text'] = __('Success! Create a new creative expression?', $result['user']['language']);
            $options['reply_markup'] = Keyboard::make([
                'inline_keyboard' =>  [
                    [
                        Keyboard::inlineButton([
                            'text' => __('Yes', $result['user']['language']),
                            'callback_data' => 'expression_start_create'
                        ]),
                        Keyboard::inlineButton([
                            'text' => __('No, cancel', $result['user']['language']),
                            'callback_data' => 'help'
                        ]),
                    ],
                ],
                'resize_keyboard' => true,
            ]);
        }

        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}