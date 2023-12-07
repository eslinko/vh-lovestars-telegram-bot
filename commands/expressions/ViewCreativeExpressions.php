<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

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

                $i = 1;
                foreach ($data['data'] as $exp) {
                    $text = '';
                    $exp_time = ($exp['active_period'] - time()) / 3600;
                    $show_buttons = false;
                    if ($exp_time > 0) {
                        $exp_text = sprintf(__('%d hours left', $result['user']['language']), round($exp_time));
                    } else {
                        $exp_text = __('Expired', $result['user']['language']);
                        $show_buttons = true;
                        $reply_markup = Keyboard::make([
                            'inline_keyboard' =>  [
                                [
                                    Keyboard::inlineButton([
                                        'text' => __('24 hours', $result['user']['language']),
                                        'callback_data' => 'expression_update_expiration__'.$exp['id'].'__24'
                                    ]),
                                    Keyboard::inlineButton([
                                        'text' => __('48 hours', $result['user']['language']),
                                        'callback_data' => 'expression_choose_expiration__'.$exp['id'].'__48'
                                    ]),
                                    Keyboard::inlineButton([
                                        'text' => __('72 hours', $result['user']['language']),
                                        'callback_data' => 'expression_choose_expiration__'.$exp['id'].'__72'
                                    ])
                                ]
                            ],
                            'resize_keyboard' => true
                        ]);
                    }
                    $text .= $i.".\n";
                    $text .= __("Type:", $result['user']['language']) . ' ' . __($exp['type_enum'], $result['user']['language']) . "\n";
                    $text .= __("Description:", $result['user']['language']) . ' ' . $exp['description'] . "\n";
                    $text .= __("Tags:", $result['user']['language']) . ' ' . $exp['tags'] . "\n";
                    $text .= __("Content:", $result['user']['language']) . ' ' . $exp['content'] . "\n";
                    $text .= __("Expiration time:", $result['user']['language']) . ' ' . $exp_text . "\n\n";
                    $i++;
                    if($show_buttons == false)
                        $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => $text]);
                    else
                        $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => $text, 'reply_markup' => $reply_markup]);
                }

            }

        }


    }
}