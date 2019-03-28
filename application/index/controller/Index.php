<?php
namespace app\index\controller;

use think\Request;


class Index
{
  /**
   * 首页
   */
  public function index()
  {
    return view();
  }

  /**
   * 获取文件
   */
  public function getFile()
  {
    $time = time();

    switch ($_FILES['file']['type']) {
      case 'image/jpeg':
        $fix = 'jpeg';
        break;
      case 'image/png':
        $fix = 'png';
        break;
    }
    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads' . DS . $time . '.' . $fix);
    $str = $this->ocrapi($time . '.' . $fix);
    return json($str);
  }
  public function ocrapi($file_name = '1553501389.png')
  {
    require '../vendor/autoload.php';
    $aip = new \AipOcr('10834827', 'hvfbPZHmZ0G7sFPzUdxB6SZY', 'uH5RPQGNmZ2PGRpKcQKeTK7Yz6Dfqrg2');
    $url = 'uploads' . DS . $file_name;
    $image = file_get_contents($url);
    $options = array();
    $options["detect_direction"] = "true";
    $result = $aip->basicAccurate($image, $options);
    $str = '';
    foreach ($result['words_result'] as $k => $v) {
      $str .= $v['words'];
    }
    unlink($url);
    return $str;
  }
}
