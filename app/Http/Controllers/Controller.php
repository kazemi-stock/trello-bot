<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Card;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use nusoap_client;
use function Ramsey\Uuid\Codec\encode;

class Controller extends BaseController
{

    public function messages_get(Request $request)
    {
        $from = $request->mobile;
        $to = $request->line;
        $body = $request->message;

        try {

            if (Contact::query()->where('phone', $from)->exists()) {

                $contact = Contact::query()->where('phone', $from)->first();
                if (empty($contact->card_id)) {
                    $url = "https://api.trello.com/1/cards";
                    $fields = [
                        'idList' => '6425acdb9c089cc9dd66527e',
                        'key' => 'c9a495974b79c0eb69bbee81f852a3c7',
                        'token' => 'ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA',
                        'name' => $from,
                        'desc' => $body
                    ];

                    $result = $this->post_curl($url, $fields);

                    $card = new Card;
                    $card->t_board_id = $result['idBoard'];
                    $card->t_card_id = $result['id'];
                    $card->name = $from;
                    $card->desc = $body;
                    $card->save();

                    $contact->card_id = $card->id;
                    $contact->save();

                }else {

                    $card = Card::query()->findOrFail($contact->card_id);
                    $url = "https://api.trello.com/1/cards/{$card->t_card_id}/actions/comments";

                    $fields = [
                        'key' => 'c9a495974b79c0eb69bbee81f852a3c7',
                        'token' => 'ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA',
                        'text' => $body
                    ];

                    $result = $this->post_curl($url ,$fields);

                }

                $msg = new Message;
                $msg->contact_id = $contact->id;
                $msg->from = $from;
                $msg->to = $to;
                $msg->body = $body;
                $msg->type = 'comment';
                $msg->save();

            } else {

                $url = "https://api.trello.com/1/cards";
                $fields = [
                    'idList' => '6425acdb9c089cc9dd66527e',
                    'key' => 'c9a495974b79c0eb69bbee81f852a3c7',
                    'token' => 'ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA',
                    'name' => $from,
                    'desc' => $body
                ];

                $result = $this->post_curl($url, $fields);

                $card = new Card;
                $card->t_board_id = $result['idBoard'];
                $card->t_card_id = $result['id'];
                $card->name = $from;
                $card->desc = $body;
                $card->save();

                $contact = new Contact;
                $contact->card_id = $card->id;
                $contact->phone = $from;
                $contact->save();

                $msg = new Message;
                $msg->contact_id = $contact->id;
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
        $url = 'https://api.trello.com/1/boards/6425acdb9c089cc9dd665277/actions?key=c9a495974b79c0eb69bbee81f852a3c7&token=ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA';

        $response = $this->get_curl($url);
        $results = json_decode($response, true);
        $results = array_reverse($results);
        $actions = array();
        foreach ($results as $key => $result) {
            if ($result['type'] == 'createCard') {
                $card = Card::query()->firstOrCreate([
                    't_card_id' => $result['data']['card']['id'],
                ], [
                    't_board_id' => $result['data']['board']['id'],
                    'name' => $result['data']['card']['name'],
                    'desc' => $result['data']['card']['desc'] ?? null,
                ]);

            } elseif ($result['type'] == 'updateCard') {
                $card = Card::query()->updateOrCreate([
                    't_card_id' => $result['data']['card']['id'],
                ], [
                    't_board_id' => $result['data']['board']['id'],
                    'name' => $result['data']['card']['name'],
                ]);
                if (isset($result['data']['card']['desc'])) {
                    $card->desc = $result['data']['card']['desc'];
                    $card->save();
                }
            } elseif ($result['type'] == 'commentCard') {
                $card = Card::query()->updateOrCreate([
                    't_card_id' => $result['data']['card']['id'],
                ], [
                    't_board_id' => $result['data']['board']['id'],
                    'name' => $result['data']['card']['name'],
                ]);
                if (isset($result['data']['card']['desc'])) {
                    $card->desc = $result['data']['card']['desc'];
                    $card->save();
                }


                $text = explode('@', $result['data']['text']);
                $body = $text[0];
                $name = end($text);
                $body = str_replace("\n", "", $body);
                $body = str_replace("\\", "", $body);
                $body = str_replace(PHP_EOL, "", $body);
                $name = trim($name);

                if ($name == $result['data']['card']['name'] && Comment::query()->where('body', $body)->doesntExist()) {

                    $contact = Contact::query()->where('name', $name)->where('card_id', $card->id)->first();

                    $comment = new Comment;
                    $comment->card_id = $card->id;
                    $comment->contact_id = $contact->id ?? null;
                    $comment->name = $name;
                    $comment->body = $body;
                    $comment->save();
                }
            }

            if (Action::query()->where('t_action_id', $result['id'])->doesntExist() && isset($card)) {
                $action = new Action;
                $action->card_id = $card->id;
                $action->t_action_id = $result['id'];
                $action->type = $result['type'];
                $action->data = $result['data'];
                $action->date = $result['date'];
                $action->save();

                $actions[] = $result;
            }
        }

        return $actions;

    }

    public function messages_send()
    {
        require_once(base_path('app/nusoup/nusoup.php'));

        $comments = Comment::query()->whereNull('sent_at')->with('contact')->get();
        $sent_msg = array();

        foreach ($comments as $comment) {

            $card = $comment->card()->first();

            if ($comment->contact == null) {
                $to = substr($card->desc, -11);
            } else {
                $to = $comment->contact->phone;
            }

            $contact = Contact::query()->updateOrCreate([
                'card_id' => $card->id,
            ], [
                'name' => $comment->name,
                'phone' => $to
            ]);

            $comment->contact_id = $contact->id;

            $url = "http://sms.Parsgreen.ir/Api/SendSMS.asmx?wsdl";
            $post = [
                'from' => '10001818181919',
                'to' => $to,
                'text' => $comment->body,
                'signature' => 'B8DD5858-EC2D-4EE2-98F0-B07435184A9E'
            ];

            date_default_timezone_set('Asia/Tehran');

            $webServiceURL = $url;
            $webServiceSignature = $post['signature'];
            $webServicetoMobile = $post['to'];
            $webServicetextMessage = $post['text'];

            $client = new nusoap_client($webServiceURL, true);
            $client->soap_defencoding = 'UTF-8';
            $err = $client->getError();
            if ($err) {
                $file = 'storage/logs/message_send.log';
                file_put_contents($file, 'Constructor error: ' . $err . "\n", FILE_APPEND | LOCK_EX);
            }
            $parameters['signature'] = $webServiceSignature;
            $parameters['toMobile'] = $webServicetoMobile;
            $parameters['smsBody'] = $webServicetextMessage;
            $parameters['retStr'] = "";

            $result = $client->call('Send', $parameters);

            if ($result['SendResult'] == 1) {
                $comment->sent_at = new Carbon();
                $sent_msg[] = $comment;
            }

            $comment->save();
        }

        return $sent_msg;
    }


    private function get_curl($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * @param string $url
     * @param array $post
     * @return array
     */
    private function post_curl($url, $post)
    {
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

        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @param string $t_card_id
     * @param string $t_board_id
     * @param string $name
     * @param string|null $action_card_id
     * @return object
     */
    private function create_card($t_card_id, $t_board_id, $name, $action_card_id = null)
    {
        $card = Card::query()->firstOrCreate([
            't_card_id' => $t_card_id,
        ], [
            't_board_id' => $t_board_id,
            'name' => $name,
        ]);

        if ($action_card_id) {
            $desc_url = "https://api.trello.com/1/cards/{$action_card_id}/desc?key=c9a495974b79c0eb69bbee81f852a3c7&token=ATTA8a40b4b49020f37007ce761e27f2289223318ed64f3b4f5138d5011d43e153d41F4CEEEA";
            $desc_response = $this->get_curl($desc_url);
            $desc_results = json_decode($desc_response, true);
            $desc = $desc_results['_value'];
            $card->desc = $desc;
            $card->save();
        }

        return $card;
    }


}
