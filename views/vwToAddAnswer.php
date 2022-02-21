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
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%" id="mainDiv">
	<div class="card-header">{{questionText}}</div>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-12" style="margin:1%">
				<textarea v-on:keyup="scan()" placeholder="உங்களின் பதில் இயன்றவரை சுருக்கமாகவும் எழுத்துப்பிழைகள் இல்லாமலும் இருக்க வேண்டும்." class="form-control" rows="20" v-model="answerText" id="target"></textarea>
			</div>
		</div>
	</div>
	<div class="card-footer" align="center"><button type="button" v-on:click.native="addAnswer" class="btn btn-sm bg-dark text-white">சேர்க்கவும்</button></div>
</div>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				questionText:"கேள்வி load ஆகுகின்றது...",
				answerText:"",
				obj:{type:Object}
			}
		},
		methods:{
			scan:function(){
				fetch("<?php echo base_url('index.php/admin/scan?'); ?>typed="+encodeURI(this.answerText),{
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
			addAnswer:function(){
				if (this.answerText=="") {
					alert("மன்னிக்கவும்; உங்களின் பதில் வெறுமையாக இருக்கக்கூடாது!");
				}
				else{
					let answerData=new FormData();
					answerData.append("byUserName","<?php echo $username; ?>");
					answerData.append("wholeAnswer",this.answerText);
					answerData.append("questionID","<?php echo $_GET['questionID']; ?>");
					fetch("<?php echo base_url('index.php/answer/submitAnswer'); ?>",{
						method:'POST',
						body: answerData,
						mode: 'no-cors',
						cache: 'no-cache'})
					.then(response => {
						if (response.status == 200) {
							return response.json();            
						}
						else if (response.status == 500) {}{
							alert('Backend Error..!');
							console.log(response.text());
						}
					})
					.then(data => {
						alert(data.message);
						window.location.href="<?php echo base_url('index.php/question/viewSingleQuestionWithAnswers?questionid='.$_GET['questionID']); ?>";
					})
					.catch(() => {
						(console.log("Network connection error"));
						alert("Reloading"); //window.location.reload();
					});
				}
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					innerThis.questionText=data['questionText'];
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/question/deriveQuestionWithAnswers?questionID='.$_GET['questionID'].'&loggedInUserName='.$username); ?>", true);
			xhttp.send();
		},
		updated(){
			var innerThis=this;
			this.obj = $("#target");
			let option = {
				toolbar: [["style", ["highlight", "bold", "italic", "underline", "clear"]],["font", ["strikethrough", "superscript", "subscript"]],["para", ["ul", "ol", "paragraph"]],["view", ["codeview"]]]
			};
			option.callbacks = {
				onChange(contents) {
					innerThis.answerText=contents;
				}
			};
			this.obj.summernote(option);
		}
	}
	const app = Vue.createApp(componentVar);
	app.mount('#mainDiv');
</script>