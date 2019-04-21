<?php  
session_start(); 
$_SESSION = array();
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Anton|Bitter|Bree+Serif|Exo+2|Fjalla+One|Inconsolata|Libre+Baskerville" rel="stylesheet">

<head>

	<title>welcome Page</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<style>

		body,h1 {
			font-family: "Raleway", sans-serif
		}
		
		body, html {
			height: 100%
		}
		
		.bgimg {

			background-image: url('16948_temp.jpg');
			min-height: 100%;
			background-position: center;
			background-size: cover;
		}

		.center{
			width : 100%;
			text-align: center;ehd
			
		}

		.back{
			padding: 40px;
			background-color: #ffffff;
			background-color: rgba( 255, 255, 255, 0.5 )
		}

		.wrap-loading{ /*화면 전체를 어둡게 합니다.*/

			position: fixed;
			left:0;
			right:0;
			top:0;
			bottom:0;
			background: rgba(255,255,255); /*not in ie */
			border-radius: 10px;
			filter: progid:DXImageTransform.Microsoft.Gradient(startColorstr='#20000000', endColorstr='#20000000');    /* ie */
		}

		.wrap-loading div{ /*로딩 이미지*/

			margin:0 auto;
		}

		.display-none{ /*감추기*/
			display : none;
		}
		
		.cen{
			display: table-cell;
			text-align: center;
			vertical-align: middle;
			text-align: center;
		}

		.fontfont{
			font-family: 'Bitter', serif;
		}

		input[type=text]:focus {
			border: 3px solid #555;
		}

		input[type=text] {
			width: 500px;
			text-align: center;
		}

		.fn2{
			position: absolute;
			top: 50%;
			left: 50%;
			text-align: center;

		}


	</style>

	<script type="text/javascript">

		function sendKey(){
			$.ajax({
				url:'http://ec2-13-125-14-92.ap-northeast-2.compute.amazonaws.com/saveVideo.php',
				method:'POST',
				data : {
					param : $('#ocpKey').val()
				},
				dataType : 'json'
				,success: function(data){
				//조회 성공일 때 처리할 것 : 버튼 추가하기
				$.each(data['data'],function(key,item){
					//요소를 추가하기
					
					$('<div />',{
						id:item+'Con',
						class: 'thumbnailContainer col-sm'
					}).appendTo('#thumbnailDIV');

					$('<img />', {
						src: "./"+data['ident']+'/'+key+'.jpg',
						width: 250,
						height: 150,
						id : item,
						class : 'thumbnail cen'
					}).appendTo('#'+item+'Con');

					$('<input/>',{
						type:'button',
						value : item,
						onclick:'showService("'+key+'","'+item+'","'+data['ident']+'","'+data['accessToken']+'")',
						class:"btn btn-dark cen",
						id:item+"Btn",
					}).appendTo('#'+item+'Con');
				});

				//Login text 내용 변경하기
				$('#login_text').html('<h1 class="w3-jumbo w3-animate-top fontfont" id="login_text">VIDEO</h1>');
				$('.back').removeClass('back')


			}
			,beforeSend : function(){
				$('.wrap-loading').removeClass('display-none');
			}
			,complete : function(){
				$('.form-key').addClass('display-none');
				$('.wrap-loading').addClass('display-none');
			}
			,error : function (e){
				//데이터 로딩 실패시 
				alert("error");
			}
		});

		}

		function showService(vid,vname,ident,accessToken){
			
			$.ajax({
				url:'http://ec2-13-125-14-92.ap-northeast-2.compute.amazonaws.com/showService.php',
				method:'POST',
				data : {
					ident : ident,
					vid: vid,
					vname : vname,
					accessToken : accessToken
				}
				,success: function(data){
					
				}
				,error : function (e){
				//데이터 로딩 실패시 
				console.log(e);
			}
		});

			window.location.href = "http://ec2-13-125-14-92.ap-northeast-2.compute.amazonaws.com/showWordcloud2.php";

		}

	</script>
</head>
<body>
	<div class="bgimg w3-display-container w3-animate-opacity w3-text-black" >
		<div class="w3-display-topleft w3-padding-large w3-xlarge">
			Contextcloud
		</div>
		<div class="w3-display-middle back">
			<center><h1 class="w3-jumbo w3-animate-top fontfont" id="login_text">Login</h1></center>
			<hr class="w3-border-grey" style="margin:auto;width:50%">
			<br>
			<div id="thumbnailDIV" class="row">
			</div>

			<p class="w3-large w3-center"></p>
			<div class='center'>
				<form class ="form-key"> 
					<input type='text' name='ocp-apim' id='ocpKey' placeholder="  what's your Ocp-Apim-Subscription-Key?  " class="form-control"/> 
					<br>
					<input type='button' value='SEND' id='sendBtn' class="btn btn-dark" onclick="sendKey()" > 
				</form>

				<div class="wrap-loading display-none ">
					<br>
					<h2 class="fontfont">GET VIDEO...</h2>
					<div><img src="./image/747.gif" /></div>
				</div> 
			</div>  
		</div>
		<div class="w3-display-bottomleft w3-padding-large">
			Powered by Context_3
		</div>
	</div>
</body>
</html>

