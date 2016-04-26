<?php

$jsall =  dirname(__DIR__).'/js/all.js';
$jspath = dirname(__DIR__).'/js/source/';

$cssall =  dirname(__DIR__).'/css/all.css';
$csspath= dirname(__DIR__).'/css/source/';

$yuicomp = __DIR__.'/vendor/limp/doc/min/yc.jar';


//ROUTER (kkkk)
$rqst = $argv;
array_shift($rqst);
$ax = $rqst;
foreach($rqst as $a){
	array_shift($ax);
    if(strpos($a, 'config') !== false) goto config;
    if(strpos($a, 'run') !== false) goto run;
    if(strpos($a, 'min') !== false) goto min;
    if(strpos($a, 'join') !== false) goto join;
}

exit("\nCompressor\n\nUsage: php mini.php <config || run>\n\n\tconfig: gera somente a configuracao em 'mini.config.php'\n\trun:    executa a compressao configurada.\n\n");


/**
 * Create config for furter...
 */

config:

//CSS ---------------------------------------------------------------------------


$files = '<?php

$jsall =  "'.$jsall.'"; //caminho de arquivos de origem
$jspath = "'.$jspath.'"; //caminho do arquivo de destino

$cssall =  "'.$cssall.'"; //caminho de arquivos de origem
$csspath= "'.$csspath.'"; //caminho do arquivo de destino

$yuicomp = "'.$yuicomp.'"; //Localização do compressor 

# StyleSheet files:
# Modifique a ordem dos arquivos ou comente-os (// para não compactar) conforme a necessidade
';
$dir = scandir($csspath);
$c = 0;
foreach ($dir as $file) {
	if($file == '.' || $file == '..') continue;
	$c ++;
	$files .= "\n".'$cssfiles['.$c.'] = \''.$csspath.$file.'\';';	
}


//JAVASCRIPT --------------------------------------------------------------------
$files .= '

# Javascript files:
# Modifique a ordem dos arquivos ou comente-os (// para não compactar) conforme a necessidade
';

$dir = scandir($jspath);
$c = 0;
foreach ($dir as $file) {
	if($file == '.' || $file == '..') continue;
	$c ++;
	$files .= "\n".'$jsfiles['.$c.'] = \''.$jspath.$file.'\';';
}

$files .= '

# © Mini for Bill Rocha - created in '.date('d/m/Y H:i:s');

file_put_contents(__DIR__.'/mini.config.php', $files);
exit("\n\tConfiguracao gerada no arquivo 'mini.config.php' com sucesso!");


/**
 * Run config based minification
 */

run:

$cssfiles = [];
$jsfiles  = [];

include __DIR__.'/mini.config.php';

ksort($cssfiles);
ksort($jsfiles);

//STYLE SHEETS ------------------------------------------------------------------
$content = '';
foreach ($cssfiles as $file) {
	$content .= exec('java -jar '.$yuicomp.' '.$file);	
	echo "\nfile: ".$file;
}

@rename($cssall, $cssall.'.'.date('Ymd_Hisu').'.css');
file_put_contents($cssall, $content);
echo "\n\nFinished Style Sheet files\nin: ".$cssall."\n\n";

//JAVASCRIPT --------------------------------------------------------------------
$content = '';
foreach ($jsfiles as $file) {
	$content .= exec('java -jar '.$yuicomp.' '.$file);	
	echo "\nfile: ".$file;
}

rename($jsall, $jsall.'.'.date('Ymd_His').'.js');
file_put_contents($jsall, $content);
exit("\n\nFinished JavaScript files\nin: ".$jsall."\n\n");


/**
 * Minificação de apenas um arquivo
 */

min:

if(!isset($ax[0]) && !isset($ax[1])) exit("\n\tError: Indique o arquivo original e o destino!\n\n");
if(!file_exists($jspath.$ax[0])) exit("\n\tError: Arquivo de origem ($jspath$ax[0]) não existe!\n\n");
if(is_writable($jspath.$ax[1])) exit("\n\tError: Não consigo escrever no diretório ($jspath$ax[1]) de destino!\n\n");

//executando
$content = exec('java -jar '.$yuicomp.' '.$jspath.$ax[0]);
if(file_put_contents($jspath.$ax[1], $content)) exit("\nSUCCESS!\n\n");
else exit("\n\tError: Não consegui escrever o arquino de origem!\n\n");


/**
 * Join files 
 *
 * @param string lista de arquivos a ser juntados, SEPARADOS por vírgula (importante)
 * @param string arquivo de destino
 */
join:

if(!isset($ax[0]) && !isset($ax[1])) exit("\n\tError: Indique os arquivos a ser juntados e o destino!\n\n");
if(is_writable($jspath.$ax[1])) exit("\n\tError: Não consigo escrever no diretório ($jspath$ax[1]) de destino!\n\n");

$f = explode(',', $ax[0]);
$content = '';
foreach($f as $file){
	$content .= file_get_contents($jspath.trim($file));
}

if(file_put_contents($jspath.$ax[1], $content)) exit("\nSUCCESS!\n\n");
else exit("\n\tError: Não consegui escrever o arquino de origem!\n\n");