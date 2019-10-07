<?php
    require_once "vendor/autoload.php";
    use Aws\S3\S3Client;

    $bucket = 'mm-image-storage';
    $key = '';
    $secret = '';

    // S3クライアントを作成
    $s3 = new S3Client(array(
        'version' => 'latest',
        'credentials' => array(
            'key' => $key,
            'secret' => $secret,
        ),
        'region'  => 'ap-northeast-1', // 東京リージョン
    ));

    // アップロードされた画像の処理
    $file = $_FILES['file']['tmp_name'];
    if (!is_uploaded_file($file)) {
        return;
    }

    // S3バケットに画像をアップロード
    $result = $s3->putObject(array(
        'Bucket' => $bucket,
        'Key' => time() . '.txt', //ファイルのキー
        'Body' => fopen($file, 'rb'),
        'ACL' => 'public-read', //公開
        'ContentType' => mime_content_type($file) //ファイルの種類を識別
    ));

    // 結果を表示
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
?>