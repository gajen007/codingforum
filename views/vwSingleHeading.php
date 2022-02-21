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
<div class="card border-dark"  style="margin:1%;position:absolute;top:7.5%" id="mainDiv">
	<div class="card-header" align="center"><strong>{{headingText}}</strong> என்ற தலைப்பில் சேர்க்கப்பட்ட கேள்விகள்</div>
	<div class="card-body">
		<ol style="list-style:none;">
			<mycomponent
			v-for="question in questions"
			v-bind:myelement="question"
			v-bind:key="question.studentid"
			></mycomponent>
		</ol>
	</div>
	<div class="card-footer" align="center"><button :disabled="cantFollow" v-bind:class="{'btn btn-sm border-dark text-dark':notFollowing,'btn btn-sm bg-dark text-white':following}" v-on:click="toFollowHeading">{{followingText}}</button></div>
</div>

<script type="text/x-template" id="idForTemplate"><div v-on:click="clickToQuestion(myelement.questionid)" style='margin:1%' class='card border-dark'><div class='card-body'>{{ myelement.questionText }}</div></div></script>

<script type="text/javascript">
	const componentVar = {
		data() {
			return {
				questions: [],
				headingText:"",
				cantFollow:false,
				notFollowing:true,
				following:false,
				followingText:""
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var result=JSON.parse(xhttp.responseText);
					var questionsData=result['questionsData'];
					innerThis.headingText=result['headingText'];
					if (result['followingStatus']=="Yes") { innerThis.notFollowing=false; innerThis.following=true; innerThis.followingText="பின்தொடர்கிறீர்கள்"; }
					else{ innerThis.notFollowing=true; innerThis.following=false; innerThis.followingText="பின்தொடர"; }
					questionsData.forEach(function(item){
						innerThis.questions.push({"questionid":item.questionid,"questionText":item.questionText});
					});
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/heading/getQuestionsOfHeading?headingID='.$_GET['headingid'].'&loggedInUserName='.$username); ?>", true);
			xhttp.send();
		},
		methods:{
			toFollowHeading:function(){
				var innerThis=this;
				let followData=new FormData();
				followData.append("headingID","<?php echo $_GET['headingid']; ?>");
				followData.append("loggedInUserName","<?php echo $username; ?>");
				fetch("<?php echo base_url('index.php/heading/followThisHeading'); ?>",{
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
					if (innerThis.following){ innerThis.following=false; innerThis.notFollowing=true; innerThis.followingText="பின்தொடர"; }
					else{ innerThis.following=true; innerThis.notFollowing=false; innerThis.followingText="பின்தொடர்கிறீர்கள்"; }
				})
				.catch(() => {
					(console.log("Network connection error"));
					alert("Reloading"); window.location.reload();
				});
			}
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement','questionid'],
		template: "#idForTemplate",
		methods : {
			clickToQuestion : function(questionid) {
				window.location.href="<?php echo base_url('index.php/question/viewSingleQuestionWithAnswers?questionid='); ?>"+questionid;
			},
		}
	});

	app.mount('#mainDiv')
</script>