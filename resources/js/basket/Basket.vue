<template>
    <div>
        <success v-if="success">
            Congratulations on your purchase
        </success>
        <div class="row" v-else>
            <div class="col-md-8" v-if="itemsInBasket">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="firstname">First Name</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="firstname" 
                            name="firstname" 
                            v-model="customer.firstname" 
                            :class="[{'is-invalid' : errorFor('customer.firstname')}]">
                        <v-errors :errors="errorFor('customer.firstname')"></v-errors>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="lastname">Last Name</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="lastname" 
                            name="lastname" 
                            v-model="customer.lastname"
                            :class="[{'is-invalid' : errorFor('customer.lastname')}]">
                        <v-errors :errors="errorFor('customer.lastname')"></v-errors>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="email">Email</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            v-model="customer.email"
                            :class="[{'is-invalid' : errorFor('customer.email')}]">
                        <v-errors :errors="errorFor('customer.email')"></v-errors>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="street">Street</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="street" 
                            name="street" 
                            v-model="customer.street"
                            :class="[{'is-invalid' : errorFor('customer.street')}]">
                        <v-errors :errors="errorFor('customer.street')"></v-errors>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="city">City</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="city" 
                            name="city" 
                            v-model="customer.city"
                            :class="[{'is-invalid' : errorFor('customer.city')}]">
                        <v-errors :errors="errorFor('customer.city')"></v-errors>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="country">Country</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="country" 
                            name="country" 
                            v-model="customer.country"
                            :class="[{'is-invalid' : errorFor('customer.country')}]">
                        <v-errors :errors="errorFor('customer.country')"></v-errors>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="state">State</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="state" 
                            name="state" 
                            v-model="customer.state"
                            :class="[{'is-invalid' : errorFor('customer.state')}]">
                        <v-errors :errors="errorFor('customer.state')"></v-errors>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="zip">Zip</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="zip" 
                            name="zip" 
                            v-model="customer.zip"
                            :class="[{'is-invalid' : errorFor('customer.zip')}]">
                        <v-errors :errors="errorFor('customer.zip')"></v-errors>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" @click.prevent="book">Book Now</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8" v-else>
                <div class="jumbotron d-flex justify-content-center align-items-center">
                    <h1>Empty</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-flex justify-content-between">
                    <h6 class="text-uppercase text-secondary font-weight-border">Your Cart</h6>
                    <h6 class="badge bg-secondary text-uppercase">
                        <span v-if="itemsInBasket">Items {{ itemsInBasket }}</span>
                        <span v-else>Empty</span>
                    </h6>
                </div>

                <transition-group name="fade">
                    <div v-for="item in basket" :key="item.bookable.id">
                        <div class="pt-2 pb-2 border-top d-flex justify-content-between">
                            <span>
                                <router-link :to="{nama:'bookable', params:{id:item.bookable.id}}">{{ item.bookable.title }}</router-link>
                            </span>
                            <span class="font-weight-bold">${{ item.price.total }}</span>
                        </div>

                        <div class="pt-2 pb-2 d-flex justify-content-between">
                            <span>From {{ item.dates.from }}</span>
                            <span>To {{ item.dates.to }}</span>
                        </div>

                        <div class="pt-2 pb-2 text-right">
                            <button 
                                class="btn btn-sm btn-outline-secondary" 
                                @click="$store.dispatch('removeFromBasket', item.bookable.id)">
                                <font-awesome-icon icon="fa-solid fa-trash-alt"></font-awesome-icon>
                            </button>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapState} from "vuex"
import Success from '../shared/components/Success.vue'
import validationErrors from '../shared/mixins/validationErrors'
export default {
  components: { Success },
    mixins:[validationErrors],
    data(){
        return {
            loading:false,
            bookingAtempted: false,
            customer:{
                firstname:null,
                lastname:null,
                email:null,
                street:null,
                city:null,
                country:null,
                state:null,
                zip:null
            }
        }
    },
    computed:{
        ...mapState({
            basket: state => state.basket.items
        }),
        ... mapGetters(["itemsInBasket"]),
        success(){
            return !this.loading && 0 === this.itemsInBasket && this.bookingAtempted
        }
    },
    methods:{
        async book(){
            this.loading = true
            this.errors = null
            this.bookingAtempted = false

            try {
                await axios.post(`/api/checkout`, {
                    customer: this.customer,
                    bookings: this.basket.map(basketItem => ({
                        bookable_id: basketItem.bookable.id,
                        from: basketItem.dates.from,
                        to: basketItem.dates.to,


                    }))
                })
                this.$store.dispatch('clearBasket');
            } catch (error) {
                this.errors = error.response && error.response.data.errors 
            }

            this.loading = false
            this.bookingAtempted = true

        }
    }
        
    
}
</script>

<style scoped>
h6.badge{
    font-size: 100%;
}

a{
    color: black;
}

.jumbotron{
    height: 300px;
    background-color: #6C757D;
    color: #fff;
}
</style>