<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bolder">
            Check Availabilty
            <transition>
                <span class="text-danger" v-if="noAvailability">(NOT AVAILABILITY)</span>
                <span class="text-success" v-if="hasAvailability">(AVAILABILITY)</span>
            </transition>
        </h6>

        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="from">From</label>
                <input 
                    type="text" 
                    name="from" 
                    id="from" 
                    class="form-control form-control-sm" 
                    placeholder="start date"
                    v-model="from"
                    @keyup.enter="check"
                    :class="[{'is-invalid' : errorFor('from')}]"
                >
                <v-errors :errors="errorFor('from')"></v-errors>
                <!-- <div class="invalid-feedback" v-for="(error, index) in this.errorFor('from')" :key="'from' + index">{{ error }}</div> -->
                
            </div>
            <div class="form-group col-md-6">
                <label for="to">To</label>
                <input 
                    type="text" 
                    name="to" 
                    id="to" 
                    class="form-control form-control-sm" 
                    placeholder="end date"
                    v-model="to"
                    @keyup.enter="check"
                    :class="[{'is-invalid' : errorFor('to')}]"
                >
                <v-errors :errors="errorFor('to')"></v-errors>
                <!-- <div class="invalid-feedback" v-for="(error, index) in this.errorFor('to')" :key="'to' + index">{{ error }}</div> -->
            </div>
        </div>

        <button class="btn btn-secondary btn-block mt-3" @click="check" :disabled="loading">
            <span v-if="!loading">Check!</span>
            <span v-if="loading"><font-awesome-icon icon="fas fa-spinner"/> Checking....</span>
        </button>
    </div>
</template>

<script>
import {is422} from "./../shared/utils/response"
import validationErrors from "./../shared/mixins/validationErrors"

export default {
    mixins:[validationErrors],
    props:{
        bookableId: Array/Object/String/Number
    },
    data(){
        return {
            from:this.$store.state.lastSearch.from,
            to:this.$store.state.lastSearch.to,
            loading:false,
            status:null,
        }
    },
    methods: {
        async check(){
            this.loading = true;
            this.errors = null;

            this.$store.dispatch('setLastSearch', {
                from:this.from,
                to:this.to
            })

            try {
                this.status = (await axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)).status
                this.$emit('availability', this.hasAvailability)
            } catch (err) {
                if(is422(err)){
                    this.errors = err.response.data.errors;
                    }

                    this.status = err.response.status;
                    this.$emit('availability', this.hasAvailability)
            }
            this.loading = false

            // axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)
            //     .then(response => {
            //         this.status = response.status;
            //     })
            //     .catch(err =>{
            //         if(is422(err)){
            //             this.errors = err.response.data.errors;
            //         }

            //         this.status = err.response.status;
            //     })
            //     .then(() => (this.loading = false));

        },
    },

    computed:{
        hasErrors(){
            return 422 === this.status && this.errors !== null;
        },

        hasAvailability(){
            return 200 === this.status;
        },

        noAvailability(){
            return 404 === this.status;
        }
    }
}
</script>
<style scoped>
    label{
        font-size: 0.7 rem;
        text-transform: uppercase;
        color: gray;
        font-weight: bolder;
    }

    .is-invalid{
        border-color: red;
        background-image: none;
    }

    .invalid-feedback{
        color: red;
    }


</style>