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
<script src="<?php echo base_url('public/vue/vue@3.2.2'); ?>" crossorigin="anonymous"></script>
<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%;width:98%" id="mainDiv">
<div class="card-header bg-dark text-white" align="center">பின்தொடரப்படும் தலைப்புகள்</div>
<div class="card-body">
	<ol style="list-style:none">
		<mycomponent v-for="heading in headings" v-bind:myelement="heading" v-bind:key="heading.headingid" ></mycomponent>
	</ol>
</div>
</div>
<script type="text/x-template" id="idForTemplate"><button style="margin:1%" v-on:click="clickToViewHeading(myelement.headingid)" class="btn bg-dark text-white">{{myelement.headingtext}}</button></script>

<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				headings: [],
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
			xhttp.open("GET", "<?php echo base_url('index.php/heading/getFollowingHeadingsByThisUser?loggedInUserName='.$username); ?>", true);
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