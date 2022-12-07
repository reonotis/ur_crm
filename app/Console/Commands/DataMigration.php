<?php

namespace App\Console\Commands;

use App\Console\Base;
use App\Models\Customer;
use App\Models\CustomerNoCounter;
use App\Models\Menu;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserShopAuthorization;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DataMigration extends Base
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
        $this->writeConsoleAndLog('info', '--------------------------------------------');
        $this->writeConsoleAndLog('info', $this->name . ' start...');

        $this->shopImport();
        $this->userImport();
        $this->menuImport();
        $this->customerImport();
        $this->historyImport();
        $this->styleIMGImport();

        $this->writeConsoleAndLog('info', $this->name . ' finish.');
        $this->writeConsoleAndLog('info', '--------------------------------------------');
    }

    /**
     * 店舗データを移行する
     * @return void
     * @throws Exception
     */
    public function shopImport()
    {
        $this->writeConsoleAndLog('info', 'shopImport start...');
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
            $this->writeConsoleAndLog('error', 'errorMsg : ' . $e->getMessage());
            throw new Exception('shop登録に失敗しました urhair_ur.shop.id=' . $shop->shop_id );
        }

        $this->writeConsoleAndLog('info', 'shopImport end');
    }

    /**
     * 利用者情報を移行する
     * @return void
     * @throws Exception
     */
    public function userImport()
    {
        $this->writeConsoleAndLog('info', 'UserImport start...');
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
            $this->writeConsoleAndLog('error', 'errorMsg : ' . $e->getMessage());
            throw new Exception('ユーザー登録に失敗しました urhair_ur.user.id=' . $user->user_id );
        }
        $this->writeConsoleAndLog('info', 'UserImport end');
    }

    /**
     * メニューデータを移行する
     * @return void
     * @throws Exception
     */
    public function menuImport()
    {
        $this->writeConsoleAndLog('info', 'menuImport start...');
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
            $this->writeConsoleAndLog('error', 'errorMsg : ' . $e->getMessage());
            throw new Exception('メニュー登録に失敗しました urhair_ur.menu.id=' .  $menu->menu_id);
        }
        $this->writeConsoleAndLog('info', 'menuImport end');
    }

    /**
     * 顧客データを移行する
     * @return void
     * @throws Exception
     */
    public function customerImport()
    {
        $this->writeConsoleAndLog('info', 'CustomerImport start...');
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

                $this->writeConsoleAndLog('info', 'CustomerImport:insertLastId:' . $lastId . '...');

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
                        'created_at' => $sysCustomer->created_at,
                    ];

                }
                Customer::insert($insertData);
            }

            $newId = Customer::select('id')->orderBy('id', 'desc')->first()->id;
            CustomerNoCounter::create([
                'id' => $newId,
            ]);

        } catch (Exception $e) {
            $this->writeConsoleAndLog('error', 'errorMsg : ' . $e->getMessage());
            throw new Exception('顧客登録に失敗しました urhair_ur.sys_customer.id=' . $lastId );
        }
        $this->writeConsoleAndLog('info', 'CustomerImport end');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function historyImport()
    {
        $this->writeConsoleAndLog('info', 'historyImport start...');
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
                $this->writeConsoleAndLog('info', 'historyImport insertLastId:' . $lastId . '...');

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
                        'created_at' => $visitHistory->created_at,
                    ];

                }
                VisitHistory::insert($insertData);
            }
        } catch (Exception $e) {
            $this->writeConsoleAndLog('error', 'errorMsg : ' . $e->getMessage());
            throw new Exception('来店履歴の登録に失敗しました urhair_ur.sys_visitHistory.visit_id=' . $visitHistory->visit_id);
        }
        $this->writeConsoleAndLog('info', 'historyImport end');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function styleIMGImport()
    {
        $this->writeConsoleAndLog('info', 'styleIMGImport start...');
        try{
            $lastId = 0;
            while(true){ // 無限ループ
                $sql = "SELECT *
                        FROM sys_styleIMG
                        WHERE id > {$lastId}
                        AND deleted_at = 0
                        ORDER BY id ASC
                        LIMIT 1000";

                $styleIMGs = DB::connection('mysql_2')->select($sql);
                if(empty($styleIMGs)){
                    break; // データが無ければ繰返しの強制終了
                }
                $this->writeConsoleAndLog('info', 'historyImport insertLastId:' . $lastId . '...');

                $insertData = [];
                foreach($styleIMGs AS $styleIMG){
                    $lastId = $styleIMG->id;

                    $insertData[] = [
                        'id' => $styleIMG->id,
                        'visit_history_id' => $styleIMG->visit_id,
                        'customer_id' => $styleIMG->customer_id,
                        'angle' => $styleIMG->angle,
                        'img_pass' => $styleIMG->img_pass,
                        'status' => $styleIMG->status,
                    ];
                }
                VisitHistoryImage::insert($insertData);
            }
        } catch (Exception $e) {
            $this->writeConsoleAndLog('error', 'errorMsg : ' . $e->getMessage());
            throw new Exception('来店履歴の登録に失敗しました urhair_ur.sys_styleIMG.id=' . $styleIMG->visit_id);
        }
        $this->writeConsoleAndLog('info', 'styleIMGImport end');
    }

}

