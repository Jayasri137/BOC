<?php
$urls = [
    'https://www.bluestoneoverseas.com/assets/images/uploads/popup_fa02d4e59b7493a16d502304edef4328.jpeg',
    'https://www.bluestoneoverseas.com/assets/images/uploads/popup_a8240c83ab38e97e359c9fc7d97fe193.jpeg',
    'https://www.bluestoneoverseas.com/assets/images/uploads/popup_991ee7ac398abaca1cc369e47dc7190c.jpeg'
];

foreach ($urls as $url) {
    $basename = basename($url);
    $dest = 'assets/images/uploads/' . $basename;
    if (!file_exists($dest)) {
        $data = @file_get_contents($url);
        if ($data !== false) {
            file_put_contents($dest, $data);
            echo "Downloaded: $basename\n";
        } else {
            echo "Failed to download: $url\n";
        }
    } else {
        echo "Already exists: $basename\n";
    }
}
