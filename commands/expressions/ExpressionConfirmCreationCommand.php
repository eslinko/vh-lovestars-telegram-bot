<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ExpressionConfirmCreationCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_confirm_creation";

    /**
     * @var string Command Description
     */
    protected $description = "Finished creation expression";

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
        $exp_time = ($result['expressions_in_proccess']['active_period'] - time())/3600;
        if($exp_time > 0){
            $exp_text = sprintf(__('%d hours left', $result['user']['language']),round($exp_time));
        } else{
            $exp_text = __('Expired', $result['user']['language']);
        }

        $options['text'] = __('Confirm the creation of a creative expression.', $result['user']['language']) . "\n";
        $options['text'] .= __("Type:", $result['user']['language']).' '.__($result['expressions_in_proccess']['type_enum'], $result['user']['language'])."\n";
        $options['text'] .= __("Description:", $result['user']['language']).' '.$result['expressions_in_proccess']['description']."\n";
        $options['text'] .= __("Tags:", $result['user']['language']).' '.$result['expressions_in_proccess']['tags']."\n";
        $options['text'] .= __("Expiration time:", $result['user']['language']).' '.$exp_text."\n";

        $options['text'] .= __("Content:", $result['user']['language']).' '.$result['expressions_in_proccess']['content']."\n";

        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' =>  [
                [
                    Keyboard::inlineButton([
                        'text' => __('Yes, create it', $result['user']['language']),
                        'callback_data' => 'expression_finished_creation'
                    ]),
                    Keyboard::inlineButton([
                        'text' => __('No, cancel', $result['user']['language']),
                        'callback_data' => 'expression_cancel_creation'
                    ])
                ],
            ],
            'resize_keyboard' => true,
        ]);

        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}