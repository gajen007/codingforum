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
	<div class="card-header bg-default text-dark" align="center">{{questionText}}&nbsp;<span style="color:black">இந்தக் கேள்விக்கு பின்வரும் தலைப்புகள் சேர்க்கப்பட்டுள்ளன.</span></div>
	<div class="card-body">
		<div class="row" style="margin:1%"><div class="col-lg-12">
			<mycomponent v-for="heading in headings" v-bind:myelement="heading" v-bind:key="heading.headingid" ></mycomponent>
		</div></div>
		<div class="row" style="margin:1%"><div class="col-lg-12"><input class="form-control" v-model="headingText" placeholder="புதிய தலைப்பு"/></div></div>
	</div><div class="card-footer" align="center"><button v-on:click="addHeading" type="button" class="btn btn-sm bg-dark text-white">சேர்க்கவும்</button></div>
</div>
<script type="text/x-template" id="idForTemplate"><button style="margin:1%" v-on:click="clickToViewHeading(myelement.headingid)" class="btn bg-dark text-white">{{myelement.headingtext}}</button></script>

<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				headings: [],
				questionText:"",
				headingText:""
			}
		},
		methods:{
			addHeading:function(){
				if (this.headingText==="") { alert("மன்னிக்கவும்; உங்களின் தலைப்பு வெறுமையாக இருக்கக்கூடாது!"); }
				else{
					let headingData=new FormData();
					headingData.append("userName","<?php echo $username; ?>");
					headingData.append("questionID","<?php echo $_GET['questionID']; ?>");
					headingData.append("headingText",this.headingText);
					fetch("<?php echo base_url('index.php/heading/addNewHeadingToQuestion'); ?>",{
						method:'POST',
						body: headingData,
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
						alert(data.message); window.location.reload();
					})
					.catch(() => {
						(console.log("Network connection error"));
						alert("Reloading"); window.location.reload();
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
					data.forEach(function(item){
						innerThis.headings.push({"headingid":item.headingid,"headingtext":item.headingText});
						innerThis.questionText=item.questionText;
					});
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/heading/getHeadingsForQuestion?questionID='.$_GET['questionID']); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement','headingid','headingtext'],
		template: "#idForTemplate",
		methods : {
			clickToViewHeading : function(headingid) {
				window.location.href="<?php echo base_url('index.php/heading/singleHeading?headingid='); ?>"+headingid;
			},
		},
		mounted(){}
	});

	app.mount('#mainDiv')
</script>