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
	<div class="card-header" align="center"><input type="text" class="form-control" v-on:change="search" v-model="searchString" placeholder="Type question here"/></div>
	<div class="card-body">
		<ol style="list-style:none;">
			<mycomponent
			v-for="question in questions"
			v-bind:myelement="question"
			v-bind:key="question.questionid"
			></mycomponent>
		</ol>
	</div>
</div>
<script type="text/x-template" id="idForTemplate"><div v-on:click="clickToQuestion(myelement.questionid)" style="margin:1%" class="card border-dark"><div class="card-body">{{myelement.questionText}}</div></div></script>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				searchString:"",
				questions:[]
			}
		},
		methods:{
			search:function(){
				if (this.searchString!=="") {
					this.questions=[];
					var innerThis=this;
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							var result=JSON.parse(xhttp.responseText);
							result.forEach(function(item){
								innerThis.questions.push({"questionid":item.questionid,"questionText":item.questionText});
							});
						}
					};
					xhttp.open("GET", "<?php echo base_url('index.php/general/searchQuestion?searchKey='); ?>"+innerThis.searchString, true);
					xhttp.send();
				}
				else{
					this.questions=[];
				}
			}
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement', 'questionid', 'questionText'],
		template: "#idForTemplate",
		methods : {
			clickToQuestion : function(questionid) {
				window.location.href="<?php echo base_url('index.php/question/viewSingleQuestionWithAnswers?questionid='); ?>"+questionid;
			}
		}
	});

	app.mount('#mainDiv')
</script>