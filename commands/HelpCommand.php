<?php

namespace Telegram\Bot\Commands;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{
	private $excludeCommands = ['registration_step_1', 'registration_step_2', 'registration_step_3', 'registration_step_4', 'succesfully_registrated', 'teacher_create_step_2', 'teacher_create_step_3', 'teacher_create_step_4', 'teacher_update_title', 'teacher_update_public_alias', 'teacher_update_description', 'teacher_update_hashtags', 'update_my_email', 'update_my_email_confirm_code', 'update_my_public_alias', 'update_my_password', 'suggest_new_language', 'registration_step_invitation_code', 'set_user_interests', 'clear_all_interests_question', 'clear_all_interests','add_new_connection','delete_connections', 'sent_invites','rejected_invites', 'expression_choose_type', 'expression_choose_description'];
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'Get a list of commands';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $commands = $this->telegram->getCommands();
		$update = $this->getUpdate();
		$telegram_id = $update->getMessage()->chat->id;
	
		$is_verified = user_is_verified($telegram_id);
		
		$cur_text = trim($update->getMessage()->text);
		$cur_command = str_replace('/', '', $cur_text);
		
		if($cur_command !== 'help' && strtolower($cur_command) !== 'help' && !$update->isType('callback_query')) {
			if(isset($commands[strtolower($cur_command)])) {
				$this->telegram->triggerCommand(strtolower($cur_command), $update);
				exit;
			} else if(!isset($commands[$cur_command]) && find_count_of_aplha_in_string($cur_text, '/') < 2) {
				$this->telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => __('Warning: This command is not supported', $is_verified['user']['language'])]);
			}
		}

        $text = '';
        foreach ($commands as $name => $handler) {
            /* @var Command $handler */
			if(in_array($name, $this->excludeCommands)) continue;
			
			if($is_verified['status'] || (!$is_verified['status'] && ($name === 'help' || $name === 'start'))) {
				$text .= sprintf('/%s - %s'.PHP_EOL, $name, __($handler->getDescription(), $is_verified['user']['language']));
			} else continue;
		}
		
        $this->replyWithMessage(compact('text'));
    }
}
