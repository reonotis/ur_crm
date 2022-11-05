<?php

namespace App\Console\Commands;

use App\Models\Menu;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserShop;
use App\Models\UserShopAuthorization;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
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

//        $this->shopImport();
        $this->userImport();
        $this->menuImport();
        $this->customerImport();
        $this->historyImport();

//        // 顧客データの移行
//        $this->migration_CustomerData();
//
//        // 来店履歴の移行
//        $this->migration_visitHistoryData();
//
//        // 顧客の画像レコードを移行
//        $this->migration_visitHistoryImgData();

        // TODO 顧客の画像データを移行

        $this->info('batch finish.');
        Log::info('DataMigration : batch終了');
        Log::info('--------------------------------------------');
    }

    /**
     * 店舗データを移行する
     * @return void
     * @throws Exception
     */
    public function shopImport()
    {
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : shopImport start...');
        try{
            $shops = DB::connection('mysql_2')->select("SELECT * FROM shop WHERE deleted_at IS NULL");
            if(empty($shops)){
                return;
            }

            $insertData = [];
            foreach($shops AS $shop){
                $insertData[] = [
                    'id' => $shop->shop_id,
                    'shop_name' => $shop->shop_name,
                    'shop_symbol' => $shop->TOKKAI_shop,
                    'email' => $shop->email,
                    'tel' => $shop->tel,
                    'img_pass' => NULL,
                    'start_time' => '10:00:00',
                    'end_time' => '21:00:00',
                    'last_reception_time' => '20:00:00',
                    'created_at' => $shop->created_at,
                    'updated_at' => $shop->updated_at,
                ];
            }

            Shop::insert($insertData);
        } catch (Exception $e) {
            Log::error( ' errorMsg : ' . $e->getMessage());
            throw new Exception('shop登録に失敗しました urhair_ur.shop.id=' . $shop->shop_id );
        }

        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : shopImport end');
    }

    /**
     * 利用者情報を移行する
     * @return void
     * @throws Exception
     */
    public function userImport()
    {
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : UserImport start...');
        try{
            $users = DB::connection('mysql_2')->select('select * from user');

            foreach($users AS $key => $user){
                if($user->user_id == 0){
                    continue;
                }

                if(empty($user->email)){
                    $email = 'test_' . $key . '@test.jp';
                }else{
                    $email = $user->email;
                }

                switch($user->status_id){
                    case 1:   // 在籍
                        $authorityId = 2;  // 2:在籍
                        break;
                    case 2:   // 長期休暇
                        $authorityId = 5;  // 5:長期休暇
                        break;
                    default:
                        $authorityId = 9;  // 9:退職
                }

                if(empty($user->password)){
                    $password = $user->user_id;
                }else{
                    $password = $user->password;
                }

                $insertData = [
                    'id' => $user->user_id,
                    'name' => $user->display_name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'authority_level' => $authorityId,
                ];
                User::create($insertData);

                UserShopAuthorization::create([
                    'shop_id' => $user->shop_id,
                    'user_id' => $user->user_id,
                    'user_read' => 1,
                    'user_create' => 0,
                    'user_edit' => 0,
                    'user_delete' => 0,
                    'customer_read' => 1,
                    'customer_create' => 0,
                    'customer_edit' => 0,
                    'customer_delete' => 0,
                    'reserve_read' => 1,
                    'reserve_create' => 0,
                    'reserve_edit' => 0,
                    'reserve_delete' => 0,
                ]);
            }
        } catch (Exception $e) {
            Log::error( ' errorMsg : ' . $e->getMessage());
            throw new Exception('ユーザー登録に失敗しました urhair_ur.user.id=' . $user->user_id );
        }
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : UserImport end');
    }

    /**
     * メニューデータを移行する
     * @return void
     * @throws Exception
     */
    public function menuImport()
    {
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : menuImport start...');
        try{
            $menus = DB::connection('mysql_2')->select("SELECT * FROM menu WHERE deleted_at IS NULL");
            if(empty($menus)){
                return;
            }

            $insertData = [];
            foreach($menus AS $menu){
                $insertData[] = [
                    'id' => $menu->menu_id,
                    'menu_name' => $menu->menu_name,
                    'menu_read' => $menu->menu_read,
                    'rank' => $menu->menu_rank,
                    'price' => $menu->price,
                    'shortening' => $menu->shortening,
                    'created_at' => $menu->created_at,
                    'updated_at' => $menu->updated_at,
                ];
            }

            Menu::insert($insertData);
        } catch (Exception $e) {
            Log::error( ' errorMsg : ' . $e->getMessage());
            throw new Exception('メニュー登録に失敗しました urhair_ur.menu.id=' .  $menu->menu_id);
        }
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : menuImport end');
    }

    /**
     * 顧客データを移行する
     * @return void
     * @throws Exception
     */
    public function customerImport()
    {
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : CustomerImport start...');
        try{
            $lastId = 1;
            while(true){ // 無限ループ
                $sql = "SELECT *
                        FROM sys_customer
                        WHERE id > {$lastId}
                        AND derete_flag = 0
                        ORDER BY id ASC
                        LIMIT 1000";
                $sysCustomers = DB::connection('mysql_2')->select($sql);
                if(empty($sysCustomers)){
                    break; // データが無ければ繰返しの強制終了
                }

                $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : CustomerImport insertLastId:' . $lastId . '...');

                $insertData = [];
                foreach($sysCustomers AS $sysCustomer){
                    $lastId = $sysCustomer->id;

                    $insertData[] = [
                        'id' => $sysCustomer->id,
                        'customer_no' => $sysCustomer->TOKKAI_number,
                        'f_name' => $sysCustomer->customer_fName,
                        'l_name' => $sysCustomer->customer_lName,
                        'f_read' => $sysCustomer->customer_fNameRead,
                        'l_read' => $sysCustomer->customer_lNameRead,
                        'sex' => $sysCustomer->sex,
                        'tel' => $sysCustomer->tel,
                        'email' => $sysCustomer->email,
                        'zip21' => $sysCustomer->zip1,
                        'zip22' => $sysCustomer->zip2,
                        'pref21' => $sysCustomer->pref21,
                        'address21' => $sysCustomer->addr21,
                        'street21' => $sysCustomer->strt21,
                        'shop_id' => $sysCustomer->goToShop,
                        'staff_id' => $sysCustomer->person_staff,
                        'memo' => $sysCustomer->comment,
                    ];

                }
                Customer::insert($insertData);
            }
        } catch (Exception $e) {
            Log::error( ' errorMsg : ' . $e->getMessage());
            throw new Exception('顧客登録に失敗しました urhair_ur.sys_customer.id=' . $lastId );
        }
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : CustomerImport end');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function historyImport()
    {
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : historyImport start...');
        try{
            $lastId = 0;
            while(true){ // 無限ループ
                $sql = "SELECT *
                        FROM sys_visitHistory
                        WHERE visit_id > {$lastId}
                        AND deleted_at = 0
                        ORDER BY visit_id ASC
                        LIMIT 1000";

                $sysVisitHistory = DB::connection('mysql_2')->select($sql);
                if(empty($sysVisitHistory)){
                    break; // データが無ければ繰返しの強制終了
                }
                $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : historyImport insertLastId:' . $lastId . '...');

                $insertData = [];
                foreach($sysVisitHistory AS $visitHistory){
                    $lastId = $visitHistory->visit_id;

                    $insertData[] = [
                        'id' => $visitHistory->visit_id,
                        'vis_date' => $visitHistory->visit_date,
                        'vis_time' => $visitHistory->visit_time,
                        'customer_id' => $visitHistory->customer_id,
                        'shop_id' => $visitHistory->shop_id,
                        'user_id' => $visitHistory->user_id,
                        'visit_reserve_id' => null,
                        'visit_type_id' => $visitHistory->correspondence_id,
                        'status' => 1,
                        'menu_id' => $visitHistory->menu_id,
                        'memo' => $visitHistory->visit_comment,
                    ];

                }
                VisitHistory::insert($insertData);
            }
        } catch (Exception $e) {
            Log::error( ' errorMsg : ' . $e->getMessage());
            throw new Exception('来店履歴の登録に失敗しました urhair_ur.sys_visitHistory.visit_id=' . $visitHistory->visit_id);
        }
        $this->info(Carbon::now()->format('Y-m-d H:i:s') . ' : historyImport end');
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
        } catch(Exception $e) {
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
        $sql = 'SELECT * FROM sys_styleIMG
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
            $sql = 'UPDATE sys_styleIMG
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
        } catch(Exception $e) {
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
                FROM sys_visitHistory
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
        } catch(Exception $e) {
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
                'created_at'  => $customer->created_at,
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

