<?php

namespace App\Services;

use App\Consts\Common;
use Exception;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image as InterventionImage;

/**
 * 画像処理をするためのサービスクラスです
 */
class ImageService
{

    /**
     * @var array $fileExtensions 登録可能な画像の拡張子
     * @var int $resizeMaxWidth リサイズする際の幅の基準値
     */
    private array $fileExtensions = ['jpg', 'jpeg', 'png'];
    private int $resizeMaxWidth = 300;
    private string $errMsg = '';

    /**
     * 画像を保存する
     * @param UploadedFile $file
     * @param int $visitHistoryId
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function customerImgStore(UploadedFile $file, int $visitHistoryId, int $userId): string
    {
        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtension($file);
        if ($extension === '') {
            return '';
        }

        // ファイル名作成
        $fileName = $this->makeCustomerImageFileName($visitHistoryId, $userId, $extension);

        // 画像を保存する
        $dirName = Common::CUSTOMER_IMG_DIR;
        $this->makeDirectory($dirName);
        $this->makeDirectory($dirName . '/resize');
        $this->imgStore($file, $dirName, $fileName);

        return $fileName;
    }

    /**
     * 渡されたファイルが登録可能な拡張子か確認してOKなら拡張子を返す
     * @param $file
     * @return string
     */
    private function checkFileExtension($file): string
    {
        // 渡された拡張子を取得
        $extension = $file->extension();
        if (!in_array($extension, $this->fileExtensions)) {
            $fileExtensions = json_encode($this->fileExtensions);
            $this->errMsg = "登録できる画像の拡張子は{$fileExtensions}のみです。";
            return '';
        }
        return $file->extension();
    }

    /**
     * ディレクトリが無ければ作成する
     * @param string $directoryName
     * @return void
     */
    private function makeDirectory(string $directoryName): void
    {
        $dir = storage_path('app/' . $directoryName);
        if (!file_exists($dir)) {
            // ディレクトリが無ければ作成する
            if (mkdir('storage/app/' . $directoryName, 0777, true)) {
                // 作成したディレクトリのパーミッションを確実に変更
                chmod('storage/app/' . $directoryName, 0777);
            }
        }
    }

    /**
     * 画像をそのままの状態と、リサイズした状態で保存する
     * @param $file
     * @param $dirName
     * @param $baseFileName
     * @return void
     */
    private function imgStore($file, $dirName, $baseFileName): void
    {
        // 画像を保存する
        $file->storeAs($dirName, $baseFileName);

        // リサイズして保存する
        InterventionImage::make($file)
            ->resize($this->resizeMaxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->orientate()
            ->save(storage_path('app/' . $dirName . '/resize/' . $baseFileName));
    }

    /**
     * ファイル名を作成する
     * @param int $visitHistoryId
     * @param int $userId
     * @param string $extension
     * @return string
     */
    private function makeCustomerImageFileName(int $visitHistoryId, int $userId, string $extension): string
    {
        // ファイル名の作成 => {日時}_{来店履歴ID(7桁に0埋め)}_{ユーザーID(5桁に0埋め)}_{ユニーク文字列}.{拡張子}
        return sprintf(
            '%s_%s_%s_%s.%s',
            time(),
            str_pad($visitHistoryId, 7, 0, STR_PAD_LEFT),
            str_pad($userId, 5, 0, STR_PAD_LEFT),
            sha1(uniqid(mt_rand(), true)),
            $extension
        );
    }

    /**
     * @return string
     */
    public function getErrorMsg(): string
    {
        return $this->errMsg;
    }

}
