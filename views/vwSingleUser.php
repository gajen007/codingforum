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
	<div class="card-header bg-dark text-white" align="center">{{nameOfTargetUser}}</div>
    <div class="card-body">

		<div class="row" style="margin-top:1%">
			<div class="col-lg-12" align="center">
				<img :src=avatarURL class="img-thumbnail" style="border-radius: 50%; border:solid 1px;" />
			</div>
		</div>

		<div class="row" style="margin-top:1%">
			<div class="col-lg-4 col-md-4 col-sm-12" align="center"><button class="rounded-pill btn btn-sm border-dark" v-on:click="questionsByUser"><i class="far fa-question-circle"></i>&nbsp;{{questionsCount}}&nbsp;கேள்வி(கள்)</button></div>
			<div class="col-lg-4 col-md-4 col-sm-12" align="center"><button class="rounded-pill btn btn-sm border-dark" v-on:click="answersByUser"><i class="fas fa-marker"></i>&nbsp;{{answersCount}}&nbsp;பதில்(கள்)</button></div>
			<div class="col-lg-4 col-md-4 col-sm-12" align="center"><button disabled class="rounded-pill btn btn-sm border-dark"><i class="fas fa-users"></i>&nbsp;{{followersCount}}&nbsp;பின்தொடர்வோர்</button></div>
		</div>
		
		<div class="row" style="margin-top:1%">
			<div class="card border-dark">
				<div class="card-body">
				<p v-html="aboutMe"></p>
				</div>
			</div>
		</div>

		<div class="row" style="margin-top:1%">
			<div class="col-lg-6 col-md-6 col-sm-12" align="center">
				<button v-bind:class="{'btn btn-sm border-dark text-dark':notFollowing,'btn btn-sm bg-dark text-white':following}" v-on:click="toFollowUser"><i class="fas fa-rss-square"></i>&nbsp;{{followingText}}</button>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12" align="center">
				<a target="_blank" href="<?php echo base_url('index.php/message/withUser?userid='.$_GET['userid']); ?>"><button class="btn btn-sm border-dark text-dark"><i class="far fa-envelope"></i>&nbsp;மடல்</button></a>
			</div>
		</div>
    </div>
</div>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				nameOfTargetUser:"",
				notFollowing:true,
				following:false,
				followingText:"பின்தொடர",
				questionsCount:"0",
				answersCount:"0",
				followersCount:"0",
				aboutMe:"",
				avatarURL:"<?php echo base_url('images/userAvatars/'); ?>"
			}
		},
		methods:{
			toFollowUser:function(){
				var innerThis=this;
				let followData=new FormData();
				followData.append("starID","<?php echo $_GET['userid']; ?>");
				followData.append("loggedInUserName","<?php echo $username; ?>");
				fetch("<?php echo base_url('index.php/user/followThisUser'); ?>",{
					method:'POST',
					body: followData,
					mode: 'no-cors',
					cache: 'no-cache'})
				.then(response => {
					if (response.status == 200) {
						return response.json();            
					}
					else {
						alert('Backend Error..!');
						console.log(response);
					}
				})
				.then(data => {
					alert(data.message);
					if (innerThis.following){ innerThis.following=false; innerThis.followingText="பின்தொடர"; innerThis.notFollowing=true; }
					else{ innerThis.following=true; innerThis.followingText="பின்தொடர்கிறீர்கள்"; innerThis.notFollowing=false; }
				})
				.catch(() => {
					(console.log("Network connection error"));
					alert("Reloading"); window.location.reload();
				});
			},
			questionsByUser:function(){
				window.location.href="<?php echo base_url('index.php/question/viewQuestionsOfUser?userid='.$_GET['userid']); ?>";
			},
			answersByUser:function(){
				window.location.href="<?php echo base_url('index.php/answer/viewAnswersOfUser?userid='.$_GET['userid']); ?>";
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					innerThis.userID=data['id'];
					innerThis.nameOfTargetUser=data['fullName'];
					if (data['followingStatus']=="Yes"){ innerThis.notFollowing=false; innerThis.following=true; innerThis.followingText="பின்தொடர்கிறீர்கள்"; }
					else{ innerThis.notFollowing=true; innerThis.following=false; innerThis.followingText="பின்தொடர"; }
					innerThis.questionsCount=data['questionsCount'];
					innerThis.answersCount=data['answersCount'];
					innerThis.followersCount=data['followersCount'];
					innerThis.aboutMe=data['aboutMe'];
					innerThis.avatarURL=innerThis.avatarURL+"/"+data['avatarURL'];
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/user/getUserData?userid='.$_GET['userid'].'&lookingUserName='.$username); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)
	app.mount('#mainDiv')
</script>
