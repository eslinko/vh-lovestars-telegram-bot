<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class LoveDoCreateCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "lovedo_create";

    /**
     * @var string Command Description
     */
    protected $description = "Create LoveDO post";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();

        $telegram_id = $update->getMessage()->chat->id;

        $user = user_is_verified($telegram_id);

        if(!$user['status']) {
            return false;
        }

        $options = ['chat_id' => $telegram_id];
        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('start-creating-expressions', ['telegram_id' => $telegram_id, 'love_do' => true]);
        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        }
        $data = $lcApi->makeRequest('get-user-connections', ['telegram_id' => $telegram_id]);

        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['connections'])) {
                $options['text'] = __('You do not have connections', $user['user']['language']);
            } else {
                $options['text'] = __('Choose user from your connections who gave you FREE service', $user['user']['language']);
                $i=1;
                $inline_keyboard = [];
                foreach ($data['connections'] as $item) {
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';

                    $text=$i.'. '.$user_name_text.' '.__('created on', $user['user']['language']).' '.date('j/m/y',strtotime($item['created_on']))."\n";
                    $inline_keyboard[]=[
                        Keyboard::inlineButton([
                            'text' => $text,
                            'callback_data' => 'love_do_for_user_id__'.$item['user_id']
                        ])
                    ];
                    $i++;
                }
                $options['reply_markup'] = Keyboard::make([
                    'inline_keyboard' =>  $inline_keyboard,
                    'resize_keyboard' => true
                ]);
            }
            $this->telegram->sendMessage($options);
        }


    }
}