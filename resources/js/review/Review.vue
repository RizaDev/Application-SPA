<template>
    <div >
        <success v-if="success">
            You've left a review, thanks very much
        </success>
        <fatal-error v-if="error"></fatal-error>
        <div class="row" v-if="!success && !error">
            <div :class="[{'col-md-4' : twoColumn},{'d-none' : oneColumn}]">
                <div class="card">
                    <div class="card-body">
                        <div v-if="loading">Loading....</div>
                        <div v-if="hasBooking">
                            <p>
                                Stayed at 
                                <router-link :to="{name:'bookable', params:{id:booking.bookable.bookable_id}}">{{ booking.bookable.title }}</router-link>
                            </p>
                            <p>
                                From {{ booking.from }} to {{ booking.to }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="[{'col-md-8' : twoColumn}, {'col-md-12': oneColumn}]">
                <div v-if="loading">Loading...</div>
                <div v-else>
                    <div v-if="alreadyReviewed">
                        <h3>You already left a review for this booking!</h3>
                    </div>
                    <div v-else>
                        <div class="form-group">
                            <label class="text-muted">Select the star rating (1 is worst - 5 is best)</label>
                            <!-- <star-rating :rating="5" class="fa-3x" v-on:rating:changed="onRatingChanged"></star-rating> -->
                            <!-- <star-rating 
                                :rating="review.rating" 
                                class="fa-3x" 
                                v-on:rating:changed="review.rating = $event">
                            </star-rating> -->
                            <star-rating 
                                class="fa-3x" 
                                v-model="review.rating">
                            </star-rating>
                        </div>
                        <div class="form-group">
                            <label for="content" class="text-muted">Describe your experience with</label>
                            <textarea 
                                name="content" 
                                cols="30" 
                                rows="10" 
                                class="form-control" 
                                v-model="review.content"
                                :class="[{'is-invalid' : errorFor('content')}]">
                            </textarea>
                            <v-errors :errors="errorFor('content')"></v-errors>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <button 
                                class="btn btn-lg btn-primary btn-block" 
                                @click.prevent="submit"
                                :disabled="sending">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import {is404, is422} from "./../shared/utils/response"
import validationErrors from "./../shared/mixins/validationErrors"

export default {
    mixins:[validationErrors],
    data() {
        return {
            review:{
                id:null,
                rating:5,
                content:null
            },
            existingReview: null,
            loading:false,
            booking: null,
            error:false,
            sending:false,
            success:false,
        }
    },
    async created() {
        this.review.id = this.$route.params.id
        this.loading = true
        // 1. If review already exists (in reviews table by Id)
        try{
            this.existingReview = (await axios.get(`/api/reviews/${this.review.id}`)).data.data
        }catch (err){
            if(is404(err)){
                try {
                    this.booking = (await axios.get(`/api/booking-by-review/${this.review.id}`)).data.data
                } catch (err) {
                    this.error = !is404(err)
                }
            }else{
                this.error = true
            }
        }
        this.loading = false
        // axios
        //     .get(`/api/reviews/${this.review.id}`)
        //     .then(res => this.existingReview = res.data.data)
        //     // .catch(err => console.log(err.response))
        //     .catch(err => {
                
        //         if(is404(err)){
        //             // 2. Fetch a booking by a review key
        //             return axios
        //                 .get(`/api/booking-by-review/${this.review.id}`)
        //                 .then(res => {
        //                     this.booking = res.data.data
        //                 })
        //                 .catch(err =>{
        //                     this.error = !is404(err);
        //                     // is404(err) ? {} : this.error = true
        //                     // if(!is404(err)){
        //                     //     this.error = true
        //                     // }
        //                 })
        //         }

        //         this.error = true
        //     })
        //     .then(res=> {
        //         // console.log(this.booking.booking_id);
        //         // console.log(res)
        //         this.loading = false
        //     }) 
        
        
    },
    computed:{
        alreadyReviewed(){
            return this.hasReview || !this.hasBooking
        },
        hasReview(){
            return this.existingReview !== null
        },
        hasBooking(){
            return this.booking !== null
        },
        oneColumn(){
            return !this.loading && this.alreadyReviewed
        },
        twoColumn(){
            return this.loading || !this.alreadyReviewed
        }
    },
    // methods: {
    //     onRatingChanged(rating){
    //         console.log(rating);
    //     }
    // },
    methods: {
        async submit(){
            // 3. Store the review
            this.errors = null
            this.sending = true
            this.success = false

            try {
                this.success = 201 === (await axios.post(`/api/reviews`, this.review)).status
            } catch (err) {
                // if(is422(err)){
                //     const errors = err.response.data.errors

                //     if(errors['content'] && 1 === _.size(errors)){
                //         this.errors = errors
                //         return
                //     }

                // }
                // this.error = true
            }
            this.sending = false
            // axios
            //     .post(`/api/reviews`, this.review)
            //     .then(res => {
            //         this.success = 201 === res.status
            //     })
            //     .catch(err => {
            //         if(is422(err)){
            //             const errors = err.response.data.errors

            //             if(errors['content'] && 1 === _.size(errors)){
            //                 this.errors = errors
            //                 return
            //             }

            //         }
            //         this.error = true
            //     })
            //     .then(() => this.sending = false)
        },
    },
}
</script>

