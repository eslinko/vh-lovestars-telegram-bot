<?php

namespace Telegram\Bot\Commands;

class ViewCreativeExpressionsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "view_creative_expressions";

    /**
     * @var string Command Description
     */
    protected $description = "View creative expressions";

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
        $data = $lcApi->makeRequest('get-user-creative-expressions', ['telegram_id' => $telegram_id]);

        if($data['status'] === 'error')
            $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __($data['text'], $result['user']['language'])]);
        else
        {
            $text = '';
            foreach ($data['data'] as $exp){
                $text .= __("Type:", $result['user']['language']).' '.__($exp['type_enum'], $result['user']['language'])."\n";
                $text .= __("Description:", $result['user']['language']).' '.$exp['description']."\n";
                $text .= __("Tags:", $result['user']['language']).' '.$exp['tags']."\n";
                $text .= __("Content:", $result['user']['language']).' '.$exp['content']."\n\n";
            }
            $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => $text]);

        }
    }
}