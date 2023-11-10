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
    protected $description = "My creative expressions";

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
            if(count($data['data']) == 0){
                $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __('you do not have ce', $result['user']['language'])]);
            } else {
                $text = '';
                $i = 1;
                foreach ($data['data'] as $exp) {
                    $exp_time = ($exp['active_period'] - time()) / 3600;
                    if ($exp_time > 0) {
                        $exp_text = sprintf(__('%d hours left', $result['user']['language']), round($exp_time));
                    } else {
                        $exp_text = __('Expired', $result['user']['language']);
                    }
                    $text .= $i.".\n";
                    $text .= __("Type:", $result['user']['language']) . ' ' . __($exp['type_enum'], $result['user']['language']) . "\n";
                    $text .= __("Description:", $result['user']['language']) . ' ' . $exp['description'] . "\n";
                    $text .= __("Tags:", $result['user']['language']) . ' ' . $exp['tags'] . "\n";
                    $text .= __("Content:", $result['user']['language']) . ' ' . $exp['content'] . "\n";
                    $text .= __("Expiration time:", $result['user']['language']) . ' ' . $exp_text . "\n\n";
                    $i++;
                }
                $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => $text]);
            }

        }


    }
}