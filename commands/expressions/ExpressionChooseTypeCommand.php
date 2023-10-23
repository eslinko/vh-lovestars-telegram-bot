<?php
//DEPRECATED
namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ExpressionChooseTypeCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_choose_type";

    /**
     * @var string Command Description
     */
    protected $description = "Choose type for expression(deprecated)";

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

        //$lcApi = new \LCAPPAPI();
        //$return_data = $lcApi->makeRequest('get-creative-types', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        //$keyboards = [];
       // foreach ($return_data['creative_types'] as $type_id => $creative_type) {
            $keyboards[] = [
                Keyboard::Button(['text' => __("\xF0\x9F\x8E\x9E".'Video', $result['user']['language'])]),
                Keyboard::Button(['text' => __("\xF0\x9F\x8E\xB6".'Audio', $result['user']['language'])]),
                Keyboard::Button(['text' => __("\xF0\x9F\x93\xB7".'Image', $result['user']['language'])]),
                Keyboard::Button(['text' => __("\xF0\x9F\x96\xB9".'Text', $result['user']['language'])]),
                Keyboard::Button(['text' => __("\xF0\x9F\x94\x97".'URL', $result['user']['language'])])
            ];
       // }

        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  $keyboards,
            'one_time_keyboard' => true
        ]);

        $options['text'] = __('Please select the type of your creative expression:', $result['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);


    }
}