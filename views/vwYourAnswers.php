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
<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%" id="mainDiv">
	<div class="card-header bg-dark text-white" align="center">நீங்கள் பதிலளித்த கேள்விகள்</div>
	<div class="card-body">
		<ol style="list-style:none;">
			<mycomponent v-for="answer in answers" v-bind:myelement="answer" v-bind:key="answer.questionid"></mycomponent>
		</ol>
	</div>
</div>

<script type="text/x-template" id="idForTemplate"><div style="margin:1%" class="card border-dark"><div class="card-header bg-dark text-white">{{myelement.questionText}}</div><div class="card-body" v-on:click="clickToAnswer(myelement.answerid)" style='margin:1%' v-html="myelement.answerTextBrief"></div><div class="card-footer bg-dark text-white" align="center"><sub><i class="far fa-clock"></i>&nbsp;{{myelement.createdon}}</sub></div></div></script>

<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				answers:[]
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					data.forEach(function(item){
						innerThis.answers.push({"answerid":item.answerid,"answerTextBrief":item.answerTextBrief+"...","createdon":item.createdon,"questionText":item.questionText});
					});
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/answer/getAnswersOfUserWithItsQuestion?userName='.$username); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement','answerid','questionText','answerTextBrief','createdon'],
		template: "#idForTemplate",
		methods : {
			clickToAnswer : function(answerid) {
				window.location.href="<?php echo base_url('index.php/answer/viewSingleAnswerWithQuestion?answerid='); ?>"+answerid;
			},
		}
	});

	app.mount('#mainDiv')
</script>