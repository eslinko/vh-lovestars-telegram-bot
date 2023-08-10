<?php
use Telegram\Bot\Keyboard\Keyboard;
class TGKeyboard
{
    static public function processKeyboard($update, $telegram)
    {
        if(!isset($update->getMessage()['text']))return;
        $telegram_id = $update->getMessage()->chat->id;
        $user = user_is_verified($telegram_id);
        if(!$user['status']) {//unknown user, show start keyboard
            TGKeyboard::showStartKeyboard();
            return;
        }

        $text=$update->getMessage()['text'];
        switch ($text) {
            case "\xF0\x9F\x94\x97".__('My connections', $user['user']['language']):
                $text=Connections::showConnections($telegram_id, $telegram, $user, $update);
                TGKeyboard::showConnectionsKeyboard($telegram_id, $telegram, $user, $update, $text);
                break;
            case "\xE2\x9E\x95".__('Add connections', $user['user']['language']):
                $telegram->triggerCommand('add_new_connection', $update);
                break;
            case "\xE2\x9E\x96".__('Delete connection', $user['user']['language']):
                $telegram->triggerCommand('delete_connections', $update);
                break;
            case  "\xF0\x9F\x91\x8B".__('Sent invites', $user['user']['language']):
                $telegram->triggerCommand('sent_invites', $update);
                break;
            case "\xE2\x9D\x8C".__('Rejected invites', $user['user']['language']):
                $telegram->triggerCommand('rejected_invites', $update);
                break;
            case "\xF0\x9F\x8E\xAB".__('My invitation codes', $user['user']['language']):
                $telegram->triggerCommand('my_invitation_codes', $update);
                break;
            case "\xF0\x9F\x93\x9C".__('My data', $user['user']['language']):
                TGKeyboard::showMyDataKeyboard($telegram_id, $telegram, $user, $update, __('My data', $user['user']['language']));
                $telegram->triggerCommand('my_data', $update);
                break;
            case "\xF0\x9F\x93\xA7".__('Update Email', $user['user']['language']):
                $telegram->triggerCommand('update_my_email', $update);
                break;
            case "\xE2\x9C\x8F".__('Update Public Alias', $user['user']['language']):
                $telegram->triggerCommand('update_my_public_alias', $update);
                break;
            case "\xF0\x9F\x94\x92".__('Update Password', $user['user']['language']):
                $telegram->triggerCommand('update_my_password', $update);
                break;
            case "\xF0\x9F\x91\x84".__('Change the language', $user['user']['language']):
                $telegram->triggerCommand('change_language', $update);
                break;
            case "\xF0\x9F\x94\xA5".__('My interests and values', $user['user']['language']):
                $telegram->triggerCommand('my_interests_and_values', $update);
                break;
            case "\xF0\x9F\x8F\xA0".'Home':
                TGKeyboard::showMainKeyboard($telegram_id, $telegram, $user, 'Home');
                break;
            default:
                //TGKeyboard::showMainKeyboard($telegram_id, $telegram, $user);
                break;
        }
    }
    static public function showStartKeyboard(){

    }
    static public function showMainKeyboard($telegram_id, $telegram, $user, $text){
        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  [
                [
                    Keyboard::button(['text' => "\xF0\x9F\x93\x9C".__('My data', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x8E\xAB".__('My invitation codes', $user['user']['language'])]),
                ],[
                    Keyboard::button(['text' => "\xF0\x9F\x94\xA5".__('My interests and values', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x94\x97".__('My connections', $user['user']['language'])]),
                ]
            ],
            'resize_keyboard' => true
        ]);

        $options['text'] = $text;
        $telegram->sendMessage($options);
    }
    static public function showConnectionsKeyboard($telegram_id, $telegram, $user, $update, $text){

        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  [
                [
                    Keyboard::button(['text' => "\xF0\x9F\x8F\xA0".__('Home', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x91\x8B".__('Sent invites', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xE2\x9D\x8C".__('Rejected invites', $user['user']['language'])]),
                ],[
                    Keyboard::button(['text' => "\xE2\x9E\x95".__('Add connections', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xE2\x9E\x96".__('Delete connection', $user['user']['language'])]),
                ]
            ],
            'resize_keyboard' => true
        ]);
        //$options['reply_markup'] = Keyboard::make([ 'remove_keyboard' =>  true  ]);
        $options['text'] = $text;

        $telegram->sendMessage($options);
        //$telegram->triggerCommand('my_connections', $update);
    }
    static public function showMyDataKeyboard($telegram_id, $telegram, $user, $update, $text){
        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  [
                [
                    Keyboard::button(['text' => "\xF0\x9F\x8F\xA0".__('Home', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x93\xA7".__('Update Email', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xE2\x9C\x8F".__('Update Public Alias', $user['user']['language'])]),
                ],[
                    Keyboard::button(['text' => "\xF0\x9F\x94\x92".__('Update Password', $user['user']['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x91\x84".__('Change the language', $user['user']['language'])]),
                ]
            ],
            'resize_keyboard' => true
        ]);

        $options['text'] = $text;

        $telegram->sendMessage($options);

    }

}