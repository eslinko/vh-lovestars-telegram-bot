<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ExpressionChooseDescriptionCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_choose_description";

    /**
     * @var string Command Description
     */
    protected $description = "Description for expression";

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

        $options['text'] = __('Please provide a description of your creative expression:', $result['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}