<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

try{

    $text = $_POST['param'];
    $vid = $_POST['vid'];
    $vname = $_POST['vname'];
    $ident = $_POST['ident'];

    if(!$text){
        throw new exception('text 값이 없습니다.');
    }

    ##파일 저장하고 파이썬 실행하기
    ## step1.파일 저장하기 -> 서버에 저장
    $target_dir = "/home/ubuntu/www/".$ident; //업로드 되게 될 타겟 디렉터리
    $target_file = fopen($target_dir."/".$vid.".txt","w");
    fwrite($target_file, $text);
    fclose($target_file);

    ##파이썬으로 nltk 실행하여 명사 추출 및 빈도수 체크하기
    echo "Current user is: " . get_current_user();
    $ret = escapeshellcmd("python3 /home/ubuntu/www/saveCSV.py --ident ".$ident." --output ".$vid);
    $output = shell_exec($ret);
    echo $output;
     
     if($output){
         ## result 전송
         $result['success'] = true;
         $result['data'] = '스크립트 빈도수 추출 및 파일 생성이 완료 되었습니다.';
     }else{
         $result['success'] = true;
         $result['data'] = '스크립트 빈도수 추출 및 파일 생성이 실패하였습니다.';
     }



}catch(exception $e){
    $result['success'] = false;
    $result['msg'] = $e->getMessage();
    $result['code'] = $e->getCode();
}finally {
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}

?>


