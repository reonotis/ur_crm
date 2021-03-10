<?php

namespace App\Console\Commands\courseApplication;

use Illuminate\Console\Command;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use \SplFileObject;

class courseApplicationImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:courseApplicationImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wordpressのコースに申し込まれた顧客情報をインポートします';

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
     * @return mixed
     */
    public function handle()
    {
        Log::info('--------------------------------------------');
        Log::info('courseApplicationImport : batch開始');
        try {
            $path = base_path()."/inport_file" ;
            $files = \File::files($path);
            // ファイル毎にデータの取り込みを行う
            if(!empty($files)){
                foreach ($files as $file) {
                    // データの取り込み
                    $reslt = $this->csvImport($file->getPathname());
                }
            }else{
                $this->info('There are no files that can be imported.');
                Log::info('courseApplicationImport : 取り込みファイルはありませんでした。');
            }
            $this->info('finish.');
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            Log::alert('courseApplicationImport', ['memo' => $e->getMessage()]);
        }
        Log::info('courseApplicationImport : batch終了');
    }




    /**
     * CSVデータを取得して格納しておく
     *
     * @param string $csvfile CSVファイルパス
     * @param string $mode `sjis` ならShift-JISでカンマ区切り、 `utf16` ならUTF-16LEでタブ区切りのCSVを読む。'utf8'なら文字コード変換しないでカンマ区切り。
     */
    function csvImport($csvfile, $mode='utf8')
    {
        Log::info('courseApplicationImport : ファイル取り込み開始', ['file_Name' => $csvfile]);
        // ファイル存在確認
        if(!file_exists($csvfile)) throw new \Exception("ファイルがありません");
        setLocale(LC_ALL, 'English_United States.1252');

        $file = new SplFileObject($csvfile);
        $file->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::READ_AHEAD
        );

        foreach ($file as $i => $row){
            // 1行目の場合はキーヘッダ行として取り込み
            if($i===0) {
                foreach($row as $j => $col) $colbook[$j] = $col;
                continue;
            }
            // 2行目以降の場合はデータ行として取り込み
            $line = array();
            foreach($colbook as $j=>$col) $line[$colbook[$j]] = @$row[$j];
            $cusID = $this->insertCustomer($line);
            $this->insertCourse($line, $cusID);
        }
        // 顧客情報を登録
        Log::info('courseApplicationImport : ファイル取り込み終了');


        // ファイルをcompletedディレクトリに移動する
        $filepath = pathinfo($csvfile);
        $filename =  $filepath['basename'];
        $newname =  $filepath['dirname']."/completed/". $filename;
        rename (  $csvfile ,  $newname );

        return true;
    }

    /**
     * 顧客情報をcustomersテーブルに登録します。
     */
    public function insertCustomer($line){
        Log::info('courseApplicationImport : 顧客情報登録開始');
        // 登録作業
        $Customer = new Customer;
        $Customer->name = $line['f_name'] ." ".$line['l_name'] ;
        $Customer->read = $line['f_read'] ." ".$line['l_read'] ;
        $Customer->tel  = $line['tel'] ;
        $Customer->email  = $line['email'] ;
        $Customer->instructor = $line['instructor_id'] ;
        $Customer->zip21  = $line['zip21'] ;
        $Customer->zip22  = $line['zip22'] ;
        $Customer->pref21  = $line['pref21'] ;
        $Customer->addr21  = $line['addr21'] ;
        $Customer->strt21  = $line['strt21'] ;
        $Customer->save();
        Log::info('courseApplicationImport : 顧客情報登録完了 ', ['customers.id' => $Customer->id]);
        return $Customer->id; ;
    }
    
    /**
     *
     */
    public function insertCourse($file, $cusID){
        Log::info('courseApplicationImport : コース情報登録開始');
        // dd($file, $cusID);
        Log::info('courseApplicationImport : コース情報登録完了 ', ['作成中' => '作成中']);

    }




}
