<script src="<?php echo base_url('public/vue/vue@3.2.2'); ?>" crossorigin="anonymous"></script>
<style type="text/css">
	body { 
		background: url("<?php echo base_url('public/images/bg.jpg'); ?>") repeat; 
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
</style>
<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%;width:98%" id="mainDiv">
	<div class="card-body">
		<div class="row"><div class="col-lg-12">{{questionText}}</div></div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12" align="center" style="margin:1%">
				<button :disabled='cantAnswer' v-on:click="toAnswerQuestion" class="btn btn-sm border-dark">{{toAnswer}}</button>&nbsp;<button :disabled='cantfollow' v-bind:class="{'btn btn-sm border-dark text-dark':notFollowing,'btn btn-sm bg-dark text-white':following}" v-on:click="toFollowQuestion" >{{followingText}}</button>&nbsp;<button class="btn btn-sm border-dark text-dark" v-on:click="toViewHeadingsOfQuestion">தலைப்புகள்</button>&nbsp;<button class="btn btn-sm border-dark text-dark">{{followersCount}}&nbsp;பின்தொடர்பவர்(கள்)</button>
			</div>
		</div>
		<div class="row"><div class="col-lg-12">{{answersCountText}}</div></div>
		<div class="row"><div class="col-lg-12">
			<ol style="list-style:none;">
				<mycomponent
				v-for="answer in answers"
				v-bind:myelement="answer"
				v-bind:key="answer.answerid"
				></mycomponent>
			</ol>
		</div></div>
	</div>
</div>

<script type="text/x-template" id="idForTemplate"><div class="card border-dark"><div class="card-header bg-dark text-white"><a v-on:click="toWriterProfile(myelement.userid)">{{myelement.answeredUsername}}</a>&nbsp;{{myelement.answeredTime}}&nbsp;அன்று பதிலளித்தார்</div><div v-on:click="clickToAnswer(myelement.answerid)" class="card-body" v-html="myelement.answerTextBrief"></div></div></script>

<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				answers: [],
				questionText:"",
				toAnswer:"உள்நுழையாமல் பதிலளிக்க முடியாது",
				followingText:"உள்நுழையாமல் பின்தொடர முடியாது",
				cantFollow:true,
				cantAnswer:true,
				answersCountText:"",
				following:false,
				notFollowing:true,
				followersCount:"0"
			}
		},
		methods:{
			toFollowQuestion:function(){
				var innerThis=this;
				let followData=new FormData();
				followData.append("questionID","<?php echo $_GET['questionid']; ?>");
				followData.append("loggedInUserName","<?php echo $username; ?>");
				fetch("<?php echo base_url('index.php/question/followThisQuestion'); ?>",{
					method:'POST',
					body: followData,
					mode: 'no-cors',
					cache: 'no-cache'})
				.then(res => { return res.json(); })
				.then(data => { 
					alert(data.message);
					if (innerThis.following) { innerThis.following=false;  innerThis.notFollowing=true; innerThis.followingText="பின்தொடர"; }
					else{ innerThis.following=true; innerThis.notFollowing=false; innerThis.followingText="பின்தொடர்கிறீர்கள்"; }
				})
				.catch(err => console.log(err));
			},
			toAnswerQuestion:function(){
				window.location.href="<?php echo base_url('index.php/answer/toAddAnswer?questionID='.$_GET['questionid']); ?>";
			},
			toViewHeadingsOfQuestion:function(){
				window.location.href="<?php echo base_url('index.php/heading/headingsOfQuestion?questionID='.$_GET['questionid']); ?>";
			},
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					innerThis.questionText=data['questionText'];
					innerThis.followersCount=data['followersCount'];
					var loggedInUserName="<?php echo $username; ?>";
					
					var answersBrief=JSON.parse(data['encodedAnswers']);
					var answeredUsernames=[];
					answersBrief.forEach(function(item){
						innerThis.answers.push({"answerid":item.answerid,"answerTextBrief":item.answerTextBrief+"...","answeredUsername":item.firstname,"userid":item.userid,"answeredTime":item.answercreatedon});
						answeredUsernames.push(item.authorUsername);
					});
					
					if (loggedInUserName===data['username']) {
						innerThis.toAnswer="கேட்டவர் பதிலளிக்க முடியாது";
						innerThis.followingText="கேட்டதனால் பின்தொடர்கிறீர்கள்";
					}
					else{
						if (answeredUsernames.includes(loggedInUserName)) {
							innerThis.toAnswer="பதிலளித்துவிட்டீர்கள்";
							innerThis.cantAnswer=true;
						}
						else{
							innerThis.toAnswer="பதிலளிக்க";
							innerThis.cantAnswer=false;
						}
						if (data['followingStatus']=="No") {
							innerThis.cantFollow=false;
							innerThis.followingText="பின்தொடர";
							innerThis.following=false;
							innerThis.notFollowing=true;
						}
						else{
							innerThis.cantFollow=false;
							innerThis.followingText="பின்தொடர்கிறீர்கள்";
							innerThis.following=true;
							innerThis.notFollowing=false;
						}
					}
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/question/deriveQuestionWithAnswers?questionID='.$_GET['questionid'].'&loggedInUserName='.$username); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement','answerid','userid'],
		template: "#idForTemplate",
		methods : {
			clickToAnswer : function(answerid) {
				window.location.href="<?php echo base_url('index.php/answer/viewSingleAnswerWithQuestion?answerid='); ?>"+answerid;
			},
			toWriterProfile : function(userid){
				window.location.href="<?php echo base_url('index.php/user/singleuser?userid='); ?>"+userid;
			}
		},
		mounted(){}
	});

	app.mount('#mainDiv')
</script>