<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="">Laravel Vue</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link class="nav-link active" :to="{name:'home'}">Home</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="btn nav-button" :to="{name:'basket'}">
                                Basket
                                <span class="badge bg-secondary" v-if="itemsInBasket">{{ itemsInBasket }}</span>
                                
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="!isLoggedIn">
                            <router-link :to="{name:'register'}" class="nav-link">Register</router-link>
                        </li>
                        <li class="nav-item" v-if="!isLoggedIn">
                            <router-link :to="{name:'login'}" class="nav-link">SignIn</router-link>
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <a class="nav-link" href="#" @click.prevent="logout">logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
export default {
    data(){
        return {
            lastSearch: this.$store.state.lastSearch
        }
    },
    computed:{
        ...mapState({
            lastSearchComputed: "lastSearch",
            isLoggedIn: "isLoggedIn"
        }),
        ... mapGetters({
            itemsInBasket: 'itemsInBasket'
        }),
        somethingElse(){
            return 1 + 2
        }
    },
    methods:{
        async logout(){
            try {
                axios.post("/logout")
                this.$store.dispatch("logout")
            } catch (error) {
                this.$store.dispatch("logout")
            }
        }
    }

}
</script>