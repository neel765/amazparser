<?php
$output = '';
if (isset($_POST['url'])) {
    $url = $_POST['url'];
    $parsedUrl = parse_url($url);
    $host = $parsedUrl['host'];

    // Define supported domains
    $supportedDomains = [
        'www.amazon.es',
        'www.amazon.in',
        'www.amazon.com',
        'www.amazon.co.uk',
        'www.amazon.de',
        'www.amazon.co.jp',
        'www.amazon.fr',
        'www.amazon.it',
        // Add other domains as needed
    ];

    // Check if the host is in the supported domains
    if (in_array($host, $supportedDomains)) {
        $userAgent = 'Amazon.com/28.4.0.100 (Android/14/SomeModel)';
        $cookies = implode('; ', [
            'mobile-device-info=dpi:420.0|w:1080|h:2135',
            'amzn-app-id=Amazon.com/28.4.0.100/18.0.357239.0',
            'amzn-app-ctxt=1.8%20%7B%22an%22%3A%22Amazon.com%22%2C%22av%22%3A%2228.4.0.100%22%2C%22xv%22%3A%221.15.0%22%2C%22os%22%3A%22Android%22%2C%22ov%22%3A%2214%22%2C%22cp%22%3A788760%2C%22uiv%22%3A4%2C%22ast%22%3A3%2C%22nal%22%3A%221%22%2C%22di%22%3A%7B%22pr%22%3A%22OnePlus7%22%2C%22md%22%3A%22GM1901%22%2C%22v%22%3A%22OnePlus7%22%2C%22mf%22%3A%22OnePlus%22%2C%22dsn%22%3A%2245ae2d3b4efa48a399e0f0a324adbaa7%22%2C%22dti%22%3A%22A1MPSLFC7L5AFK%22%2C%22ca%22%3A%22Carrier%22%2C%22ct%22%3A%22WIFI%22%7D%2C%22dm%22%3A%7B%22w%22%3A1080%2C%22h%22%3A2135%2C%22ld%22%3A2.625%2C%22dx%22%3A403.4110107421875%2C%22dy%22%3A409.90301513671875%2C%22pt%22%3A0%2C%22pb%22%3A78%7D%2C%22is%22%3A%22com.android.vending%22%2C%22msd%22%3A%22' . $host . '%22%7D',
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_ENCODING , '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'user-agent: ' . $userAgent,
            'cookie: ' . $cookies,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        if ($result && preg_match('@href="(/view-3d[^"]+)"@', $result, $matches)) {
            $output = '3D Model: <a target="_blank" href="https://' . $host . $matches[1] . '">https://' . $host . $matches[1] . '</a> (The zip file with the model can be found in the network tab in Developer tools)';
        } else {
            $output = 'No 3D model found.';
        }

        curl_close($ch);
    } else {
        $output = 'Unsupported URL. Make sure it is a valid Amazon product URL.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amazon 3D Model Parser</title>
</head>
<body>
    <h1>Amazon 3D Model Parser</h1>
    <p><?= $output ?></p>
    <a href="index.html">Go Back</a>
</body>
</html>
