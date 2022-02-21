<script src="<?php echo base_url('public/vue/vue@3.2.2'); ?>" crossorigin="anonymous"></script>
<div class="card border-dark" style="margin:1%" id="mainDiv">
    <div class="card-body">
        <ol>
            <container
                ref="childRef"
                v-for="element in elements"
                v-bind:myelement="element"
                v-bind:elementColor="element.elementColor"
                ></container>
        </ol>
    </div>
    <button v-on:click="changeColor">Change</button>
</div>

<script type="text/x-template" id="idForTemplate">
    <li v-bind:style="{color:myelement.elementColor}">{{ myelement.elementText }}</li>
</script>

<script type="text/javascript">
    const componentVar = {
        props:{},
        data() {
            return {
                elements:[],
            }
        },
        mounted(){
        	this.elements.push({"elementText":"Lord Vishnu","elementColor":"blue"});
        },
        methods:{
            changeColor:function(){
                this.$refs.childRef.$el.style.color="red";
            }
        }
    }
    const app = Vue.createApp(componentVar)

    app.component('container', {
        props: ['myelement','elementid','elementText','elementColor'],
        template: "#idForTemplate"
    });
    app.mount('#mainDiv')
</script>