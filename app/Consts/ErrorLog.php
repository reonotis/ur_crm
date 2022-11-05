<?php

namespace App\Consts;

/**
 * エラーコード
 * 10000番台 : admin でのエラー
 * 20000番台 : shop でのエラー
 * 30000番台 : user でのエラー
 *  1000番台 : Model でのエラー
 *  2000番台 : Controller でのエラー
 */
class ErrorLog
{
    public const CL_21001 = 'ErrorCode:CL_21001 ProductModel getByShopIdMethodによるエラ－';
    public const CL_21002 = 'ErrorCode:CL_21001 ProductModel createProductMethodによるエラ－';
    public const CL_21003 = 'ErrorCode:CL_21003 CategoryModel getByNextHierarchyによるエラ－';
    public const CL_22001 = 'ErrorCode:CL_22001 ProductController storeConfirmMethodによるエラ－';
    public const CL_22002 = 'ErrorCode:CL_22002 CategoryController storeMethodによるエラ－';
    public const CL_22003 = 'ErrorCode:CL_22002 ProductController storeMethodによるエラ－';
    public const CL_22004 = 'ErrorCode:CL_22002 ProductController showMethodによるエラ－';

}
