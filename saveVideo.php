<?php
    ##로그인한 계정에 업로드된 영상을 다운받도록 하기 
try{
    $key = $_POST['param']; #받아온 key값 

    if(!$key){
        throw new exception('text 값이 없습니다.');
    }else{
        #해당 파이썬 파일을 이용하여 계정의 비디오를 다운받게함
 
        $ret = escapeshellcmd("python3 /home/ubuntu/www/Get_video_url_v6.py ".$key);
        $output = exec($ret);

        if($output){
            ## result 전송
            # 생성된 json 파일 읽어 리턴하기
            $str = file_get_contents($output);
            $videoData = json_decode($str, true);
            
            $result['success'] = true;
            $result['data'] = $videoData['videoDict'];
            $result['ident'] = $videoData['ident'];
            $result['accessToken'] = $videoData['accessToken'];

        }else{
         $result['success'] = true;
         $result['data'] = '비디오 생성이 실패하였습니다.';
        }  

 }
}catch(exception $e){
    $result['success'] = false;
    $result['msg'] = $e->getMessage();
    $result['code'] = $e->getCode();
}finally {
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}

?>
