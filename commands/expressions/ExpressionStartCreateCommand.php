<?php

namespace Telegram\Bot\Commands;

class ExpressionStartCreateCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_start_create";

    /**
     * @var string Command Description
     */
    protected $description = "Add creative expression";

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

/*       if(!empty($result['expressions_create_command'])) {
           $this->telegram->triggerCommand($result['expressions_create_command'], $update);
           exit;
       }*/

        $lcApi = new \LCAPPAPI();
        $lcApi->makeRequest('start-creating-expressions', ['telegram_id' => $telegram_id]);
        //$this->telegram->triggerCommand('expression_choose_type', $update);
        \TGKeyboard::showCreativeExpressionsTypeKeyboard($telegram_id, $this->telegram, $result['user'], __('Please select the type of your creative expression:', $result['user']['language']));
    }
}