<template>
    <div>
        <!-- <BookableListItem></BookableListItem> -->
        <!-- <bookable-list-item v-if="bookable1"
            :item-title=bookable1.title 
            :item-content=bookable1.content 
            :price=2010>
        </bookable-list-item>
        
        <bookable-list-item v-if="bookable2"
            :item-title=bookable2.title 
            :item-content=bookable2.content 
            :price=2017>
        </bookable-list-item> -->
        <div v-if="loading">Data is loading...</div>
        <div v-else>
            <div class="row mb-4" 
                v-for="row in rows" 
                :key="'row' + row">
                <div class="col d-flex align-items-stretch" 
                    v-for="(bookable, column) in bookableInRow(row) " 
                    :key="'row' + row + column">
                    <bookable-list-item 
                        v-bind="bookable">
                    </bookable-list-item>
                </div>
                <div class="col"
                    v-for="p in placeholderInrow(row)" 
                    :key="'placeholder' + p + row">
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
import BookableListItem from "./BookableListItem";
export default {
    components:{
        // BookableListItem: BookableListItem
        "bookable-list-item" : BookableListItem
    },
    data() {
        return {
            // bookable1: null,
            // bookable2: null,
            bookables:null,
            loading:true,
            columns:3
        }
    },
    // beforeCreate(){
    //     console.log('before create');
    // },
    computed:{
        rows(){
            return this.bookables == null?0:Math.ceil(this.bookables.length/this.columns) 
        }
    },
    methods: {
        bookableInRow(row){
            return this.bookables.slice((row - 1)* this.columns, row * this.columns)
        },
        placeholderInrow(row){
            return this.columns - this.bookableInRow(row).length;
        }
    },
    created() {
        // console.log('created');
        // console.log(this.bookable1);

        // this.loading = true

        // const p = new Promise((resolve, reject)=>{
        //     console.log(resolve);
        //     console.log(reject);

        //     setTimeout(() => resolve('hello'), 3000);
        // })
        // .then(result => "Hello again " + result)
        // .then(result => console.log(result))
        // .catch(err => console.log(`Error ${err}`));
        // console.log(p);

        const req = axios
            .get('/api/bookables')
            .then(res => {
                this.bookables = res.data.data
                this.loading = false
            });
        console.log(req);

        // setTimeout(() =>{
        //     // this.bookable1 = {
        //     //     title: "Cheap Villa",
        //     //     content: "a very cheap villa"
        //     // },
        //     // this.bookable2 = {
        //     //     title: "Cheap Villa 2",
        //     //     content: "a very cheap villa 2"
        //     // }
        //     this.bookables = [
        //         {
        //             // id:1,
        //             title: "Cheap Villa",
        //             content: "a very cheap villa"
        //         },
        //         {
        //             title: "Cheap Villa 2",
        //             content: "a very cheap villa 2"
        //         },
        //         {
        //             title: "Cheap Villa 2",
        //             content: "a very cheap villa 2"
        //         },
        //         {
        //             title: "Cheap Villa 2",
        //             content: "a very cheap villa 2"
        //         },
        //         {
        //             title: "Cheap Villa 2",
        //             content: "a very cheap villa 2"
        //         },
        //         {
        //             title: "Cheap Villa 2",
        //             content: "a very cheap villa 2"
        //         },
        //         {
        //             title: "Cheap Villa 2",
        //             content: "a very cheap villa 2"
        //         }
        //     ]
        //     this.loading = false
        // }, 2000);


        // setTimeout(() => {
        //     console.log('first change');
        //     this.bookable1.title = 'you will see this'
        // }, 8000);

        // setTimeout(() => {
        //     console.log('second change');
        //     this.bookable3.title = 'you never see this'
        // }, 12000);
    },
    // beforeMount() {
    //     console.log('before mount');
    // },
    // mounted() {
    //     console.log('mounted');
    // },
    // beforeDestroy() {
    //     console.log('before destroy');
    // },
    // destroyed() {
    //     console.log('destroyed');
    // },
}
</script>