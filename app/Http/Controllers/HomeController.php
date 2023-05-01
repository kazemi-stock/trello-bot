<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Card;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use nusoap_client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $chats = array();

        if ($request->has('phone') && (!empty($request->phone))) {
            $contact = Contact::query()->where('phone', $request->phone)->first();
            if (isset($contact)) {
                if (!empty($request->message)) {
                    $sender = $this->sender($contact->phone, $request->message);
                    if ($sender['SendResult'] == 1) {
                        $new_comment = new Comment;
                        $new_comment->card_id = $contact->card_id;
                        $new_comment->contact_id = $contact->id;
                        $new_comment->name = $contact->name ?? '';
                        $new_comment->body = $request->message ?? '';
                        $new_comment->sent_at = new Carbon();
                        $new_comment->save();
                    }
                }
                if ($contact->messages()->exists()) {
                    foreach ($contact->messages()->orderBy('created_at')->get() as $key => $message) {
                        $msg['name'] = $contact->name ?? $contact->phone;
                        $msg['text'] = $message->body;
                        $date = new Carbon($message->created_at, 'Asia/Tehran');
                        $msg['date'] = date_format($date, 'Y-m-d h:m:s');
                        array_push($chats, $msg);
                    }
                }
                if ($contact->comments()->exists()) {
                    foreach ($contact->comments()->orderBy('created_at')->get() as $key => $comment) {
                        $comnt['name'] = 'admin';
                        $comnt['text'] = $comment->body;
                        $date = new Carbon($comment->created_at, 'Asia/Tehran');
                        $comnt['date'] = date_format($date, 'Y-m-d h:m:s');
                        array_push($chats, $comnt);
                    }
                }

                if (!empty($chats)) {
                    usort($chats, function ($a, $b) {
                        return strcmp($a["date"], $b["date"]);
                    });
                }

            } else {
                $sender = $this->sender($request->phone, $request->message);
                if ($sender['SendResult'] == 1) {
                    $chat['name'] = $request->phone;
                    $chat['text'] = $request->message;
                    $date = new Carbon('now', 'Asia/Tehran');
                    $chat['date'] = date_format($date, 'Y-m-d h:m:s');
                    array_push($chats, $chat);

                }
            }
        }
        return view('home', compact('chats'));
    }

    private function sender($to, $body)
    {
        require_once(base_path('app/nusoup/nusoup.php'));


        $url = "http://sms.Parsgreen.ir/Api/SendSMS.asmx?wsdl";
        $post = [
            'from' => '10001818181919',
            'to' => $to,
            'text' => $body,
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

        return $result;
    }

// مخاطبین ---------------------------------------------------------------
    public function contacts_index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function contacts_create()
    {
        return view('contacts.create');
    }

    public function contacts_store(Request $request)
    {
        $phone = preg_match('/^(?:0)?9[0-9]{9}$/', $request->phone);
        $name = preg_match('/^[0123456789 آابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیئ\s]+$/', $request->name);
        if ($phone == 0) {
            return back()->with('error', 'شماره موبایل معتبر نیست. لطفا شماره موبایل را با صفر وارد کنید');
        }
        if ($name == 0) {
            return back()->with('error', 'نام معتبر نیست. لطفا نام را با حروف فارسی و اعداد وارد کنید.');
        }
        if (Contact::query()->wherePhone($request->phone)->exists()) {
            return back()->with('error', 'مخاطب با این شماره قبلاً ثبت شده است.');
        }

        Contact::query()->create($request->all());

        return back()->with('success', 'مخاطب با موفقیت ثبت شد.');
    }

    public function contacts_edit()
    {
        return view('contacts.edit');
    }

    public function contacts_update(Request $request)
    {
        $phone = preg_match('/^(?:0)?9[0-9]{9}$/', $request->phone);
        $name = preg_match('/^[0123456789 آابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیئ\s]+$/', $request->name);
        if ($phone == 0) {
            return back()->with('error', 'شماره موبایل معتبر نیست. لطفا شماره موبایل را با صفر وارد کنید');
        }
        if ($name == 0) {
            return back()->with('error', 'نام معتبر نیست. لطفا نام را با حروف فارسی و اعداد وارد کنید.');
        }

        $contact = Contact::query()->find($request->id);
        if (Contact::query()->where( 'phone', $request->phone)->where('id', '!=', $contact->id)->exists() ) {
            return back()->with('error', 'مخاطب با این شماره قبلاً ثبت شده است.');
        }


        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->save();

        return back()->with('success', 'مخاطب با موفقیت ویرایش شد.');
    }

    public function contacts_delete(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'مخاطب با موفقیت حذف شد.');
    }

// پیام های دریافتی --------------------------------------------------------

    public function messages_index()
    {
        $messages = Message::all();
        return view('messages.index', compact('messages'));
    }

// پیام های ارسالی --------------------------------------------------------

    public function comments_index()
    {
        $comments = Comment::all();
        return view('comments.index', compact('comments'));
    }

// کارت ها --------------------------------------------------------

    public function cards_index()
    {
        $cards = Card::all();
        return view('cards.index', compact('cards'));
    }

// رویدادها --------------------------------------------------------

    public function actions_index()
    {
        $actions = Action::all();
        return view('actions.index', compact('actions'));
    }

// تنظیمات --------------------------------------------------------

    public function setting_index()
    {
        return view('setting.index');
    }

    public function setting_update()
    {
        return back();
    }

// کاربران --------------------------------------------------------

    public function users_index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function users_update()
    {
        return back();
    }


}
