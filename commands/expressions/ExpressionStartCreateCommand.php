<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ExpressionStartCreateCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "expression_start_create";

    /**
     * @var string Command Description
     */
    protected $description = "Create New Expressions";

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

       if(!empty($result['expressions_create_command'])) {
           $this->telegram->triggerCommand($result['expressions_create_command'], $update);
           exit;
       }

        $lcApi = new \LCAPPAPI();
        $lcApi->makeRequest('start-creating-expressions', ['telegram_id' => $telegram_id]);
        $this->telegram->triggerCommand('expression_choose_type', $update);
    }
}