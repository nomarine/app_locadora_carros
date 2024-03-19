<template>
    <div :class="estilo" role="alert">
        {{titulo}}
        <hr>
        <p>{{detalhes.mensagem}}</p>

        <ul v-if="detalhes.erros">
            <span v-for="campo, key in detalhes.erros" :key="key">
                <li v-for="detalhe, key in campo" :key="key">{{detalhe}}</li>
            </span>
        </ul>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: [
            'tipo',
            'titulo',
            'detalhes'
        ],
        computed: {
            estilo() {
                return `alert alert-${this.tipo}`
            }
        }
    }
</script>
