<template>
    <div class="d-flex align-items-center">
        <font-awesome-icon 
            v-for="star in fullStar" 
            :key="'full' + star" 
            icon="fa-solid fa-star"
            @click="$emit('input', star)" 
        />
        <font-awesome-icon v-if="halfStar" icon="fa-solid fa-star-half-alt" />
        <font-awesome-icon 
            v-for="star in emptyStar" 
            :key="'empty' + star" 
            icon="fa-regular fa-star"
            @click="$emit('input', fullStar + star)" 
        />

    </div>
</template>

<script>
export default {
    props:{
        value: Number
    },

    computed:{
        halfStar(){
            if(this.fullStar !== 5 && this.emptyStar === 0){
                return true;
            }else{
                return false;
            }
        },
        fullStar(){
            // > 4.5 = 5 star
            // 4.3 = 4 star dan half
            return Math.round(this.value);
        },
        emptyStar(){

            return 5 - Math.ceil(this.value);
        }
    }
}
</script>