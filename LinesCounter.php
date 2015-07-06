<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2015/7/6
 * Time: 18:13
 */
function countOneFile($file)
{
    $total = 0;
    if (!$fp = fopen($file, "rb")) {
        return false;
    }
    $code = fread($fp, filesize($file));
    fclose($fp);

    //Note : the following regular expression has a bug
    /**
     * <?php echo "<?php echo 'hello world'; ?>" ?>
     */
    preg_match_all("/<\?php(.*?)\?>/is", $code, &$match);
    foreach ($match[0] as $v) {
        $total += substr_count($v, "\n") + 1;
    }
    return $total;
}
$dir = opendir('./');
$totalNumber=0;
$thisFileNumber;
while(false !== ( $file = readdir($dir)) ) {
    if ( $file[0] != '.' ) {
        echo $file;
        $thisFileNumber = countOneFile($file);
        $totalNumber+=$thisFileNumber;
        echo $thisFileNumber.'<Br>';
    }
}
echo "totalLineNumber:".$totalNumber;

?>