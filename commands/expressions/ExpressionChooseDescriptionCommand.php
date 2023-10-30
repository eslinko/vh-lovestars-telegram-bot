<?php

namespace Telegram\Bot\Commands;

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

        $options['text'] = __('give description of ce:', $result['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}