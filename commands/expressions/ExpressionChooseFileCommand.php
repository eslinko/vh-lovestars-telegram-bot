<?php

namespace Telegram\Bot\Commands;

class ExpressionChooseFileCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_choose_file";

    /**
     * @var string Command Description
     */
    protected $description = "File for expression";

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

        $options['text'] = __('Please provide a file (image/video/audio) of your creative expression:', $result['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
    }
}