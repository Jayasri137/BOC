<?php
$content = file_get_contents('index.php');

$pattern_process = '/(<!-- PROCESS -->.*?<\/section>)/s';
$pattern_services = '/(<section class="section services-bento" id="services".*?<\/section>)/s';

preg_match($pattern_process, $content, $m_process);
preg_match($pattern_services, $content, $m_services);

if (!empty($m_process[0]) && !empty($m_services[0])) {
    $process_str = $m_process[0];
    $services_str = $m_services[0];
    
    // find the exact substring that spans both to ensure we replace properly
    $start_pos = strpos($content, $process_str);
    $end_pos = strpos($content, $services_str) + strlen($services_str);
    
    if ($start_pos !== false && $end_pos !== false && $start_pos < $end_pos) {
        $length = $end_pos - $start_pos;
        $swapped = $services_str . "\n" . $process_str;
        $content = substr_replace($content, $swapped, $start_pos, $length);
        file_put_contents('index.php', $content);
        echo "Sections swapped successfully!";
    } else {
        echo "Error: Could not determine span of sections.";
    }
} else {
    echo "Error: Could not match sections.";
}
?>
