<?php

namespace App\Jobs;

use App\MessangerLog;
use App\Messengers\TelegramMessenger;
use App\Messengers\ViberMessenger;
use App\Messengers\WhatsappMessenger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $failed_sending = [];

        if (!empty($this->data) and is_array($this->data)) {
            foreach ($this->data as $item) {
                $message_model = MessangerLog::find($item);
                if (!$message_model->isRepeatSend()) continue;
                if ($message_model) {
                    switch ($message_model->messenger) {
                        case 'Telegram':
                            $messenger = new TelegramMessenger((string)$message_model->message);
                            break;
                        case 'WhatsApp':
                            $messenger = new WhatsappMessenger((string)$message_model->message);
                            break;
                        case 'Viber':
                            $messenger = new ViberMessenger((string)$message_model->message);
                            break;
                        default:
                            $messenger = null;
                            break;
                    }

                    if (!empty($messenger)) $response = $messenger->sendMessage();
                    else $response['status'] = 'error';

                    if ($response['status'] == 'ok') {
                        $message_model->done = true;
                        $message_model->save();
                    } elseif ($response['status'] == 'error') {
                        if ($message_model->isRepeatSend()) {
                            $message_model->count++;
                            $message_model->save();
                            $failed_sending[] = $item;
                        }
                    }
                }
            }
        }

        if (!empty($failed_sending)) {
            self::dispatch($failed_sending)->delay(now()->addMinutes(7));
        }
    }
}
