<?php
$file = 'index.php';
$lines = file($file);

$part1 = array_slice($lines, 0, 848); // lines 1 to 848
$countriesHtml = array_slice($lines, 848, 106); // lines 849 to 954 (wait, 953-848+1=106)
$part2 = array_slice($lines, 954, 252); // lines 955 to 1206
$processHtml = array_slice($lines, 1206, 52); // lines 1207 to 1258
$part3 = array_slice($lines, 1258); // line 1259+

// WAIT, LET ME DO THIS USING STRING REPLACEMENT SO I DON'T MESS UP MATH.
$content = file_get_contents($file);

$countryStart = '<!-- COUNTRIES -->';
$countryEnd = '</section>' . "\n" . '<section class="section why-elite-section"'; 
// I need to split safely. Let's just use string positions!

$pos1 = strpos($content, '<!-- COUNTRIES -->');
// find the closing </section> right before why-elite-section
$pos2 = strpos($content, '<section class="section why-elite-section"');

$countriesBlock = substr($content, $pos1, $pos2 - $pos1);

$pos3 = strpos($content, '<!-- PROCESS -->');
$pos4 = strpos($content, '<!-- STUDENT SUCCESS STORIES -->');

$processBlock = substr($content, $pos3, $pos4 - $pos3);

$content = str_replace($countriesBlock, '###PROCESS_BLOCK###', $content);
$content = str_replace($processBlock, '###COUNTRIES_BLOCK###', $content);

$content = str_replace('###PROCESS_BLOCK###', $processBlock, $content);
$content = str_replace('###COUNTRIES_BLOCK###', $countriesBlock, $content);

file_put_contents($file, $content);
echo "Swapped successfully.";
