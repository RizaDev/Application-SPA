<template>
    <div class="w-50 m-auto">
        <div class="card card-body">
            <form>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input 
                        type="text"
                        name="name"
                        placeholder="Enter your name"
                        class="form-control"
                        v-model="user.name"
                        :class="[{'is-invalid' : errorFor('name')}]">
                    <v-errors :errors="errorFor('name')"></v-errors>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="text"
                        name="email"
                        placeholder="Enter your email"
                        class="form-control"
                        v-model="user.email"
                        :class="[{'is-invalid' : errorFor('email')}]">
                    <v-errors :errors="errorFor('email')"></v-errors>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="form-control"
                        v-model="user.password"
                        :class="[{'is-invalid' : errorFor('password')}]">
                    <v-errors :errors="errorFor('password')"></v-errors>
                </div>
                <div class="form-group">
                    <label for="password">Re-Type Password</label>
                    <input 
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        class="form-control"
                        v-model="user.password_confirmation"
                        :class="[{'is-invalid' : errorFor('password_confirmation')}]">
                    <v-errors :errors="errorFor('password_confirmation')"></v-errors>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button
                        type="submit"
                        class="btn btn-primary btn-lg btn-block"
                        :disabled="loading"
                        @click.prevent="register">
                        Register
                    </button>
                </div>

                <hr>

                <div>
                    Already have an account?
                    <router-link :to="{name:'login'}" class="font-weight-bold">Login</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import validationErrors from '../shared/mixins/validationErrors'
import { logIn } from "../shared/utils/auth"
export default {
    mixins: [validationErrors],
    data(){
        return{
            user:{
                name:null,
                email:null,
                password:null,
                password_confirmation:null
            },
            loading:false
        }
    },
    methods:{
        async register(){
            this.loading = true
            this.errors = null

            try {
                const response = await axios.post('/register', this.user)
                if(response.status == 201){
                    logIn()
                    this.$store.dispatch('loadUser')
                    this.$router.push({name:'home'})
                }
            } catch (error) {
                this.errors = error.response && error.response.data.errors
            }

            this.loading = false
        }
    }
}
</script>