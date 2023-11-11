<?php

namespace Telegram\Bot\Commands;

class UploadAvatarCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "upload_avatar";

    /**
     * @var string Command Description
     */
    protected $description = "Upload avatar";

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

        $options['text'] = __('Please attach cool avatar', $result['user']['language']);
        $this->telegram->sendMessage($options);


        set_command_to_last_message($this->name, $telegram_id);
    }
}