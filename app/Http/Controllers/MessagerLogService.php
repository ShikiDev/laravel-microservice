<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\MessangerLog;
use Illuminate\Http\Request;


class MessagerLogService extends Controller
{
    /**
     * Экшен, который фиксирует реквест к сервису на отправку сообщения через messengers
     *
     * @param Request $request
     * @return string
     */
    public function addRequestToQueue(Request $request)
    {
        try {

            if (is_array($request->post('request'))) {
                $data = $request->post('request');
            } else {
                $data = [];
                $request_obj = json_decode($request->post('request'));

                $data['contacts'] = isset($request_obj->contacts) ? $request_obj->contacts : false;
                $data['message'] = isset($request_obj->message) ? $request_obj->message : false;
                $data['messengers'] = isset($request_obj->messengers) ? $request_obj->messengers : false;
            }

            if (empty($data['contacts'])) throw new \Exception('Contacts list was not specified', 400);
            if (empty($data['message'])) throw new \Exception('Message was not specified', 400);
            if (empty($data['messengers'])) throw new \Exception('Messengers was not specified', 400);

            $contacts = explode(';', $data['contacts']);
            $messengers = explode(';', $data['messengers']);
            $messengers_log_uids = [];
            $contact_skipped = [];

            foreach ($contacts as $contact) {
                if (!MessangerLog::validateContact($contact)) {
                    $contact_skipped[] = $contact;
                    continue;
                }
                foreach ($messengers as $messenger) {
                    $messenger_log = MessangerLog::where('message', $data['message'])->where('contact', $contact)->where('messenger', $messenger)->first();
                    if (empty($messenger_log)) {
                        $messenger_log = new MessangerLog();
                        $messenger_log->message = $data['message'];
                        $messenger_log->contact = $contact;
                        $messenger_log->messenger = $messenger;
                        $messenger_log->save();
                        $messengers_log_uids[] = $messenger_log->id;
                    }
                }
            }

            if (!empty($messengers_log_uids)) SendMessage::dispatch($messengers_log_uids)->delay(now()->addMinutes(5));

            $response['status'] = 'ok';
            $response['code'] = 200;
            $response['message'] = !empty($messengers_log_uids) ? 'Request added to Queue and will execute later.' : 'Nothing to send';
            if (!empty($contact_skipped)) $response['skipped_contacts'] = implode(',', $contact_skipped);
        } catch (\Exception $exception) {
            $response = [];
            $response['status'] = 'error';
            $response['code'] = $exception->getCode();
            $response['message'] = $exception->getMessage();
            $response['line'] = $exception->getLine();
        }

        //не знаю, как можно модифицировать ассоциативный массив методами Laravel
        return json_encode($response);
    }
}
