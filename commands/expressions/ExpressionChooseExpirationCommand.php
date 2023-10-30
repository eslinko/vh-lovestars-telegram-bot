<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ExpressionChooseExpirationCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_choose_expiration";

    /**
     * @var string Command Description
     */
    protected $description = "Expiration for expression";

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

        $options = [
            'chat_id' => $telegram_id,
        ];

        $options['text'] = __('select ce expiration:', $result['user']['language']);
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('24 hours', $result['user']['language']),
                        'callback_data' => 'expression_choose_expiration__24'
                    ]),
                    Keyboard::inlineButton([
                        'text' => __('48 hours', $result['user']['language']),
                        'callback_data' => 'expression_choose_expiration__48'
                    ]),
                    Keyboard::inlineButton([
                        'text' => __('72 hours', $result['user']['language']),
                        'callback_data' => 'expression_choose_expiration__72'
                    ])
                ]
            ],
            'resize_keyboard' => true
        ]);
        $this->telegram->sendMessage($options);

    }
}