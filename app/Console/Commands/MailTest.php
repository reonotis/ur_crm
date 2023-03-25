<?php

namespace App\Console\Commands;

use App\Console\Base;
use Exception;
use Illuminate\Support\Facades\Mail;

/**
 *
 */
class MailTest extends Base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MailTest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '管理者のアドレスにメールを送信します';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $this->writeConsoleAndLog('info', $this->name . ' start...');

        $data = [
            "name" => "aaaaaa",
        ];
        Mail::send('emails.sample', $data, function ($message) {
            $message->to('fujisawareon@yahoo.co.jp')
                ->from('info@reonotis.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('お申込みありがとうございます。');
        });

        $this->writeConsoleAndLog('info', $this->name . ' finish.');
    }

}
