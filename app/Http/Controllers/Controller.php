<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Card;
use App\Models\Message;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function Ramsey\Uuid\Codec\encode;

class Controller extends BaseController
{

    public function message_get(Request $request)
    {
        $from = $request->mobile;
        $to = $request->line;
        $body = $request->message;

        try {

            if (Message::query()->where('from', $from)->exists()) {

                $msg = Message::query()->where('from', $from)->first();
                $card = Card::query()->findOrFail($msg->card_id);
                $url = "https://api.trello.com/1/cards/{$card->card_id}/actions/comments";

                $post = [
                    'key' => 'c9a495974b79c0eb69bbee81f852a3c7',
                    'token' => 'ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA',
                    'text' => $body
                ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);


                $msg = new Message;
                $msg->card_id = $card->id;
                $msg->from = $from;
                $msg->to = $to;
                $msg->body = $body;
                $msg->type = 'comment';
                $msg->save();


            }else {

                $post = [
                    'idList' => '6425acdcb7ce75b3b0be480d',
                    'key' => 'c9a495974b79c0eb69bbee81f852a3c7',
                    'token' => 'ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA',
                    'name' => $from,
                    'desc' => $body
                ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.trello.com/1/cards",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                $result = json_decode($response, true);

                $card = new Card;
                $card->board_id = $result['idBoard'];
                $card->card_id = $result['id'];
                $card->save();

                $msg = new Message;
                $msg->card_id = $card->id;
                $msg->from = $from;
                $msg->to = $to;
                $msg->body = $body;
                $msg->type = 'card';
                $msg->save();
            }

        } catch (\Exception $exception) {
            $file = 'message_log.log';
            file_put_contents($file, $exception->getMessage() . "\n", FILE_APPEND | LOCK_EX);
        }
    }

    public function actions_get()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.trello.com/1/lists/6425acdcb7ce75b3b0be480d/actions?key=c9a495974b79c0eb69bbee81f852a3c7&token=ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: dsc=a7ab935589758b772ab9ebf772bebd820af1e5ee20906c370d14b5346ec4492c; preAuthProps=s%3A6425aab9fb1bce7005bf4bc0%3AisEnterpriseAdmin%3Dfalse.oHg%2BjtaCT47QwXei8UCyaq8GacsmfClQ9QsyiJW4LDI'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $results = json_decode($response, true);

        foreach ($results as $result) {
            if ($result['type'] == 'commentCard') {

                $text = explode('@', $result['data']['text']);
                $body = $text[0];
                $name = end($text);
                $body = str_replace("\n", "", $body);
                $body = str_replace("\\", "", $body);
                $body = str_replace(PHP_EOL,"", $body);

                if ($name == $result['data']['card']['name'] && ! Action::query()->where('action_id', $result['id'])->exists()) {
                    $card = Card::query()->where('card_id', $result['data']['card']['id'])->first();
                    $card->name = $name;

                    $action = new Action;
                    $action->card_id = $card->id;
                    $action->action_id = $result['id'];
                    $action->type = $result['type'];
                    $action->text = $body;
                    $action->save();
                }
            }
        }

    }

    public function send_message()
    {
        $actions = Action::query()->whereNull('sent_at')->get();
        foreach ($actions as $action) {
            $to = Message::query()->where('card_id', $action->card_id)->pluck('from')->first();
            $url = "http://sms.parsgreen.ir/UrlService/sendSMS.ashx";
            $post = [
                'from' => '10001818181919',
                'to' => $to,
                'text' => $action->text,
                'signature' => 'B8DD5858-EC2D-4EE2-98F0-B07435184A9E'
            ];
            $text = $action->text;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://sms.parsgreen.ir/UrlService/sendSMS.ashx?from=10001818181919&to={$to}&text={$text}&signature=B8DD5858-EC2D-4EE2-98F0-B07435184A9E",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            dd($response);
        }
    }

}
