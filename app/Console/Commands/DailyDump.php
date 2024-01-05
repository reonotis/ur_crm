<?php

namespace App\Console\Commands;

use App\Console\Base;
use Exception;
use PDO;
use PDOException;

/**
 *
 */
class DailyDump extends Base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DailyDump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DBのダンプ処理を行います';

    /** @var string $dbName */
    private $dbName;

    /** @var string $password */
    private $password;

    /** @var string $dsn */
    private $dsn;

    /** @var string $host */
    private $host;

    /** @var string $user */
    private $user;

    /** @var string $savePath */
    private $savePath;


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
        $this->writeConsoleAndLog('info', '--------------------------------------------');
        $this->writeConsoleAndLog('info', $this->name . ' start...');

        $this->_setConf();
        try {
            $dbh = new PDO($this->dsn, $this->user, $this->password);
        } catch (PDOException $e) {
            throw new Exception("データベースにアクセスできません！\n " . $e->getMessage());
        }

        // ダンプ実行
        $command = 'mysqldump --single-transaction --default-character-set=binary ' . $this->dbName . ' --host=' . $this->host . ' --user=' . $this->user . ' --password=' . $this->password . ' > ' . $this->savePath;
        system($command);

        // 結果確認
        if (file_exists($this->savePath) && filesize($this->savePath) > 0) {
            $this->writeConsoleAndLog('info', 'ダンプに成功しました');
        } else {
            $this->writeConsoleAndLog('error', 'ダンプに失敗しました');
            // TODO 管理アドレスにメール飛ばしたい
        }

        $this->writeConsoleAndLog('info', $this->name . ' finish.');
        $this->writeConsoleAndLog('info', '--------------------------------------------');
    }

    private function _setConf()
    {
        $this->dbName = env('DB_DATABASE');
        $this->host = env('DB_HOST');
        $this->user = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');
        $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;

        $filePath = base_path() . '/dump/';
        $fileName = date('Ymd') . '_DB_dump.sql';
        $this->savePath = $filePath . $fileName;
    }
}
