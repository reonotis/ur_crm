<?php

namespace App\Console\Commands;

use Hash;
use App\Models\Customer;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DataMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DataMigration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '古いDBから新しいDBへデータを移行します。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        Log::info('--------------------------------------------');
        Log::info('DataMigration : batch開始');
        $this->info('batch start.');

        // 利用者データの移行
        $this->migration_UsersData();

        // 顧客データの移行
        $this->migration_CustomerData();

        // 来店履歴の移行
        $this->migration_visitHistoryData();

        // 顧客の画像レコードを移行
        $this->migration_visitHistoryImgData();

        // TODO 顧客の画像データを移行

        $this->info('batch finish.');
        Log::info('DataMigration : batch終了');
        Log::info('--------------------------------------------');
    }

    /**
     * ユーザーデータを移行する
     *
     * @return void
     */
    public function migration_UsersData(){
        Log::info('DataMigration : ユーザーデータ移行開始');
        $this->info('ユーザーデータ移行開始');
        try {
            // 旧DBからデータを取得
            $this->get_data_users();

            // TODO 取得したデータをCSVに書き出しておく

            DB::connection('mysql')->beginTransaction();
            DB::connection('mysql_2')->beginTransaction();
            // 新DBにデータを登録
            $this->insert_newDB_users();

            DB::connection('mysql')->commit();
            DB::connection('mysql_2')->commit();
        } catch(\Exception $e) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_2')->rollback();
            $this->error($e->getMessage());
            Log::alert('DataMigration', ['memo' => $e->getMessage()]);
        }
        $this->info('ユーザーデータ移行完了');
        Log::info('DataMigration : ユーザーデータ移行完了');
    }

    /**
     * ユーザーデータを取得する
     *
     */
    public function get_data_users(){
        Log::info('DataMigration :   ユーザーデータ取得');
        $this->_users = [];
        $sql = 'SELECT * FROM user WHERE user_id <> 0';
        $this->_users = DB::connection('mysql_2')->select($sql);

        if( empty($this->_users)) $this->info("登録するデータが1件もありません。");
    }

    /**
     * 新しいDBにユーザーデータを登録する
     *
     */
    public function insert_newDB_users(){
        foreach($this->_users as $_user ){
            Log::info('DataMigration :     ユーザーデータ登録 : id = '. $_user->user_id );
            if($_user->email) {
                $email = $_user->email;
            }else{
                $email = 'sample' . $_user->user_id ;
            }

            User::insert([[
                'id'           => $_user->user_id,
                'name'         => $_user->display_name,
                'email'        => $email,
                'password'     => Hash::make($_user->password),
                'shop_id'      => $_user->shop_id,
                'authority_id' => $_user->authority_id,
            ]]);
        }
    }

    /**
     * 来店履歴画像データを移行する
     *
     * @return void
     */
    public function migration_visitHistoryImgData(){
        Log::info('DataMigration : 来店履歴画像データ移行開始');
        $this->info('来店履歴画像データ移行開始');
        try {
            $this->_loopNo = 1;

            while(true){
                $this->info($this->_loopNo."回目のループ開始");

                // 旧DBからデータを取得
                $this->get_data_visitHistoryImg();
                //移行前のデータがなければループを終了させる
                if(empty($this->_style_imgs)) break;

                // TODO 取得したデータをCSVに書き出しておく

                DB::connection('mysql')->beginTransaction();
                DB::connection('mysql_2')->beginTransaction();
                // 新DBにデータを登録
                $this->insert_newDB_visitHistoryImg();
                // 旧DBのデータを移行済みに更新する
                $this->update_oldDB_visitHistoryImg();

                $this->_loopNo ++ ;
                // if( $this->_loopNo == 3 ) break; // TODO 作成中は1回でループ終了させる
                DB::connection('mysql')->commit();
                DB::connection('mysql_2')->commit();
            }
        } catch(\Exception $e) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_2')->rollback();
            $this->error($e->getMessage());
            Log::alert('DataMigration', ['memo' => $e->getMessage()]);
        }
        $this->info('来店履歴画像データ移行完了');
        Log::info('DataMigration : 来店履歴画像データ移行完了');
    }

    /**
     * 来店履歴画像データを取得する
     *
     */
    public function get_data_visitHistoryImg(){
        Log::info('DataMigration :   データ取得 : '. $this->_loopNo .'回目');
        $this->_style_imgs = [];
        $sql = 'SELECT * FROM sys_styleImg
                WHERE deleted_at <> 1
                LIMIT 100';
        $this->_style_imgs = DB::connection('mysql_2')->select($sql);

        if( empty($this->_style_imgs)) $this->info("登録するデータが1件もありません。");
    }

    /**
     * 新しいDBに来店履歴画像データを登録する
     *
     */
    public function insert_newDB_visitHistoryImg(){
        foreach($this->_style_imgs as $style_img ){
            Log::info('DataMigration :     データ登録 : id = '. $style_img->id );
            VisitHistoryImage::insert([[
                'id'           => $style_img->id,
                'customer_id'  => $style_img->customer_id,
                'visit_history_id' => $style_img->visit_id,
                'angle'        => $style_img->angle,
                'img_pass'     => $style_img->img_pass,
            ]]);
        }
    }

    /**
     * 古いDBの来店履歴画像データを移行済みする
     */
    public function update_oldDB_visitHistoryImg(){
        foreach($this->_style_imgs as $style_img ){
            Log::info('DataMigration :       データ更新 : id = '. $style_img->id );
            $sql = 'UPDATE sys_styleimg
                    SET deleted_at = 1
                    WHERE id =  '.$style_img->id ;
            $this->_customers = DB::connection('mysql_2')->update($sql);
        }
    }

    /**
     * 来店履歴データを移行する
     *
     * @return void
     */
    public function migration_visitHistoryData(){
        Log::info('DataMigration : 来店履歴データ移行開始');
        $this->info('来店履歴データ移行開始');
        try {
            $this->_loopNo = 1;

            while(true){
                $this->info($this->_loopNo."回目のループ開始");

                // 旧DBからデータを取得
                $this->get_data_visitHistory();
                //移行前のデータがなければループを終了させる
                if(empty($this->_visit_histories)) break;

                // TODO 取得したデータをCSVに書き出しておく
                DB::connection('mysql')->beginTransaction();
                DB::connection('mysql_2')->beginTransaction();
                // 新DBにデータを登録
                $this->insert_newDB_visitHistory();
                // 旧DBのデータを移行済みに更新する
                $this->update_oldDB_visitHistory();


                $this->_loopNo ++ ;
                DB::connection('mysql')->commit();
                DB::connection('mysql_2')->commit();
                // if( $this->_loopNo == 3 ) break; // TODO 作成中は1回でループ終了させる
            }

            // throw new \Exception("強制終了");
        } catch(\Exception $e) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_2')->rollback();
            $this->error($e->getMessage());
            Log::alert('DataMigration', ['memo' => $e->getMessage()]);
        }
        $this->info('来店履歴データ移行完了');
        Log::info('DataMigration : 来店履歴データ移行完了');
    }

    /**
     * 来店履歴データを取得する
     *
     */
    public function get_data_visitHistory(){
        Log::info('DataMigration :   データ取得 : '. $this->_loopNo .'回目');
        $this->_visit_histories = [];
        $sql = 'SELECT *
                FROM sys_visithistory
                WHERE deleted_at <> 1
                LIMIT 100';
        $this->_visit_histories = DB::connection('mysql_2')->select($sql);

        if( empty($this->_visit_histories)) $this->info("登録するデータが1件もありません。");
    }

    /**
     * 新しいDBに来店履歴データを登録する
     */
    public function insert_newDB_visitHistory(){
        foreach($this->_visit_histories as $visit_history ){
            Log::info('DataMigration :     データ登録 : id = '. $visit_history->visit_id );
            VisitHistory::insert([[
                'id'           => $visit_history->visit_id,
                'vis_date'     => $visit_history->visit_date,
                'vis_time'     => $visit_history->visit_time,
                'customer_id'  => $visit_history->customer_id,
                'shop_id'      => $visit_history->shop_id,
                'staff_id'     => $visit_history->user_id,
                'menu_id'      => $visit_history->menu_id,
                'visit_type_id'=> $visit_history->correspondence_id,
                'memo'         => $visit_history->visit_comment,
            ]]);
        }
    }

    /**
     * 古いDBの来店履歴データを移行済みする
     */
    public function update_oldDB_visitHistory(){
        foreach($this->_visit_histories as $visit_history ){
            Log::info('DataMigration :       データ更新 : id = '. $visit_history->visit_id );
            $sql = 'UPDATE sys_visitHistory
                    SET deleted_at = 1
                    WHERE visit_id =  '.$visit_history->visit_id ;
            $this->_customers = DB::connection('mysql_2')->update($sql);
        }
    }

    /**
     * 顧客データを移行する
     *
     * @return void
     */
    public function migration_CustomerData(){
        Log::info('DataMigration : 顧客データ移行開始');
        $this->info('顧客データ移行開始');
        try {
            $this->_loopNo = 1;

            while(true){
                $this->info($this->_loopNo."回目のループ開始");

                // 旧DBからデータを取得
                $this->get_data();
                //移行前のデータがなければループを終了させる
                if(empty($this->_customers)) break;

                // TODO 取得したデータをCSVに書き出しておく

                DB::connection('mysql')->beginTransaction();
                DB::connection('mysql_2')->beginTransaction();
                // 新DBにデータを登録
                $this->insert_newDB();
                // 旧DBのデータを移行済みに更新する
                $this->update_oldDB();

                DB::connection('mysql')->commit();
                DB::connection('mysql_2')->commit();
                $this->_loopNo ++ ;
                // if( $this->_loopNo == 3 ) break; // TODO 作成中は1回でループ終了させる
            }

            // throw new \Exception("強制終了");
        } catch(\Exception $e) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_2')->rollback();
            $this->error($e->getMessage());
            Log::alert('DataMigration', ['memo' => $e->getMessage()]);
        }
        $this->info('顧客データ移行完了');
        Log::info('DataMigration : 顧客データ移行完了');
    }

    /**
     * 対象データを取得する
     */
    public function get_data(){
        Log::info('DataMigration :   データ取得 : '. $this->_loopNo .'回目');
        $this->_customers = [];
        $sql = 'SELECT *
                FROM sys_customer
                WHERE derete_flag = 0
                LIMIT 100';
        $this->_customers = DB::connection('mysql_2')->select($sql);

        if( empty($this->_customers)) $this->info("登録するデータが1件もありません。");
    }

    /**
     * 新しいDBに登録する
     */
    public function insert_newDB(){
        foreach($this->_customers as $customer ){
            Log::info('DataMigration :     データ登録 : id = '. $customer->id );
            // 登録する誕生日を設定する
            if($customer->birthday == NULL){
                $birthday_year = NULL;
                $birthday_month = NULL;
                $birthday_day = NULL;
            }else{
                $birthday_year = substr($customer->birthday, 0, 4);
                $birthday_month = substr($customer->birthday, 5, 2);
                $birthday_day = substr($customer->birthday, 8, 2);
            }

            Customer::insert([[
                'id'          => $customer->id,
                'TOKKAI_shop' => $customer->TOKKAI_shop,
                'TOKKAI_no'   => $customer->TOKKAI_no,
                'member_number' => $customer->TOKKAI_number,
                'f_name'      => $customer->customer_fName,
                'l_name'      => $customer->customer_lName,
                'f_read'      => $customer->customer_fNameRead,
                'l_read'      => $customer->customer_lNameRead,
                'staff_id'    => $customer->person_staff,
                'sex'         => $customer->sex,
                'tel'         => $customer->tel,
                // TODO 'tel_home'         => $customer->tel_home,
                'email'       => $customer->email,
                'birthday_year'  => $birthday_year,
                'birthday_month' => $birthday_month,
                'birthday_day'   => $birthday_day,
                'shop_id'     => $customer->goToShop,
                'zip21'       => $customer->zip1,
                'zip22'       => $customer->zip2,
                'pref21'      => $customer->pref21,
                'addr21'      => $customer->addr21,
                'strt21'      => $customer->strt21,
                'memo'        => $customer->comment,
            ]]);
        }
    }

    /**
     * 古いDBを移行済みする
     */
    public function update_oldDB(){
        foreach($this->_customers as $customer ){
            Log::info('DataMigration :       データ更新 : id = '. $customer->id );
            $sql = 'UPDATE sys_customer
                    SET derete_flag = 1
                    WHERE id =  '.$customer->id ;
            $this->_customers = DB::connection('mysql_2')->update($sql);
        }
    }

}

/*
UPDATE sys_customer SET derete_flag = 0
UPDATE sys_visithistory SET deleted_at = 0
UPDATE sys_styleimg SET deleted_at = 0


*/


