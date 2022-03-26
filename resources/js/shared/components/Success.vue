<template>
    <div class="text-center success">
        <font-awesome-icon icon="fa-regular fa-thumbs-up"></font-awesome-icon>
        <h1 class="mt-4">Success!</h1>
        <h2>
            <slot></slot>
        </h2>
    </div>
</template>

<style scoped>
.success{
    color: #999;
    font-size: 10rem;

}
</style>