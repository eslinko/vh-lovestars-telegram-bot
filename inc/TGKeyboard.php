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
            //sendMessage causes duplicated useless messages at registration
            //$telegram->sendMessage(['chat_id' => $update->getMessage()->chat->id, 'text' => $user['text'], 'reply_markup' => $user['reply_markup']]);
            TGKeyboard::showStartKeyboard();
            return false;
        }
        $user = $user['user'];
        $text=$update->getMessage()['text'];
        switch ($text) {
            case "\xF0\x9F\x94\x97".__('My connections', $user['language']):
                $text_resp=Connections::showConnections($telegram_id, $telegram, $user, $update);
                TGKeyboard::showConnectionsKeyboard($telegram_id, $telegram, $user, $text_resp);
                break;
            case "\xE2\x9E\x95".__('Add connections', $user['language']):
                $telegram->triggerCommand('add_new_connection', $update);
                break;
            case "\xE2\x9E\x96".__('Btn_remove_connection', $user['language']):
                $telegram->triggerCommand('delete_connections', $update);
                break;
            case  "\xF0\x9F\x91\x8B".__('Your sent invites.', $user['language']):
                $telegram->triggerCommand('sent_invites', $update);
                break;
            case "\xE2\x9D\x8C".__('Rejected invites', $user['language']):
                $telegram->triggerCommand('rejected_invites', $update);
                break;
            case "\xE2\x8F\xB3".__('Btn_Pending Invites', $user['language']):
                $telegram->triggerCommand('pending_invites', $update);
                break;
            case "\xF0\x9F\x8E\xAB".__('Btn_My invitation codes', $user['language']):
                $telegram->triggerCommand('my_invitation_codes', $update);
                break;
            case "\xF0\x9F\x93\x9C".__('My data', $user['language']):
                TGKeyboard::showMyDataKeyboard($telegram_id, $telegram, $user, __('My data', $user['language']));
                $telegram->triggerCommand('my_data', $update);
                break;
            case "\xF0\x9F\x93\xA7".__('Update Email', $user['language']):
                $telegram->triggerCommand('update_my_email', $update);
                break;
            case "\xE2\x9C\x8F".__('Update Public Alias', $user['language']):
                $telegram->triggerCommand('update_my_public_alias', $update);
                break;
            case "\xF0\x9F\x94\x92".__('Update Password', $user['language']):
                //$telegram->triggerCommand('update_my_password', $update);
                break;
            case "\xF0\x9F\x91\x84".__('Btn_Change the language', $user['language']):
                $telegram->triggerCommand('change_language', $update);
                break;
            case "\xF0\x9F\x94\xA5".__('Btn_My interests and values', $user['language']):
                $telegram->triggerCommand('my_interests_and_values', $update);
                break;
            case "\xF0\x9F\x92\x9B".__('My Lovestars', $user['language']):
                $telegram->triggerCommand('my_lovestars', $update);
                break;
            case "\xF0\x9F\x93\x9D".__('Add event url.', $user['language']):
                $telegram->triggerCommand('events_create', $update);
                break;
            case "\xF0\x9F\x93\x84".__('Get list of my events.', $user['language']):
                $telegram->triggerCommand('get_my_events', $update);
                break;
            case "\xF0\x9F\x8F\xA0".__('Home', $user['language']):
                TGKeyboard::showMainKeyboard($telegram_id, $telegram, $user, 'Home');
                break;
            case "\xF0\x9F\x98\x8D".__('Btn_My matches', $user['language']):
                $telegram->triggerCommand('my_matches', $update);
                break;
            default:
                //TGKeyboard::showMainKeyboard($telegram_id, $telegram, $user);
                return false;
                break;
        }
        return true;
    }
    static public function showStartKeyboard(){

    }

    static public function showMainKeyboard($telegram_id, $telegram, $user, $text){
        $options =[];
        $options['chat_id'] = $telegram_id;
        if(in_array($user['role'], ['event_organizer', 'admin'])){
            $options['reply_markup'] = Keyboard::make([
                'keyboard' =>  [
                    [
                        Keyboard::button(['text' => "\xF0\x9F\x93\x9C".__('My data', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x8E\xAB".__('Btn_My invitation codes', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x92\x9B".__('My Lovestars', $user['language'])]),

                    ],[
                        Keyboard::button(['text' => "\xF0\x9F\x94\xA5".__('Btn_My interests and values', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x94\x97".__('My connections', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x98\x8D".__('Btn_My matches', $user['language'])])
                    ],[
                        Keyboard::button(['text' => "\xF0\x9F\x93\x9D".__('Add event url.', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x93\x84".__('Get list of my events.', $user['language'])]),
                    ]
                ],
                'resize_keyboard' => true
            ]);
        } else {
            $options['reply_markup'] = Keyboard::make([
                'keyboard' =>  [
                    [
                        Keyboard::button(['text' => "\xF0\x9F\x93\x9C".__('My data', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x8E\xAB".__('Btn_My invitation codes', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x92\x9B".__('My Lovestars', $user['language'])]),

                    ],[
                        Keyboard::button(['text' => "\xF0\x9F\x94\xA5".__('Btn_My interests and values', $user['language'])]),
                        Keyboard::button(['text' => "\xF0\x9F\x94\x97".__('My connections', $user['language'])]),
                    ]
                ],
                'resize_keyboard' => true
            ]);
        }


        $options['text'] = $text;
        $telegram->sendMessage($options);
    }
    static public function showConnectionsKeyboard($telegram_id, $telegram, $user, $text){

        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  [
                [
                    Keyboard::button(['text' => "\xF0\x9F\x8F\xA0".__('Home', $user['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x91\x8B".__('Your sent invites.', $user['language'])]),
                    Keyboard::button(['text' => "\xE2\x9D\x8C".__('Rejected invites', $user['language'])]),
                ],[
                    Keyboard::button(['text' => "\xE2\x9E\x95".__('Add connections', $user['language'])]),
                    Keyboard::button(['text' => "\xE2\x9E\x96".__('Btn_remove_connection', $user['language'])]),
                    Keyboard::button(['text' => "\xE2\x8F\xB3".__('Btn_Pending Invites', $user['language'])]),

                ]
            ],
            'resize_keyboard' => true
        ]);
        //$options['reply_markup'] = Keyboard::make([ 'remove_keyboard' =>  true  ]);
        $options['text'] = $text;

        $telegram->sendMessage($options);
        //$telegram->triggerCommand('my_connections', $update);
    }
    static public function showMyDataKeyboard($telegram_id, $telegram, $user, $text){
        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::make([
            'keyboard' =>  [
                [
                    Keyboard::button(['text' => "\xF0\x9F\x8F\xA0".__('Home', $user['language'])]),
                    //Keyboard::button(['text' => "\xF0\x9F\x93\xA7".__('Update Email', $user['language'])]),
                    Keyboard::button(['text' => "\xE2\x9C\x8F".__('Update Public Alias', $user['language'])]),
                ],[
                    //Keyboard::button(['text' => "\xF0\x9F\x94\x92".__('Update Password', $user['language'])]),
                    Keyboard::button(['text' => "\xF0\x9F\x91\x84".__('Btn_Change the language', $user['language'])]),
                ]
            ],
            'resize_keyboard' => true
        ]);

        $options['text'] = $text;

        $telegram->sendMessage($options);

    }
    static public function hideKeyboard($telegram_id, $telegram, $text){
        $options =[];
        $options['chat_id'] = $telegram_id;
        $options['reply_markup'] = Keyboard::remove();
        $options['text'] = $text;
        $telegram->sendMessage($options);
    }

}