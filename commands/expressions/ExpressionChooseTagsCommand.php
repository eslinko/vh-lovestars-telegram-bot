<?php

namespace Telegram\Bot\Commands;

class ExpressionChooseTagsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_choose_tags";

    /**
     * @var string Command Description
     */
    protected $description = "Tags for expression";

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

        $options['text'] = __('Please enter tags (separated by commas):', $result['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}