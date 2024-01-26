<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{label}}</label>
        <slot></slot>
        <div :id="ajudaId" class="form-text text-muted">{{ajudaTexto}}</div>
    </div>
</template>

<script>
    export default {
        props: [
            'id',
            'label',
            'ajudaId',
            'ajudaTexto'
        ],
    }
</script>