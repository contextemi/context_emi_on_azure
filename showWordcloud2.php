	<?php 
	session_start();
	$ident = $_SESSION['ident'];
	$vid = $_SESSION['vid'];
	$vname = $_SESSION['vname'];
	$accessToken = $_SESSION['accessToken'];

	?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="https://d3js.org/d3.v3.min.js"></script>
	<script src="https://rawgit.com/jasondavies/d3-cloud/master/build/d3.layout.cloud.js" type="text/JavaScript"></script>
	<title>ContextCloud</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Anton|Bitter|Bree+Serif|Exo+2|Fjalla+One|Inconsolata|Libre+Baskerville" rel="stylesheet">

	<style>
		body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
		.w3-row-padding img {margin-bottom: 12px}
		.bgimg {
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			background-image: url('16948_temp.jpg');
			min-height: 100%;
		}
		div #video_player_box{ width:550px; background:#000; margin:0px auto;}
		div #video_controls_bar{ background: #333; padding:10px;}
		@font-face {
			font-family: 'Bitter', serif;
		}

		.center{
			width : 100%;
			text-align: center;
		}

		.wrap-loading{ /*화면 전체를 어둡게 합니다.*/

			position: fixed;

			left:0;

			right:0;

			top:0;

			bottom:0;

			background: rgba(0,0,0,0.2); /*not in ie */

			filter: progid:DXImageTransform.Microsoft.Gradient(startColorstr='#20000000', endColorstr='#20000000');    /* ie */

		}

		.wrap-loading div{ /*로딩 이미지*/

			position: fixed;

			top:50%;

			left:50%;

			margin-left: -21px;

			margin-top: -21px;

		}

		.display-none{ /*감추기*/

			display:none;

		}



	</style>

	<script>

	var scriptList = new Array();
	//세션에 저장된 정보 변수에 저장하기
	var ident = <?php echo json_encode($_SESSION['ident']); ?>; 
	var vid = <?php echo json_encode($_SESSION['vid']); ?>;
	var vname = <?php echo json_encode($_SESSION['vname']); ?>;
	var accessToken = <?php echo json_encode($_SESSION['accessToken']); ?>;

	//해당 영상의 스크립트 받아오기 -> 이 데이터를 이용하여 csv 파일 제작 
	$(function(){

		var params = {
			"format": "csv",
			"accessToken": accessToken,
		};

		$.ajax({
			url: "https://api.videoindexer.ai/trial/Accounts/"+ident+"/Videos/"+vid+"/Captions?" + $.param(params),	
			beforeSend: function(xhrObj){
			},
			type: "GET",
			dataType:"text"
		})
		.done(function(data) {
        	//window 전송 외에 post로 ajax를 이용하여 변수를 전달 할 수 있음.
        	var allRow = data.split(/\r?\n|\r/);

        	var textLine="";
        	// var script = "";

        	//csv로 받아온 파일 필요한 형태로 제작하기
        	for(var singleRow = 0; singleRow < allRow.length; singleRow++) {

        		var collapse = allRow[singleRow].split(",");
        		var data = new Object();

        		for(var count = 0; count < collapse.length; count++) {

					//시작시간 받아오기
					if(count == 1){
						mm = collapse[count].split(":");
						nn = parseInt(mm[1]);
						if(nn > 0){
							nn = nn*60;
							a = nn + parseInt(mm[2]);
							data.start_second = ""+a;
						}else{
							b = parseInt(mm[2]);
							data.start_second = ""+b;
						}
					}

					//스크립트 받아오기 
					if (count == 3){
						s = collapse[count];
						script = s.replace('"',"");
						textLine += script;
						textLine += "\t";
						data.script = script;
					}
				}

				scriptList.push(data);
			}

			var jsonData = JSON.stringify(scriptList);

			$('<source/>',{
				src:"http://ec2-13-125-14-92.ap-northeast-2.compute.amazonaws.com/"+ident+"/"+vid+".mp4"
			}).appendTo('#my_video');

			$('#output').val(textLine);
			saveText(textLine);


		})
		.fail(function() {
			alert('error');
		});



	});

	//csv 파일 생성하기 
	function saveText(text){

		//이벤트 전달 제거하기 
		var saveBtn = document.createElement('button');
		var saveBtnText = document.createTextNode('showD3');
		saveBtn.appendChild(saveBtnText);

		$.ajax({
			url:'http://ec2-13-125-14-92.ap-northeast-2.compute.amazonaws.com/saveText.php',
			method:'POST',
			data:{
				param:text,
				vname: vname,
				vid:vid,
				ident :ident
			}
		})
		.done(function(data){
			let D3wordcloud=showD3();
			D3wordcloud();
			// $('<input/>',{
			// 	type:'button',
			// 	value:'showD3',
			// 	onclick:'showD3(); this.onclick=";"',
			// 	id:'showD3'
				
			// }).appendTo('body');

		})
		.fail(function(){
			alert('fail');
		});
	}


	//video 관련 함수
	function playPause(btn,vid){
		var vid = document.getElementById(vid);
		if(vid.paused){
			vid.play();
			btn.innerHTML = "Pause";
		} else {
			vid.pause();
			btn.innerHTML = "Play";
		}
	}

	function gotoTime(btn,vid,time){
		var vid = document.getElementById(vid);
		if(vid.paused){
			vid.currentTime = time;
		} else {
			vid.currentTime = time;
		}
	}


	//웃음 추출해오는 함수 php -> python 경로
	function showlaughTime(){

		$.ajax({
			url:'http://ec2-13-125-14-92.ap-northeast-2.compute.amazonaws.com/showlaughPY.php',
			method:'GET',
			timeout : 70000,
			dataType : 'json'
			,success: function(data){
				//조회 성공일 때 처리할 것 : 버튼 추가하기
				$('.timeStampBtn').remove();
				$.each(data['data'],function(index,item){

					$('<input/>',{
                		type:'button',
                		value:""+item,
                		onclick:'gotoTime(this,"my_video",'+item+')',
                		class:'timeStampBtn btn btn-outline-secondary',
                	}).appendTo('#funny_bar');
				});
			}
			,beforeSend : function(){
				$('.wrap-loading').removeClass('display-none');
			}
			,complete : function(){
				$('.wrap-loading').addClass('display-none');
			}
			,error : function (e){
				//데이터 로딩 실패시 
				alert('error');
			}
		});
		
	}


</script>
</head>


<body>

	
	<!-- Sidebar with image -->
	<nav class="w3-sidebar w3-hide-medium w3-hide-small" style="width:50%;background-color:black">
		<div class="bgimg">
			<div id="video_player_box" class="center" style="padding-top:150px; background-color:transparent;">
				<div style="background-color:rgba(0,0,0,1);">
					<video id="my_video" width="550" height="300" autoplay>
					</video>
					<div id="video_controls_bar" style = "background-color:transparent">
						<button id="playpausebtn" class="btn btn-danger" onclick="playPause(this,'my_video')">Pause</button>
						<button id="showlaughTime" class="btn btn-warning" onclick="showlaughTime()">FUNNY TIME</button>
					</div>
					<div id="funny_bar">
					</div>
				</div>
			</div>       

		</div>
	</nav>

		<!-- Page Content -->
		<div class="w3-main w3-padding-large" style="margin-left:50%">
			<img src="/w3images/profile_girl.jpg" class="w3-image w3-hide-large w3-hide-small w3-round" style="display:block;width:60%;margin:auto;">
			<img src="/w3images/profile_girl.jpg" class="w3-image w3-hide-large w3-hide-medium w3-round" width="1000" height="1333">
			<!-- <button class="w3-button w3-light-grey w3-padding-large w3-margin-top" id='save_button' onclick='saveText(); this.onclick=";"'>
				<i class="fa fa-download"></i> SaveFile
			</button> -->
			<!-- Header -->
			<header class="w3-container w3-center" id="home">
				<h3 class="w3-jumbo"><b>ContextCloud</b></h3>
				<p></p>
			</header>
			<div id="cloudBtn" class="center">
				
			</div>
			<p>
				<div class="center">
					<textarea name=text id="output" cols="60" rows="10"> </textarea>
					<br>
					<!-- <div id="btn_parent">
						<input type="submit" value=save id='save_button' onclick='saveText(); this.onclick=";"'>
					</div> -->

					<div class="wrap-loading display-none">
						<div><img src="./image/Spinner.gif" /></div>
					</div> 






	<script>

		/*d3 관련 변수 및 함수*/
		//scale.linear: 선형적인 스케일로 표준화를 시킨다. 
		//domain: 데이터의 범위, 입력 크기
		//range: 표시할 범위, 출력 크기 
		//clamp: domain의 범위를 넘어간 값에 대하여 domain의 최대값으로 고정시킨다.
		function showD3(){
			
			var width = 560,height = 500

			var svg = d3.select("header").append("svg")
			.attr("width", width)
			.attr("height", height);

			d3.csv("./"+ident+"/"+vid+".csv", function (data) {
				showCloud(data)
				setInterval(function(){
					showCloud(data)
				},9000) 
			});

			var keywords = ["war"]
			var svg = d3.select("svg")
			.append("g")
			.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")")

			wordScale = d3.scale.linear().domain([0, 100]).range([0, 150]).clamp(true);

			function showCloud(data) {
				d3.layout.cloud().size([width, height])
                //클라우드 레이아웃에 데이터 전달
                .words(data)
                .rotate(function (d) {
                	return d.text.length > 3 ? 0 : 90;
                })
                //스케일로 각 단어의 크기를 설정
                .fontSize(function (d) {
                	return wordScale(d.frequency);
                })
                //클라우드 레이아웃을 초기화 > end이벤트 발생 > 연결된 함수 작동  
                .on("end", draw)
                .start();

                function draw(words) { 
                	var cloud = svg.selectAll("text").data(words)
                //Entering words
                cloud.enter()
                .append("text")
                .style("font-family", "Bitter")
                .style("fill", function (d) {
                	return (keywords.indexOf(d.text) > -1 ? "#fbc280" : "#405275");
                })
                .style("fill-opacity", .5)
                .attr("text-anchor", "middle") 
                .attr('font-size', 2)
                .text(function (d) {
                	return d.text;
                }).
                on('click',function(d){
                	
                	$('.timeStampBtn').remove();

                	var secondList
                	// 	//filter 사용하고 싶었는데..실패험 ㅠㅠ

                	for(count = 0; count<scriptList.length; count++){
                		var rescript = scriptList[count].script.toLowerCase();
                		if(rescript.indexOf(d.text.toLowerCase())!=-1){
                			//버튼 만들기
                			$('<input/>',{
                				type:'button',
                				value:''+scriptList[count].start_second,
                				onclick:'gotoTime(this,"my_video",'+scriptList[count].start_second+')',
                				class:'timeStampBtn btn btn-outline-success'
                			}).appendTo('#cloudBtn');
                		}
                	}

                }); 
                cloud
                .transition()
                .duration(800)
                .style("font-size", function (d) {
                	return d.size + "px";
                })
                .attr("transform", function (d) {
                	return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
                })
                .style("fill-opacity", 1); 
            }
        }
    }
</script>
<p>
	<p>
		<!-- Footer -->
		<footer class="w3-container w3-padding-64 w3-light-grey w3-center w3-opacity w3-xlarge" style="margin:0px">
			<i class="fa fa-facebook-official w3-hover-opacity"></i>
			<i class="fa fa-instagram w3-hover-opacity"></i>
			<i class="fa fa-snapchat w3-hover-opacity"></i>
			<i class="fa fa-pinterest-p w3-hover-opacity"></i>
			<i class="fa fa-twitter w3-hover-opacity"></i>
			<i class="fa fa-linkedin w3-hover-opacity"></i>
			<p class="w3-medium">Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank" class="w3-hover-text-green">context.3</a></p>
			<!-- End footer -->
		</footer>

		<!-- END PAGE CONTENT -->
	</div>


<script>
// Open and close sidebar

function openNav() {
	document.getElementById("mySidebar").style.width = "60%";
	document.getElementById("mySidebar").style.display = "block";
}

function closeNav() {
	document.getElementById("mySidebar").style.display = "none";
}
</script>

</body>
</html>