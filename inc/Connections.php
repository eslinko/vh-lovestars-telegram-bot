<?php

class Connections
{
    static public function showConnections($telegram_id, $telegram, $user, $update)
    {

        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('get-user-connections', ['telegram_id' => $telegram_id]);

        if($data['status'] === 'error' || empty($data)) {
            $text = __("Error! Try again later.", $user['user']['language']);

        } else {
            if(empty($data['connections'])) {
                $text = __('You do not have connections', $user['user']['language']);
            } else {
                $i=1;
                $text='';
                foreach ($data['connections'] as $item) {
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';
                    $text.=$i.'. '.$user_name_text.' '.__('created on', $user['user']['language']).' '.date('j/m/y',strtotime($item['created_on']))."\n";
                    $i++;
                }
            }

        }
        return $text;
    }
}