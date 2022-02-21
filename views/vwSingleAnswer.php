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
<script src="<?php echo base_url('public/js/jquery-3.6.0.js'); ?>" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%" id="mainDiv">
	<div class="row" style="margin:1%"><div class="col-lg-12">{{questionText}}</div></div>
	<div class="row" style="margin:1%">
		<div class="col-lg-10"><span style="font-size:12px" class="badge bg-primary text-white" v-on:click="toAuthorProfile(authorID)">{{authorName}}</span>&nbsp;{{answeredDateTime}}&nbsp;அன்று பதிலளித்தார்</div>
		<div class="col-lg-2">
			<button :disabled='notTheAuther' title="திருத்துவதற்கு" class="btn btn-sm border-dark" v-on:click="toEditAnswer"><i class="far fa-edit"></i></button>&nbsp;
			<button :disabled='notTheAuther' v-bind:class="{'btn btn-sm border-danger':canDelete,'btn btn-sm border-success':canRestore}" v-on:click="toDeleteOrModifyAnswer"><i v-bind:title="{'நீக்குவதற்கு...':canDelete,'மீட்டெடுப்பதற்கு...':canRestore}" v-bind:class="{'fas fa-trash-alt':canDelete,'fas fa-trash-restore-alt':canRestore}"> </i></button>
		</div>
	</div>
	<div class="row" style="margin:1%"><div class="col-lg-12"><button :disabled='cantfollow' v-bind:class="{'btn btn-sm border-dark text-dark':notFollowing,'btn btn-sm bg-dark text-white':following}" v-on:click="toFollowAnswer" >{{followingText}}</button></div></div>
	<div class="row" style="margin:1%"><div class="col-lg-12"><span v-html="answerText"></span></div></div>
	<div class="row" style="margin:1%">
		<div class="col-lg-12" align="center" style="margin:1%">
			<div class="btn-group" role="group">
				<button :disabled='upvoted' v-on:click="upVoteIt" class="btn btn-sm border-success"><i style="color:green" class="fas fa-thumbs-up"></i>&nbsp;{{upvotes}}</button><button v-on:click="viewVotes('up')" class="btn border-success btn-sm text-success" style="font-size:12px">ஆதரவு வாக்கு(கள்)</button>
			</div>&nbsp;
			<div class="btn-group" role="group">
				<button :disabled='downvoted' v-on:click="downVoteIt" class="btn btn-sm border-danger"><i style="color:red" class="fas fa-thumbs-down"></i>&nbsp;{{downvotes}}</button><button v-on:click="viewVotes('down')" class="btn border-danger btn-sm text-danger" style="font-size:12px">எதிர்வாக்கு(கள்)</button>
			</div>&nbsp;
			<div class="btn-group" role="group">
				<button class="btn btn-sm border-primary"><i style="color:blue" class="far fa-comment"></i>&nbsp;{{comments.length}}</button><button class="btn border-primary btn-sm text-primary" style="font-size:12px">கருத்து(கள்)</button>
			</div>&nbsp;
			<div class="btn-group" role="group">
				<button class="btn btn-sm border-dark"><i style="color:black" class="fas fa-users"></i>&nbsp;{{answerFollowersCount}}</button><button class="btn border-dark btn-sm text-dark" style="font-size:12px">பின்தொடர்பவர்(கள்)</button>
			</div>
		</div>
	</div>
	<div class="row" style="margin:1%"><div class="col-lg-12">
		<div class="row" style="margin:1%"><div class="col-lg-12"><textarea v-on:keyup="scan()" v-model="typedComment" name="typedComment" type="text" class="form-control" placeholder="Your comment here"></textarea></div></div>
		<div class="row" style="margin:1%"><div class="col-lg-12" align="center"><button class="btn btn-sm btn-danger" v-on:click="feedComment">கருத்திடுக</button></div></div></div></div>
		<div class="row" style="margin:1%">
			<div class="col-lg-12">
				<mycomponent v-for="comment in comments" v-bind:myelement="comment" v-bind:key="comment.commentid" ></mycomponent>
			</div>
		</div>

		<div ref="modal" class="modal fade" :class="{show, 'd-block': active}" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">{{modalTitle}}</h5>
						<button type="button" class="btn btn-sm bg-danger text-white" data-bs-dismiss="modal" aria-label="Close" v-on:click="dismissModal"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<ol style="list-style:none;">
							<mycomponent2 v-for="respondent in respondents" v-bind:myelement2="respondent" v-bind:key="respondent.respondentID" ></mycomponent2>
						</ol>
					</div>
				</div>
			</div>
		</div>

	</div>
	<script type="text/x-template" id="idForTemplate">
		<div class="card border-dark" style="margin:1%">
			<div class="card-body">
				<div class="row" style="margin:1%">
					<div class="col-lg-10">
						<span class="badge bg-primary text-white" v-on:click="toCommenterProfile(myelement.commenterID)">{{myelement.commenterName}}</span>
					</div>
					<div class="col-lg-2">{{myelement.updatedon}}</div>
				</div>
				<div class="row" style="margin:1%">
					<div class="col-lg-12">{{myelement.comment}}</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="respondentRow"><li v-on:click="toRespondentProfile(myelement2.respondentID)">{{myelement2.respondentName}}</li></script>

	<script type="text/javascript">
		const componentVar = {
			props:{},
			data() {
				return {
					answerID:null,
					questionText:null,
					authorID:"",
					authorName:null,
					answeredDateTime:null,
					cantfollow:false,
					following:false,
					notFollowing:true,
					followingText:"பின்தொடர",
					answerText:null,
					notTheAuther:true,
					canDelete:true,
					canRestore:true,
					upvoted:true,
					downvoted:true,
					upvotes:0,
					downvotes:0,
					answerFollowersCount:"0",
					typedComment:"",
					comments:[],
					active: false,
					show: false,
					modalTitle:"",
					respondents:[]
				}
			},
			methods:{
				scan:function(){
					fetch("<?php echo base_url('index.php/admin/scan?'); ?>typed="+encodeURI(this.typedComment),{
						method:'GET',
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
						if (data.result) {
							alert(data.message);
						}
					})
					.catch(() => {
						console.log("Network connection error");
					});
				},
				dismissModal:function(){
					this.show = !this.show;
					this.active = !this.active;
				},
				viewVotes:function(voteType){
					this.respondents=[];
					var innerThis=this;
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							if (voteType=="up") { innerThis.modalTitle="ஆதரவு வாக்களித்தவர்கள்"; }
							else if (voteType=="down") { innerThis.modalTitle="எதிர்வாக்களித்தவர்கள்"; }
							const body = document.querySelector("body");
							innerThis.active = !innerThis.active;
							innerThis.active ? body.classList.add("modal-open") : body.classList.remove("modal-open");
							setTimeout(() => (innerThis.show = !innerThis.show), 10);
							var data=JSON.parse(xhttp.responseText);
							data.forEach(function(item){
								innerThis.respondents.push({'respondentID':item.respondentid,'respondentName':item.respondent});
							});
						}
					};
					xhttp.open("GET", "<?php echo base_url('index.php/answer/getVotes?'); ?>answerID="+innerThis.answerID+"&voteType="+voteType, true);
					xhttp.send();
				},
				toAuthorProfile:function(userID){
					window.location.href="<?php echo base_url('index.php/user/singleuser?userid='); ?>"+userID;
				},
				feedComment:function(){
					if (this.typedComment!=="") {
						let commentData=new FormData();
						commentData.append("userName","<?php echo $username; ?>");
						commentData.append("answerID",this.answerID);
						commentData.append("comment",this.typedComment);
						fetch("<?php echo base_url('index.php/answer/getFeedComment'); ?>",{
							method:'POST',
							body: commentData,
							mode: 'no-cors',
							cache: 'no-cache'})
						.then(response => {
							if (response.status == 200) { return response.json(); }
							else { alert('Backend Error..!'); console.log(response); }
						})
						.then(data => { 
							alert(data.message);
							window.location.reload();
						})
						.catch(err => console.log(err));
					}
					else{ alert("மன்னிக்கவும்; உங்களின் கருத்து வெறுமையாக இருக்கக்கூடாது!"); }
				},
				toEditAnswer:function(){
					window.location.href="<?php echo base_url('index.php/answer/editAnswer?answerID='); ?>"+this.answerID;
				},
				toDeleteOrModifyAnswer:function(){
					var confirmText="";
					let action="";
					if (this.canDelete&&!this.canRestore){ action="delete"; confirmText="இந்தப் பதிலை நிச்சயமாக நீக்க விரும்புகின்றீர்களா..? நீக்கினாலும் நீங்கள் எப்போது வேண்டுமானாலும் இந்த பதிலை மீட்டெடுக்கலாம்."; }
					else if (!this.canDelete&&this.canRestore) { action="revoke";  confirmText="இந்தப் பதிலை நிச்சயமாக மீட்டெடுக்க விரும்புகின்றீர்களா..?"; }
					if (confirm(confirmText)) {
						var innerThis=this;
						let formData=new FormData();
						formData.append("answerID",this.answerID);
						formData.append("action",action);
						fetch("<?php echo base_url('index.php/answer/deleteOrModifyAnswer'); ?>",{
							method:'POST',
							body: formData,
							mode: 'no-cors',
							cache: 'no-cache'})
						.then(response => {
							if (response.status == 200) { return response.json(); }
							else { alert('Backend Error..!'); console.log(response); }
						})
						.then(data => {
							alert(data.message);
							window.location.reload();
						})
						.catch(err => console.log(err));
					}
				},
				upVoteIt:function(){ this.voteIt("up"); },
				downVoteIt:function(){ this.voteIt("down"); },
				voteIt:function(vote){
					var innerThis=this;
					let formData=new FormData();
					formData.append("userName","<?php echo $username; ?>");
					formData.append("answerID",this.answerID);
					formData.append("vote",vote);
					fetch("<?php echo base_url('index.php/answer/feedVote'); ?>",{
						method:'POST',
						body: formData,
						mode: 'no-cors',
						cache: 'no-cache'})
					.then(response => {
						if (response.status == 200) { return response.json(); }
						else { alert('Backend Error..!'); console.log(response); }
					})
					.then(data => { 
						if (vote=="up") { if(innerThis.downvoted){ innerThis.downvotes=parseInt(innerThis.downvotes)-1; } innerThis.upvoted=true; innerThis.downvoted=false; innerThis.upvotes=parseInt(innerThis.upvotes)+1; }
						else if (vote=="down") { if(innerThis.upvoted){ innerThis.upvotes=parseInt(innerThis.upvotes)-1; } innerThis.downvoted=true; innerThis.upvoted=false; innerThis.downvotes=parseInt(innerThis.downvotes)+1; }
					})
					.catch(err => console.log(err));
				},
				toFollowAnswer:function(){
					var innerThis=this;
					let followData=new FormData();
					followData.append("answerID",this.answerID);
					followData.append("loggedInUserName","<?php echo $username; ?>");
					fetch("<?php echo base_url('index.php/answer/followThisAnswer'); ?>",{
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
				}
			},
			mounted(){
				var innerThis=this;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var data=JSON.parse(xhttp.responseText);
						innerThis.answerID=data['answerid'];
						innerThis.questionText=data['questionText'];
						innerThis.answerText=data['answerText'];
						innerThis.authorID=data['userid'];
						innerThis.authorName=data['authorName'];
						innerThis.answeredDateTime=data['answeredOn'];
						innerThis.upvotes=data['upvotes'];
						innerThis.downvotes=data['downvotes'];
						innerThis.answerFollowersCount=data['answerFollowersCount'];
						var decodedComments=JSON.parse(data['encodedcomments']);
						var loggedInUserName="<?php echo $username; ?>";
						if (data['authorUserName']!=loggedInUserName) {
							if(data['givenFeedback']=='up'){ innerThis.upvoted=true; innerThis.downvoted=false; }
							else if(data['givenFeedback']=='down'){ innerThis.upvoted=false; innerThis.downvoted=true; }
							else { innerThis.upvoted=false; innerThis.downvoted=false; }
							if (data['followingStatus']=="Yes") { innerThis.following=true; cantfollow=true; innerThis.notFollowing=false; innerThis.followingText="பின்தொடர்கிறீர்கள்"; }
							else{ innerThis.following=false; cantfollow=false; innerThis.notFollowing=true; innerThis.followingText="பின்தொடர"; }

						}
						else{ innerThis.following=true; innerThis.notFollowing=false; innerThis.cantfollow=true; innerThis.followingText="பதிலளித்ததால் பின்தொடர்கிறீர்கள்"; innerThis.notTheAuther=false; 

						if (data['status']=="ACTIVE") {
							innerThis.canDelete=true;
							innerThis.canRestore=false;
						}
						else{
							innerThis.canDelete=false;
							innerThis.canRestore=true;
						}
						innerThis.upvoted=true; innerThis.downvoted=true; }
						decodedComments.forEach(function(item){
							innerThis.comments.push({"commentid":item.commentid,"comment":item.comment,"commenterID":item.commenterID,"commenterName":item.commenterName,"updatedon":item.updatedon});
						});
					}
				};
				xhttp.open("GET", "<?php echo base_url('index.php/answer/getAnswerWithQuestion?answerID='.$_GET['answerid'].'&userName='.$username); ?>", true);
				xhttp.send();
			}
		}
		const app = Vue.createApp(componentVar)
		
		app.component('mycomponent', {
			props: {
				myelement:{type:Object},
				commentid:{type:String},
				commenterID:{type:String},
				commenterName:{type:String},
				updatedon:{type:String},
				comment:{type:String}
			},
			template: "#idForTemplate",
			methods:{
				toCommenterProfile:function(userID){
					window.location.href="<?php echo base_url('index.php/user/singleuser?userid='); ?>"+userID;
				}
			}
		});

		app.component('mycomponent2', {
			props: {
				myelement2:{type:Object},
				respondentID:{type:String},
				respondentName:{type:String}
			},
			template: "#respondentRow",
			methods:{
				toRespondentProfile:function(userID){
					window.location.href="<?php echo base_url('index.php/user/singleuser?userid='); ?>"+userID;
				}
			}
		});

		app.mount('#mainDiv')
	</script>