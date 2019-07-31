<?php
if(isset($_SESSION["user_situation"]) == false) {
	echo("<script>alert('먼저 로그인부터 하세요.')</script>");
	echo("<script>location.replace('/main/index');</script>");
}
?>
<div class="searching-grid-background"></div>
<div class="container">
	<div class="content-grid row">
		<div class="col-sm">


			<form>
				<div class="form-group">
					<label for="inputState">플랫폼</label>
					<select class="form-control" name="platfrom" id="platform">
							<option disabled selected>Choose...</option>
							<option value="steam">스팀(Steam)</option>
							<option value="origin">오리진(Origin)</option>
							<option value="battlenet">블리자드 배틀넷(Blizzard Battle.net)</option>
					</select>
				</div>
				<div class="form-group">
			    <label for="exampleFormControlFile1">계정 스크린샷(5mb이하)</label>
			    <input type="file" class="form-control-file" name="img" id="exampleFormControlFile1">
			  </div>
				<div class="input-group account-explain-group">
				  <div class="input-group-prepend">
				    <span class="input-group-text">계정설명</span>
				  </div>
				  <textarea class="form-control account-explain" name="explain" id="explain" aria-label="With textarea"></textarea>
				</div>
				<div class="alert alert-success" id="account_price_expect" role="alert">
					<div id="account_price_expect">
						<span>계정 추정가격 : 정해지지 않음.</span>
					</div>
				</div>
				<p class="game-calc-p" id="game_check"><a class="game-calc" data-toggle="modal" href="#steamGameList">내 게임목록 조회하기</a></p>
				<label for="exampleInputPassword1">판매가 지정</label>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
					<span class="input-group-text">₩</span>
					</div>
					<input type="text" class="form-control price-form" name="price" id="price" aria-label="Amount (to the nearest dollar)">
				</div>
				<p>
					<a class="pay-type" data-toggle="collapse" href="#bz-pay" role="button" aria-expanded="false" aria-controls="collapseExample">
					번개장터로 결재하기
					</a>
					<a class="pay-type" data-toggle="collapse" href="#personal-pay" role="button" aria-expanded="false" aria-controls="collapseExample">
					연락처로 결재하기
					</a>
				</p>
				<div class="collapse" id="bz-pay">
					<div class="form-group">
					<label for="exampleInputPassword1">번개장터 링크</label>
					<input type="text" class="form-control bz-form" name="payway_bz_link" id="bz_link" placeholder="이곳에 번개장터 링크를 붙혀놓으세요.">
					</div>
				</div>

				<div class="collapse" id="personal-pay">
					<div class="form-group">
					<label for="exampleInputPassword1">연락처</label>
					<input type="text" class="form-control bz-form" name="payway_tel" id="tel" placeholder="이곳에 카카오톡 아이디를 기재하세요.">
					</div>
				</div>

				<div id="invisible-form">
					<input type="hidden" name="steamlink" value="">
					<input type="hidden" name="game_list" value="">
					<input type="hidden" name="game_count" value="">
				</div>

				<button type="button" class="btn btn-primary" onclick="sell_submit();">등록완료</button>
			</form>


		</div>
		<div class="col-sm">
			<div class="game-count">
				<p class="utill-border">Games</p>
				<!--
				<p>PLAYERUNKNOWS BATTLEGROUND</p>
				<p>GTA V</p>
				<p>Call of duty Modern Warfare</p>
				<p>Day by daylight</p>
				-->
				<div class="game_list" id="steam_game_list">
					<p>아직 게임목록이 조회되지 않았습니다.</p>
				</div>
				<script type="text/javascript">
				function game_scraping() {

						function addComma(num) {
						  var regexp = /\B(?=(\d{3})+(?!\d))/g;
						  return num.toString().replace(regexp, ',');
						}

							var queryString = $("#steam_profile").serialize();
							alert(queryString);
									$.ajax({
										url : '/process/scraping',
										data			: {
											param1		: queryString
										},
										type			: 'POST',
										dataType		: 'json',
										success: function(result) {
											if(result.success == false) {
												console.log('failed.');
										}
										  // var steamlink = JSON.parse(result.steamlink);

											var game_list = JSON.parse(result.data);
											// console,log(gmae_list);
											var game_count = Object.keys(game_list).length;
											// json문자열을 javascript 오브젝트로 반환

											$('#steamGameListModalClose').trigger('click');
											$("#steam_game_list *").remove();
											$("#account_price_expect *").remove();
											// $("#game_check").remove();

											var php_array_main='';

											game_list.forEach(function(row){
													// console.log(row.name);
													function playtime() {
														if (row.hours_forever == undefined) {
															return '0h';
														} else {
															return row.hours_forever+'h';
														}
													}
								          var tmpHtml = '<p>'+row.name+'<span class="play-time">'+playtime()+'</span>'+'</p>';
								          $("#steam_game_list").append(tmpHtml);
													var php_array_input = row.name+';';
													php_array_main = php_array_main+php_array_input;
											});

											$("#account_price_expect").append('<span>계정 추정가격 : '+addComma(game_count*2000)+'₩ ~ '+addComma(game_count*2500)+'₩</span>');

										  game_list_out = php_array_main;
											game_count_out = game_count;
											// var value = $("#price").val();

											// console.log(game_count_out);
											// console.log(game_list_out);

											// this['inputname'].value
											$("#invisible-form *").remove();
											var formHtmlOne = "<input type='hidden' name='steamlink' value='test'>";
											var formHtmlTwo = "<input type='hidden' name='game_count' value='"+game_count+"'>";
											var formHtmlThree = "<input type='hidden' name='game_list' value='"+game_list+"'>";
											$("#invisible-form").append(formHtmlOne);
											$("#invisible-form").append(formHtmlTwo);
											$("#invisible-form").append(formHtmlThree);
										},

										error: function(request, status, error) {
								        console.log(request.responseText);
								    }

									});
								}
						function sell_submit() {

							var platform = $("#platform option:selected").val();
							// var platform = $("select[name='platform']").val();
							var explain = $("#explain").val();
							var price = $("#price").val();
							var bz_link = $("#bz_link").val();
							var tel = $("#tel").val();
							var game_count = game_count_out;
							var game_list = game_list_out;

							console.log(game_count);
							console.log(game_list);
							console.log('플랫폼 : '+platform);
							console.log('계정설명 : '+explain);
							console.log('계정가격 : '+price);
							console.log('번장링크 : '+bz_link);
							console.log('연락처 : '+tel);

							$.ajax({
								url : '/process/item_upload',
								data			: {
									param1		: platform,
									param2		: explain,
									param3		: price,
									param4		: bz_link,
									param5		: tel,
									param6		: game_count,
									param7		: game_list
								},
								type			: 'POST',
								dataType		: 'text',
								success: function(result) {
									if(result.success == false) {
										console.log('failed.');
								}
								alert(result.data);
								},
								error: function(request, status, error) {
										console.log(request.responseText);
								}
							});
						}
				</script>
			</div>
		</div>
	</div>
</div>
