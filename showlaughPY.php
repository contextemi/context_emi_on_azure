<?php
session_start();
$ident = $_SESSION['ident'];
$vid = $_SESSION['vid'];
$vname = $_SESSION['vname'];
$accessToken = $_SESSION['accessToken'];
    ##파이썬으로 nltk 실행하여 명사 추출 및 빈도수 체크하기
try{
    #나중에는 영상 이름을 받아오거나 순서를 부여할 예정
    $ret = escapeshellcmd("python3 /home/ubuntu/www/laugh/segment_laughter.py --filename ".$vid." --foldername ".$ident);
    $output = shell_exec($ret);

    if($output){
         ## result 전송
        $text = "";
        $row = 1;
        if(($handle = fopen('/home/ubuntu/www/laugh/'.$ident.'/'.$vid.'.csv','r')) !== FALSE){
            while(($data = fgetcsv($handle,1000,",")) !== FALSE){
                $num = count($data);
                

                for($c = 0; $c<$num ; $c++){
                    $text.=$data[$c].',';
                    $datas[$row] = $data[$c];
                }
                $row++;
            }
        }else{
            echo "no";
        }

        $result['success'] = true;
        $result['data'] = $datas;


   }else{
        $result['success'] = true;
        $result['data'] = '웃음 추출에 실패하였습니다.';
   }
}catch(exception $e){
    $result['success'] = false;
    $result['msg'] = $e->getMessage();
    $result['code'] = $e->getCode();
}finally {
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}

?>