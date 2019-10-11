<?php
    require_once "../vendor/autoload.php";
    use Aws\S3\S3Client;

    $bucket = '';
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

    $file = $_FILES['file']['tmp_name'];
    if (!is_uploaded_file($file)) {
        return;
    }

    // S3バケットに画像をアップロード
    $result = $s3->putObject(array(
        'Bucket' => $bucket,
        'Key' => time() . '.jpg',
        'Body' => fopen($file, 'rb'),
        'ACL' => 'public-read', // 画像は一般公開されます
        'ContentType' => mime_content_type($file), //ファイルの拡張子を確認
    ));
    // テスト用
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
?>